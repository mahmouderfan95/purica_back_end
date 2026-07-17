<?php
namespace App\Services\Payment;
use App\Models\Cart;
use App\Models\Order;
use App\Services\Payment\DTOs\PaymentResult;

class PaymentContext
{
    public function __construct(
        private PaymentStrategyFactory $factory
    ) {
    }

    public function handle(
        string $paymentType,
        Cart $cart,
        Order $order
    ): PaymentResult {

        return $this->factory
            ->make($paymentType)
            ->pay($cart,$order);
    }
}
