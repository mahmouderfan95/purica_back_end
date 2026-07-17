<?php
namespace App\Repositories\Front;
use App\Enums\AdditionTypeEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariants;
use App\Models\User;
use Illuminate\Http\Request;

class OrderRepository
{
    public function getOrders($request)
    {
        $user = auth('api')->user();
        $status = $request->status;
        return  $this->getModel()::query()
            ->where('user_id', $user->id)
            ->with('items.product','city','region')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderByDesc('id')
            ->paginate(PAGINATION_COUNT_WEB);
    }
    public function getModelById($id)
    {
        return $this->getModel()::query()
            ->with(['user','items.product','city','region'])
            ->where('id', $id)
            ->first();
    }
    public function createOrder(
        User $user,
        Request $request,
        Country $country,
        float $finalTotal,
        float $discountValue,
        ?Coupon $coupon,
    ) {

        return $this->getModel()::query()->create([
            'user_id'               => $user->id,
            'total'                 => $finalTotal,
            'payment_type'          => $request->payment_type ?? 'cod',
            'address'               => $request->address,
            'notes'                 => $request->notes,
            'status'                => OrderStatusEnum::PENDING,
            'country_id'            => $country->id,
            'city_id'               => $request->city_id,
            'discount'              => $discountValue,
            'coupon_id'             => $coupon?->id,
            'shipping_company_id'   => $request->shipping_company_id,
            'shipping_cost'         => $request->shipping_cost,
            'addition_type'         => AdditionTypeEnum::CUSTOMER,
            'client_name'           => $request->name,
            'client_phone'          => $request->phone,
        ]);
    }
    public function processOrderItems(Order $order, Cart $cart): void
    {
        foreach ($cart->items as $item) {

            OrderItem::query()->create([
                'order_id'         => $order->id,
                'product_id'       => $item->product_id,
                'quantity'         => $item->quantity,
                'price'            => $item->price,
                'total'            => $item->total,
                'selected_options' => $item->selected_options,
            ]);
        }
    }
    public function decreaseStock(Cart $cart): void
    {
        $cart->loadMissing('items');

        foreach ($cart->items as $item) {

//            dd($item);
            $variant = ProductVariants::query()
                ->where('product_id', $item->product_id)
                ->whereJsonContains('selected_options', json_decode($item->selected_options, true))
                ->first();


            if (!$variant) {
                throw new \RuntimeException('Product variant not found.');
            }
            if ($variant->available_quantity < $item->quantity) {
                throw new \RuntimeException(trans('general.Insufficient_stock_product'));
            }

            $variant->decrement('available_quantity', $item->quantity);
        }
    }
    private function getModel() : Order
    {
        return new Order();
    }
}
