<?php
namespace App\Repositories\Admin;
use App\Enums\OrderStatusEnum;
use App\Models\Order;

class OrderRepository
{
    public function getAdminOrders(int $id) : array
    {
        $query = $this->getModel()::query()
            ->where('created_by', $id);

        return [
            'orders' => (clone $query)
                ->with(['items.product', 'createdBy'])
                ->paginate(PAGINATION_COUNT_ADMIN),

            'completed_count' => (clone $query)
                ->where('status', OrderStatusEnum::COMPLETED)
                ->count(),

            'cancelled_count' => (clone $query)
                ->where('status', OrderStatusEnum::CANCELED)
                ->count(),
        ];
    }
    public function getCountOfCompleteOrders()
    {
        return $this->getModel()::query()
            ->where('status',OrderStatusEnum::DELIVERED)
            ->count();
    }
    public function getCountOfCancelledOrders()
    {
        return $this->getModel()::query()
            ->where('status',OrderStatusEnum::CANCELED)
            ->count();
    }
    public function getCountOfRefundedOrders()
    {
        return $this->getModel()::query()
            ->where('status',OrderStatusEnum::REFUNDED)
            ->count();
    }
    public function getOrders($request)
    {
        $status = $request->input("status");
        $paymentType = $request->input("payment_type");
        $createdBy = $request->input("created_by");
        $admin = auth('adminApi')->user();
        $isSuperAdmin = $admin->hasRole('super-admin');
        $search = $request->input("search");
        return $this->getModel()::query()
            ->with(['items.product','user','country','city','shippingCompany','coupon',
                'createdBy' => function ($query) {
                    $query->withCount('orders');
                }])
            ->select('id','user_id','total','status','created_at',
                'payment_type','address','country_id','city_id',
                'shipping_company_id','shipping_cost','created_by','coupon_id','discount')
            ->when($status, function ($query, $status) {
                $query->where("status",$status);
            })
            ->when($paymentType, function ($query, $paymentType) {
                $query->where("payment_type",$paymentType);
            })
            ->when($createdBy, function ($query, $createdBy) {
                $query->where("created_by",$createdBy);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    if (is_numeric($search)) {
                        $q->where('id', $search);
                    }

                    $q->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });

                });
            })
            /* ===========================
                Role Based Visibility
             ============================ */
            ->when(!$isSuperAdmin, function ($query) use ($admin) {
                $query->where('created_by', $admin->id)
                    ->where('addition_type','from_admin');
            })
            ->orderByDesc('id')
            ->latest()
            ->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function getModelById($id)
    {
        return $this->getModel()::query()
            ->with(['items.product','user','country','city','shippingCompany','coupon'])
            ->where("id",$id)
            ->select('id','user_id','total','status','created_at',
                'payment_type','address','country_id','city_id',
                'shipping_company_id','shipping_cost','created_by','coupon_id','discount')
            ->first();
    }
    private function getModel() : Order
    {
        return new Order();
    }
}
