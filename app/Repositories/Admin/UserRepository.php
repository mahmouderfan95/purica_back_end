<?php
namespace App\Repositories\Admin;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function getCountOfCustomers()
    {
        return $this->getModel()::query()->count();
    }
    public function getCustomersReports($request)
    {
        $from = $request->from_date;
        $to = $request->to_date;
        return $this->getModel()::query()
            ->whereHas('orders')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone'
            )
            ->withCount(['orders as total_orders' => function ($q) use ($from, $to) {
                $q->when($from && $to, fn($q) =>
                $q->whereBetween('created_at', [$from, $to])
                );
            }])

            ->addSelect([
                'peak_period' => Order::query()
                    ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period")
                    ->whereColumn('orders.user_id', 'users.id')
                    ->when($from && $to, fn($q) =>
                    $q->whereBetween('created_at', [$from, $to])
                    )
                    ->groupBy('period')
                    ->orderByRaw('COUNT(*) DESC')
                    ->limit(1)
            ])

            ->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function getUsersStatusReport(): array
    {
        return $this->getModel()::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }
    private function getModel() : User
    {
        return new User();
    }
}
