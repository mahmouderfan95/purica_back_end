<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\Orders\OrderCollection;
use App\Http\Resources\Admin\Orders\OrderResource;
use App\Repositories\Admin\OrderRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderService
{
    use ApiResponseAble;
    public function __construct(
        public OrderRepository $orderRepository,
//        public ShippingCompanyRepository $shippingCompanyRepository,
//        public ShippingPriceRepository $shippingPriceRepository,
//        public UserRepository $userRepository,
    ){}
    public function index($request) : JsonResponse
    {
        try{
            $orders = $this->orderRepository->getOrders($request);
            if(!$orders)
                return $this->listResponse([]);
            return $this->ApiSuccessResponse(OrderCollection::make($orders),'orders retrieved successfully');
        }catch (\Exception $exception){
            Log::error('error of get orders' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function show($id) : JsonResponse
    {
        try{
            $order = $this->orderRepository->getModelById($id);
            if(!$order)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(OrderResource::make($order),'order retrieved successfully');
        }catch (\Exception $exception){
            Log::error('error of show order '.$exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $order = $this->orderRepository->getModelById($id);
            if (!$order)
                return $this->notFoundResponse();
            $order->delete();
            return $this->ApiSuccessResponse([],'Order deleted successfully');
        }catch (\Exception $exception){
            Log::error('error of destroy order ' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong',500);
        }
    }
}
