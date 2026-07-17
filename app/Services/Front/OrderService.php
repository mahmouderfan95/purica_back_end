<?php
namespace App\Services\Front;
use App\Enums\OrderStatusEnum;
use App\Http\Resources\Admin\Orders\OrderCollection;
use App\Http\Resources\Front\Orders\OrderResource;
use App\Models\Country;
use App\Repositories\Admin\CountryRepository;
use App\Repositories\Front\CartRepository;
use App\Repositories\Front\CouponRepository;
use App\Repositories\Front\OrderRepository;
use App\Repositories\Front\UserRepository;
use App\Services\Payment\PaymentContext;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    use ApiResponseAble;
    public function __construct(
        public OrderRepository $orderRepository,
        public CartRepository $cartRepository,
        public CouponRepository $couponRepository,
        public UserRepository $userRepository,
        public PaymentContext $paymentContext,
    ){}
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
        $cart = $this->cartRepository->getCartByUserId($user->id);

        if (!$cart || $cart->items->count() === 0) {
            return $this->ApiErrorResponse([],'السلة فارغة', 400);
        }

        DB::beginTransaction();
        try {
            // -----------------------------------------
            // 2) Handle Coupon / Promo Code (Senior Way)
            // -----------------------------------------
            $couponData = $this->couponRepository->validateCoupon(
                $request->coupon_code,
                $cart->total
            );
            $coupon = $couponData['coupon'];
            $discountValue = $couponData['discount'];
            // Apply final total after discount
            $finalTotal = max(0, $cart->total - $discountValue + $request->shipping_cost);
            #create order
            $order = $this->orderRepository->createOrder(
                user: $user,
                request: $request,
                country: $egypt,
                finalTotal: $finalTotal,
                discountValue: $discountValue,
                coupon: $coupon,
            );
            #create order items
            $this->orderRepository->processOrderItems($order,$cart);
            #decrese stock
            $this->orderRepository->decreaseStock($cart);
            // ----------------------------
            // 5) Increase coupon usage
            // ----------------------------
            $this->couponRepository->updateCouponUsage($coupon);
            #clear cart
            $this->cartRepository->clearCart($cart);
            $this->userRepository->syncCustomerInformation($user,$request);
            #payment
            $result = $this->paymentContext->handle(
                $request->payment_type,
                $cart,
                $order
            );
            DB::commit();
            return $this->ApiSuccessResponse(
                [
                    'order'=>OrderResource::make($order->load('items','city','region')),
                    'payment'=>$result
                ],
                'Order created successfully'
            );
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
