<?php
namespace App\Enums;
enum OrderStatusEnum: string
{
    const PENDING = "pending";
    const PROCESSING  = "processing";
    const COMPLETED = "completed";
    const SHIPPED = "shipped";
    const  DELIVERED =  "delivered";
    const CANCELED = "cancelled";
    const  PARTIALLY_CANCELLED = "partially_cancelled";
    const RETURNED = "returned";
    const REFUNDED = "refunded";
}
