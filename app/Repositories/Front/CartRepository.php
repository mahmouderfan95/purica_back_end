<?php
namespace App\Repositories\Front;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;

class CartRepository
{
    public function mergeGuestCart(User $user, ?string $guestToken): void
    {
        if (!$guestToken) {
            return;
        }

        $guestCart = Cart::query()->where('guest_token', $guestToken)
            ->with('items')
            ->first();

        if (!$guestCart) {
            return;
        }

        $userCart = Cart::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'guest_token' => null,
                'total' => 0,
            ]
        );

        foreach ($guestCart->items as $item) {

            $existing = CartItem::query()
                ->where('cart_id', $userCart->id)
                ->where('product_id', $item->product_id)
                ->where('selected_options', $item->selected_options)
                ->first();

            if ($existing) {

                $existing->quantity += $item->quantity;
                $existing->total = $existing->quantity * $existing->price;
                $existing->save();

            } else {

                $item->cart_id = $userCart->id;
                $item->save();
            }
        }

        $userCart->update([
            'total' => $userCart->items()->sum('total'),
        ]);

        $guestCart->delete();
    }
    public function getCart(?User $user, ?string $guestToken): Cart
    {
        return Cart::query()->firstOrCreate(
            $user
                ? ['user_id' => $user->id]
                : ['guest_token' => $guestToken],
            [
                'user_id' => $user?->id,
                'guest_token' => $user ? null : $guestToken,
                'total' => 0,
            ]
        );
    }
    public function getCartByUserId($id)
    {
        return  $this->getModel()::query()->with('items.product')
            ->where('user_id', $id)
            ->first();
    }
    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();

        $cart->update([
            'total' => 0,
        ]);
    }
    private function getModel() : Cart
    {
        return new Cart();
    }
}
