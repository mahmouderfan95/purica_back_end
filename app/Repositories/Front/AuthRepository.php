<?php
namespace App\Repositories\Front;
use App\Enums\GeneralStatusEnum;
use App\Models\User;

class AuthRepository
{
    public function getModelByEmail($email)
    {
        return $this->getModel()::query()->where('email', $email)
            ->whereStatus(GeneralStatusEnum::ACTIVE)
            ->first();
    }
    private function getModel() : User
    {
        return new User();
    }
}
