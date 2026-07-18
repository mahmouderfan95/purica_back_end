<?php
namespace App\Repositories\Front;
use App\Models\Rating;

class RatingRepository
{
    public function getRatings()
    {
        return $this->getModel()::query()->with(['user','product'])
            ->select('id','user_id','product_id','rating','comment','created_at')
            ->orderByDesc('id')
            ->take(4)
            ->get();
    }
    public function createRating($data)
    {
        $user = auth('api')->user();
        return $this->getModel()::query()->updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $data['product_id']],
            ['rating' => $data['rating'], 'comment' => $data['comment'] ?? null]
        );
    }
    private function getModel() :Rating
    {
        return new Rating();
    }
}
