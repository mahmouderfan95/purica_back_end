<?php
namespace App\Services\Payment\Strategies;
use App\Models\Cart;
use App\Models\Order;
use App\Repositories\Front\CartRepository;
use App\Repositories\Front\CouponRepository;
use App\Repositories\Front\OrderRepository;
use App\Services\Front\OrderService;
use App\Services\Payment\Contracts\PaymentStrategyInterface;
use App\Services\Payment\DTOs\PaymentResult;

class CashOnDeliveryStrategy implements PaymentStrategyInterface
{
    public function __construct(
        private OrderRepository $orderRepository,
        private CartRepository $cartRepository,
        private CouponRepository $couponRepository,
    ){}
    public function pay(Cart $cart, Order $order): PaymentResult
    {
        $this->orderRepository->decreaseStock($cart);

        $this->couponRepository->updateCouponUsage($order->coupon);

        $this->cartRepository->clearCart($cart);

        return new PaymentResult(
            success: true,
            message: 'Cash on delivery selected.'
        );
    }
}
