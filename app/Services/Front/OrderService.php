<?php
namespace App\Services\Front;
use App\Repositories\Front\OrderRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    use ApiResponseAble;
    public function __construct(public OrderRepository $orderRepository){}
    public function index($request) : JsonResponse
    {
        try{
            $orders = $this->orderRepository->getOrders($request);
            if(!$orders->isEmpty())
                return $this->ApiSuccessResponse(OrderCollection::make($orders));
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of create order' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function store($request) : JsonResponse
    {
        $egypt  = Country::query()->first();
        $user = auth('api')->user();

        $cart = Cart::with('items.product')
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->count() === 0) {
            return $this->ApiErrorResponse([],'السلة فارغة', 400);
        }

        DB::beginTransaction();
        try {
            $conversionImage = null;
            if ($request->hasFile('conversion_image')) {
                $conversionImage = $this->save_file(
                    $request->file('conversion_image'),
                    'orders'
                );
            }
            // -----------------------------------------
            // 2) Handle Coupon / Promo Code (Senior Way)
            // -----------------------------------------
            $discountValue = 0;
            $coupon = null;

            if ($request->filled('coupon_code')) {

                $coupon = $this->couponRepository->findValidCoupon($request->coupon_code);

                if (!$coupon) {
                    return $this->ApiErrorResponse([], 'الكوبون منتهى الصلاحية', 400);
                }

                // Check usage limit
                if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                    return $this->ApiErrorResponse([], 'تم تجاوز الحد الاقصى من الكوبون', 400);
                }

                // Check minimum order total
                if ($coupon->min_order_total && $cart->total < $coupon->min_order_total) {
                    return $this->ApiErrorResponse([], 'اجمالى الطلب اقل من الحد الادنى المطلوب لاستخدام للكوبون', 400);
                }

                // Apply discount
                $discountValue = $coupon->value;
            }
            // Apply final total after discount
            $finalTotal = max(0, $cart->total - $discountValue + $request->shipping_cost);
            $order = Order::query()->create([
                'user_id'        => $user->id,
                'total'          => $finalTotal,
                'payment_type' => $request->payment_type ?? 'cod',
                'address'        => $request->address,
                'notes'          => $request->notes,
                'status'         => 'pending',
                'country_id'     => $egypt->id,
                'city_id'        => $request->city_id,
//                'region_id'      => $request->region_id,
                'wallet_number'   => $request->wallet_number ?? null,
                'conversion_image' => $conversionImage,
                'discount'         => $discountValue,
                'coupon_id'      => $coupon?->id,
                'shipping_company_id' => $request->shipping_company_id ?? null,
                'shipping_cost' => $request->shipping_cost ?? null,
                'addition_type' => 'from_customer',
                'client_name' => $request->name,
                'client_phone' => $request->phone,
            ]);
            foreach ($cart->items as $item) {
                $variant = ProductVariant::query()
                    ->where('product_id', $item->product_id)
                    ->where('selected_options', $item->selected_options)
                    ->lockForUpdate()
                    ->first();

                if (!$variant) {
                    return $this->ApiErrorResponse([],'Product variant not found',400);
                }

                if ($variant->available_quantity < $item['quantity']) {
                    return $this->ApiErrorResponse([],trans('general.Insufficient_stock_product'),400);
                }

                $variant->available_quantity -= $item->quantity;
                $variant->save();
                OrderItem::query()->create([
                    'order_id'        => $order->id,
                    'product_id'      => $item->product_id,
                    'quantity'        => $item->quantity,
                    'price'           => $item->price,
                    'total'           => $item->total,
                    'selected_options'=> $item->selected_options,
                ]);
            }
            // ----------------------------
            // 5) Increase coupon usage
            // ----------------------------
            if ($coupon) {
                $coupon = Coupon::query()
                    ->where('id', $coupon->id)
                    ->lockForUpdate()
                    ->first();

                if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                    return $this->ApiErrorResponse([], 'Coupon usage limit exceeded', 400);
                }
                $coupon->increment('used_count');
            }
            $cart->items()->delete();
            $cart->update(['total' => 0]);
            $exists = User::query()
                ->where('phone', $request->phone)
                ->where('id', '!=', $user->id)
                ->exists();

            if (!$exists) {
                $user->update([
                    'name'  => $request->name,
                    'phone' => $request->phone,
                ]);
            }
            DB::commit();
            return $this->ApiSuccessResponse(OrderResource::make($order->load('items','city','region')),'Order created successfully');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::error('error of create order' . $e->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function show($id) : JsonResponse
    {
        try{
            $order = $this->orderRepository->getModelById($id);
            if(!$order)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(OrderResource::make($order));
        }catch (\Exception $exception){
            Log::error('error of view order' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function cancel($request) : JsonResponse
    {
        try{
            $user = auth('api')->user();
            $order = $this->orderRepository->getModelById($request->order_id);
            if(!$order){
                return $this->notFoundResponse();
            }
            if($order->user_id != $user->id)
            {
                return $this->ApiErrorResponse([], trans('general.you_dont_have_access_to_cancel_order'),403);
            }
            if (! in_array($order->status, [OrderStatusEnum::PENDING, OrderStatusEnum::PROCESSING])) {
                return $this->ApiErrorResponse([], trans('general.you_dont_have_cant_cancel_order'),400);
            }
            DB::transaction(function () use ($order,$request) {
                $order->update([
                    'status' => OrderStatusEnum::CANCELED,
                    'cancel_reason' => $request->cancel_reason,
                    'cancelled_at' => now(),
                ]);
                $order->items()->update(['status' => OrderStatusEnum::CANCELED]);
            });
            return $this->ApiSuccessResponse(OrderResource::make($order),'order cancelled');
        }catch (\Exception $exception)
        {
            Log::error('error of cancel order' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
