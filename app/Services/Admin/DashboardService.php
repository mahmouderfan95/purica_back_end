<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\Products\ProductMostOrderdResource;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\CouponRepository;
use App\Repositories\Admin\OrderRepository;
use App\Repositories\Admin\ProductRepository;
use App\Repositories\Admin\UserRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    use ApiResponseAble;
    public function __construct(
        public ProductRepository $productRepository,
        public OrderRepository $orderRepository,
        public CategoryRepository $categoryRepository,
        public CouponRepository $couponRepository,
        public UserRepository $userRepository,
    ){}
    public function statistics($request) : JsonResponse
    {
        try{
            $data = [];
            $data['count_of_product'] = $this->productRepository->getCountOfProducts();
            $data['count_of_order_completed'] = $this->orderRepository->getCountOfCompleteOrders();
            $data['count_of_order_canceled'] = $this->orderRepository->getCountOfCancelledOrders();
            $data['count_of_order_returned'] = $this->orderRepository->getCountOfRefundedOrders();
            $data['count_of_customer'] = $this->userRepository->getCountOfCustomers();
            $data['count_of_category'] = $this->categoryRepository->getCountOfCategories();
            $data['count_of_coupons'] = $this->couponRepository->getCountOfCoupons();
            $data['count_of_active_coupons'] = $this->couponRepository->getCountOfActiveCoupons();
            $data['count_of_inactive_coupons'] = $this->couponRepository->getCountOfInactiveCoupons();
            $data['count_of_expired_coupons'] = $this->couponRepository->getCountOfExpiredCoupons();
            $data['product_most_ordered'] = ProductMostOrderdResource::collection($this->productRepository->getMostOrderd($request));
            $data['product_least_ordered'] = ProductMostOrderdResource::collection($this->productRepository->getLeastOrdered($request));
            return $this->ApiSuccessResponse($data,'dashboard data');
        }catch (\Exception $exception)
        {
            Log::error('error of get dashboard data' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
}
