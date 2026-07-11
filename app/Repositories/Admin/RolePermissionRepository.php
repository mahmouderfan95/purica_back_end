<?php
namespace App\Repositories\Admin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionRepository
{
    public function getRoles($request)
    {
        $currentGuard = Auth::getDefaultDriver();
        // Default to search value
        $searchTerm = $request->input('search', null);
        // Build the base query
        $query = $this->getModel()->query();
        $query->with(['permissions']);
        // Apply searching
        if ($searchTerm) {
            $query->where("name", 'like', '%' . $searchTerm . '%');
        }
        // Retrieve paginated results
        return $query->where('guard_name', $currentGuard)
            ->where('name', '<>','super-admin')
            ->latest()
            ->paginate(PAGINATION_COUNT_ADMIN);
    }
    public function store($request)
    {
        $currentGuard = Auth::getDefaultDriver();
        $role = $this->getModel()->create([
            'name' => $request->name,
//            'display_name' => $request->display_name,
            'guard_name' => $currentGuard
        ]);
        if($role){
            $role->syncPermissions($request->permissions);
        }
        return $role;
    }
    public function update($request, $id)
    {
        $role = $this->getModelById($id);
        if (! $role)
            return false;
        $role->name = $request->name;
//        $role->display_name = $request->display_name;
        $role->syncPermissions($request->permissions);
        $role->save();
        return $role;
    }
    public function getModelById($id)
    {
        $currentGuard = Auth::getDefaultDriver();
        return $this->getModel()
            ->with(['permissions'])
            ->where('guard_name', $currentGuard)
            ->where('name', '<>','super-admin')
            ->where('id', $id)
            ->first();
    }
    public function getPermissions() : Collection
    {
        $currentGuard = Auth::getDefaultDriver();
        return Permission::query()->where('guard_name', $currentGuard)->get();
    }
    public function getModel() : Role
    {
        return new Role();
    }
}
