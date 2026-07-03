<?php
namespace App\Repositories\Admin;
use App\Models\Admin;

class AuthRepository
{
    public function getModelByEmail($email)
    {
        return $this->getModel()::query()->where("email", $email)->first();
    }
    private function getModel() : Admin
    {
        return new Admin();
    }
}
