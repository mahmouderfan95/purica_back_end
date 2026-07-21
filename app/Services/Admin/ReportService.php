<?php
namespace App\Services\Admin;
use App\Enums\UserStatusEnum;
use App\Http\Resources\Admin\Reports\AdminReportCollection;
use App\Http\Resources\Admin\Reports\CategoryChartResource;
use App\Http\Resources\Admin\Reports\CategorySalesChartResource;
use App\Http\Resources\Admin\Reports\CustomerReportResource;
use App\Http\Resources\Admin\Reports\ProductTopOrderedCollection;
use App\Http\Resources\Admin\Reports\ProductTopRatedCollection;
use App\Models\Admin;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\ProductRepository;
use App\Repositories\Admin\UserRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportService
{
    use ApiResponseAble;
    public function __construct(
        public ProductRepository $productRepository,
        public UserRepository $userRepository,
        public CategoryRepository $categoryRepository,
    ){}
    public function getAdminsReports($request) : JsonResponse
    {
        $from = $request->from_date;
        $to   = $request->to_date;

        $reports = Admin::query()
            ->withCount([
                'orders as orders_count' => function ($q) use ($from, $to) {
                    if ($from) $q->whereDate('created_at', '>=', $from);
                    if ($to) $q->whereDate('created_at', '<=', $to);
                }
            ])
            ->withSum([
                'orders as total_revenue' => function ($q) use ($from, $to) {
                    if ($from) $q->whereDate('created_at', '>=', $from);
                    if ($to) $q->whereDate('created_at', '<=', $to);
                }
            ], 'total')

            ->when($request->search, function ($q, $search) {
                $q->where('name', 'LIKE', "%{$search}%");
            })

            ->when($request->sort, function ($q, $sort) {
                $q->orderBy(
                    $sort === 'most_orders' ? 'orders_count' : 'orders_count',
                    $sort === 'most_orders' ? 'DESC' : 'ASC'
                );
            }, fn($q) => $q->orderBy('id', 'desc'))

            ->paginate(PAGINATION_COUNT_ADMIN);
        if($reports->isEmpty()){
            return $this->listResponse([]);
        }
        return $this->ApiSuccessResponse(AdminReportCollection::make($reports));
    }
    public function getProductsReports($request) : JsonResponse
    {
        try{
            $from  = $request->from_date;
            $to    = $request->to_date;
            $sort = $request->sort_by;
            $data = [];
            $data['top_ordered'] = ProductTopOrderedCollection::make($this->productRepository->getTopOrdered($from, $to,$sort));
            $data['most_returned'] = ProductTopOrderedCollection::make($this->productRepository->getMostReturned($from,$to));
            $data['top_rated'] = ProductTopRatedCollection::make($this->productRepository->getHighestRated());
            $data['lowest_rated'] = ProductTopRatedCollection::make($this->productRepository->getLowestRated());
            return $this->ApiSuccessResponse($data);
        }catch (\Exception $exception){
            Log::error('error of get products reports' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function getUsersReports($request) : JsonResponse
    {
        try{
            $from  = $request->from_date;
            $to    = $request->to_date;
            $customerReports = CustomerReportResource::collection($this->userRepository->getCustomersReports($request));
            return $this->ApiSuccessResponse($customerReports);
        }catch (\Exception $exception){
            Log::error('error of get users reports' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function categoriesProductCharts($request) : JsonResponse
    {
        try{
            $categories = $this->categoryRepository
                ->getCategoriesWithProductsCount();

            $totalProducts = $this->categoryRepository
                ->getTotalProductsCount();
            return $this->ApiSuccessResponse([
                'total_products' => $totalProducts,
                'categories' => CategoryChartResource::collection($categories),
            ], 'Categories products chart data');
        }catch (\Exception $exception)
        {
            Log::error('error of get categories with product chart reports' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function categoriesSalesRatio($request)
    {
        try{
            $data = $this->categoryRepository->categorySalesRatio();

            $totalSales = $data->sum('total_sales');

            return $this->ApiSuccessResponse([
                'total_sales' => $totalSales,
                'data' => CategorySalesChartResource::collection($data->map(function ($item) use ($totalSales) {
                    $item->percentage = $totalSales > 0
                        ? round(($item->total_sales / $totalSales) * 100, 2)
                        : 0;
                    return $item;
                })),
            ]);
        }catch (\Exception $exception)
        {
            Log::error('error of report of category sales ratio' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function usersStatusReport($request) : JsonResponse
    {
        try{
        $report = $this->userRepository->getUsersStatusReport();

        $data = [
            'total_customers' => array_sum($report),
            'data' => [
                [
                    'label' => 'نشطين',
                    'key'   => UserStatusEnum::ACTIVE,
                    'count' => $report[UserStatusEnum::ACTIVE] ?? 0,
                ],
                [
                    'label' => 'محظورين',
                    'key'   => UserStatusEnum::BLOCKED,
                    'count' => $report[UserStatusEnum::BLOCKED] ?? 0,
                ],
                [
                    'label' => 'غير نشطين',
                    'key'   => UserStatusEnum::INACTIVE,
                    'count' => $report[UserStatusEnum::INACTIVE] ?? 0,
                ],
            ],
        ];
        return $this->ApiSuccessResponse($data, 'Customers classification report');
        }catch (\Exception $exception)
            {
                Log::error('error of users status report' . $exception->getMessage());
                return $this->ApiErrorResponse([],'something went wrong');
            }
    }
}
