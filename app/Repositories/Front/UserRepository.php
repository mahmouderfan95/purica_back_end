<?php
namespace App\Repositories\Front;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository
{
    public function syncCustomerInformation(User $user, Request $request): void
    {
        $phoneExists = $this->getModel()::query()
            ->where('phone', $request->phone)
            ->whereKeyNot($user->id)
            ->exists();

        if ($phoneExists) {
            return;
        }

        $user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);
    }
    private function getModel() : User
    {
        return new User();
    }
}
