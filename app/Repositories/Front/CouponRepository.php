<?php
namespace App\Repositories\Front;
use App\Models\Coupon;

class CouponRepository
{
    public function findValidCoupon($code)
    {
        return $this->getModel()::query()
            ->where('code', $code)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
    }
    public function getModelByToken($token)
    {
        return $this->getModel()::query()
            ->where('token', $token)
            ->first();
    }
    private function getModelById($id)
    {
        return $this->getModel()::query()
            ->where('id', $id)
            ->lockForUpdate()
            ->first();
    }
    public function validateCoupon(?string $couponCode, float $cartTotal): array
    {
        if (empty($couponCode)) {
            return [
                'coupon' => null,
                'discount' => 0,
            ];
        }

        $coupon = $this->findValidCoupon($couponCode);

        if (!$coupon) {
            throw new \Exception('الكوبون منتهى الصلاحية');
        }

        if (
            $coupon->usage_limit !== null &&
            $coupon->used_count >= $coupon->usage_limit
        ) {
            throw new \Exception('تم تجاوز الحد الأقصى لاستخدام الكوبون');
        }

        if (
            $coupon->min_order_total &&
            $cartTotal < $coupon->min_order_total
        ) {
            throw new \Exception('إجمالى الطلب أقل من الحد الأدنى المطلوب لاستخدام الكوبون');
        }

        return [
            'coupon' => $coupon,
            'discount' => $coupon->value,
        ];
    }
    public function updateCouponUsage(?Coupon $coupon): void
    {
        if (!$coupon) {
            return;
        }

        $coupon = $this->getModelById($coupon->id);

        if (!$coupon) {
            throw new \RuntimeException('Coupon not found.');
        }

        if (
            $coupon->usage_limit !== null &&
            $coupon->used_count >= $coupon->usage_limit
        ) {
            throw new \RuntimeException('Coupon usage limit exceeded.');
        }

        $coupon->increment('used_count');
    }
    private function getModel() : Coupon
    {
        return new Coupon();
    }
}
