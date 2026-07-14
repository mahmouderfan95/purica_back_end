<?php
namespace App\Repositories\Front;
use App\Models\Product;
use App\Models\User;
use App\Models\UserProductWhitelists;
use Illuminate\Support\Facades\DB;

class FavoriteRepository
{
    public function mergeGuestFavorites(User $user, ?string $guestToken): void
    {
        if (empty($guestToken)) {
            return;
        }

        DB::transaction(function () use ($user, $guestToken) {

            $favorites = $this->getModel()::query()->where('guest_token', $guestToken)->get();

            foreach ($favorites as $favorite) {

                $this->getModel()::query()->firstOrCreate([
                    'user_id'    => $user->id,
                    'product_id' => $favorite->product_id,
                ]);

            }

            $this->getModel()::query()->where('guest_token', $guestToken)->delete();
        });
    }
    public function getFavorites(?User $user, ?string $guestToken)
    {
        $productIds = $this->getModel()::query()
            ->when(
                $user,
                fn ($q) => $q->where('user_id', $user->id),
                fn ($q) => $q->where('guest_token', $guestToken)
            )
            ->pluck('product_id');

        return Product::query()
            ->whereIn('id', $productIds)
            ->withExists([
                'favorites as is_fav' => function ($q) use ($user, $guestToken) {
                    $q->when(
                        $user,
                        fn ($q) => $q->where('user_id', $user->id),
                        fn ($q) => $q->where('guest_token', $guestToken)
                    );
                }
            ])
            ->with([
                'category',
                'attributeOptions',
                'media:id,product_id,original_path,file_name,status',
                'variants',
            ])
            ->get();
    }
    public function toggleFavorite(?User $user, ?string $guestToken, int $productId): bool
    {
        $favorite = $this->getModel()::query()
            ->where('product_id', $productId)
            ->when(
                $user,
                fn ($q) => $q->where('user_id', $user->id),
                fn ($q) => $q->where('guest_token', $guestToken)
            )
            ->first();

        if ($favorite) {
            $favorite->delete();
            return false;
        }

        $this->getModel()::query()->create([
            'user_id'      => $user?->id,
            'guest_token'  => $user ? null : $guestToken,
            'product_id'   => $productId,
        ]);

        return true;
    }
    private function getModel() : UserProductWhitelists
    {
        return new UserProductWhitelists();
    }
}
