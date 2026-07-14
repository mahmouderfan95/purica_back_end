<?php
namespace App\Services\Front;
use App\Helper\NormalizeOptions;
use App\Http\Resources\Front\Cart\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Repositories\Front\CartRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CartService
{
    use ApiResponseAble,NormalizeOptions;
    public function __construct(public CartRepository $cartRepository){}
    public function index($request): JsonResponse
    {
        try {

            $user = auth('api')->user();
            $guestToken = $request->attributes->get('guest_token');

            $cart = Cart::with('items.product')
                ->when(
                    $user,
                    fn ($q) => $q->where('user_id', $user->id),
                    fn ($q) => $q->where('guest_token', $guestToken)
                )
                ->first();

            if (!$cart) {
                return $this->listResponse([]);
            }

            return $this->ApiSuccessResponse(
                CartResource::make($cart),
                'cart items'
            );

        } catch (\Exception $exception) {

            Log::error('error of get cart', [
                'user_id' => $user?->id,
                'guest_token' => $guestToken,
                'error' => $exception->getMessage(),
            ]);

            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            $user = auth('api')->user();

            // Step 1: Get or Create Cart
            $cart = $this->cartRepository->getCart(
                $user,
                request()->attributes->get('guest_token')
            );
            // (2) Normalize selected options to avoid duplication issues
            $normalizedOptions = $this->normalizeOptions($request->selected_options);
            $product = Product::query()->find($request->product_id);
            if (!$product) {
                return $this->ApiErrorResponse([], 'Product not found', 404);
            }
            $price = $product->price_after_discount > 0
                ? $product->price_after_discount
                : $product->price;
            // Step 2: Determine product variant price
            $variant = ProductVariants::query()
                ->where('product_id', $request->product_id)
                ->where('selected_options', $normalizedOptions)
                ->first();
            $variantPrice = $variant ? $variant->price : $price;
            $availableStock = $variant
                ? $variant->available_quantity
                : $product->available_quantity;
            $existingItem = CartItem::query()->where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->where('selected_options', $normalizedOptions)
                ->first();
            $requestedQuantity = $request->quantity;
            // stock validation
            if ($requestedQuantity > $availableStock) {
                return $this->ApiErrorResponse(
                    [],
                    'الكمية المطلوبة غير متاحة',
                    400
                );
            }
            if ($existingItem) {
                $existingItem->quantity += $request->quantity;
                $existingItem->total = $existingItem->quantity * $existingItem->price;
                $existingItem->save();
            } else {
                CartItem::query()->create([
                    'cart_id'          => $cart->id,
                    'product_id'       => $request->product_id,
                    'quantity'         => $request->quantity,
                    'price'            => $variantPrice,
                    'total'            => $variantPrice * $request->quantity,
                    'selected_options' => $normalizedOptions,
                ]);
            }
            $cart->update([
                'total' => $cart->items()->sum('total')
            ]);
            return $this->ApiSuccessResponse($cart->load('items.product'),'product add to cart successfully');
        }catch (\Exception $exception)
        {
            Log::error('error of add product to cart' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $item = CartItem::query()
                ->where('id', $id)
                ->whereHas('cart', fn($q) => $q->where('user_id', auth('api')->id()))
                ->first();
            if(!$item){
                return $this->notFoundResponse();
            }
            $cart = $item->cart;

            $item->delete();

            $cart->refresh();

            if ($cart->items()->count() === 0) {
                // Delete the entire cart if it's empty
                $cart->delete();
                return $this->ApiSuccessResponse([], 'item deleted and cart removed because it became empty');
            }

            // Otherwise update total
            $cart->update([
                'total' => $cart->items()->sum('total')
            ]);

            return $this->ApiSuccessResponse([], 'item deleted successfully');
        }catch (\Exception $exception){
            Log::error('error of delete product from cart' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
}
