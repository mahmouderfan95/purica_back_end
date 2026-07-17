<?php
namespace App\Services\Payment;
use App\Services\Payment\Contracts\PaymentStrategyInterface;
use App\Services\Payment\Strategies\CashOnDeliveryStrategy;
use App\Services\Payment\Strategies\VisaPaymentStrategy;
use InvalidArgumentException;
class PaymentStrategyFactory
{
    public function make(string $paymentType): PaymentStrategyInterface
    {
        return match ($paymentType) {

            'cod' => app(CashOnDeliveryStrategy::class),

            'visa' => app(VisaPaymentStrategy::class),

            default => throw new InvalidArgumentException(
                'Unsupported payment type.'
            ),
        };
    }
}
