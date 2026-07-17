<?php
namespace App\Repositories\Admin;
use App\Models\Coupon;

class CouponRepository
{
    public function getCountOfCoupons()
    {
        return $this->getModel()::query()->count();
    }
    public function getCountOfActiveCoupons()
    {
        return $this->getModel()::query()->Active()->count();
    }
    public function getCountOfInActiveCoupons()
    {
        return $this->getModel()::query()->where('status','inactive')->count();
    }
    public function getCountOfExpiredCoupons()
    {
        return $this->getModel()::query()
            ->where(function ($q) {
                $q->whereDate('end_date', '<', now())
                    ->orWhereColumn('used_count', '>=', 'usage_limit');
            })
            ->count();
    }
    public function getCoupons($request)
    {
        $allowedSorts = [
            'id',
            'usage_limit',
            'used_count',
            'status',
            'created_at',
        ];

        $sortBy  = $request->get('sort_by', 'id');
        $sortDir = $request->get('sort_dir', 'desc');

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }
        return $this->getModel()::query()
            ->select('id','code','token','value','min_order_total','usage_limit','used_count','status','start_date','end_date')
            ->orderBy($sortBy, $sortDir)
            ->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function create($request)
    {
        return $this->getModel()::query()->create($request);
    }
    public function getModelById($id)
    {
        return $this->getModel()::query()
            ->where('id', $id)
            ->first();
    }
    public function exists(array $conditions): bool
    {
        return $this->getModel()::query()
            ->where($conditions)
            ->exists();
    }
    private function getModel() : Coupon
    {
        return new Coupon();
    }
}
