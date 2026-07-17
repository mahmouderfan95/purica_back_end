<?php
namespace App\Services\Payment\Strategies;
use App\Models\Order;
use App\Services\Payment\Contracts\PaymentStrategyInterface;
use App\Services\Payment\DTOs\PaymentResult;

class VisaPaymentStrategy implements PaymentStrategyInterface
{
    public function pay(Order $order): PaymentResult
    {
        return new PaymentResult(
            success: true,
            message: 'Visa payment initialized.'
        );
    }
}
