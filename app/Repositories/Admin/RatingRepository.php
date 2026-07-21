<?php
namespace App\Repositories\Admin;
use App\Models\Rating;

class RatingRepository
{
    public function getReviews($request)
    {
        return $this->getModel()::query()
            ->with(['user','product'])
            ->select('id','user_id','product_id','rating','comment','created_at')
            ->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function getModelById($id)
    {
        return $this->getModel()::query()
            ->where('id', $id)
            ->first();
    }
    private function getModel() : Rating
    {
        return new Rating();
    }
}
