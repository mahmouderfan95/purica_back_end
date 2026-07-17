<?php
namespace App\Services\Payment\Contracts;
use App\Models\Cart;
use App\Models\Order;
use App\Services\Payment\DTOs\PaymentResult;

interface PaymentStrategyInterface
{
    public function pay(Cart $cart ,Order $order): PaymentResult;
}
