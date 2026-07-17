<?php
namespace App\Services\Payment\DTOs;
class PaymentResult
{
    public function __construct(
        public bool $success,
        public ?string $paymentUrl = null,
        public ?string $message = null
    ) {
    }
}
