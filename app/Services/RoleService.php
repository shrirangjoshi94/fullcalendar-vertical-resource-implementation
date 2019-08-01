<?php
namespace App\Services;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Repository\RoleRepository;

class RoleService
{
    /**
     * Service object
     *
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * To initialize objects/variables
     *
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository) 
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get all roles 
     * 
     * @return JsonResponse
     */
    public function getAllRoles(): JsonResponse
    {
        return $this->roleRepository->getAllRoles();
    }

    /**
     * Save Role
     * 
     * @param Array 
     * @return Role
     */
    public function store(Array $data): Role
    {
        return $this->roleRepository->create([
            'display_name' => $data['roleName'],
            'slug' => str_slug($data['roleName'])
        ]);
    }
    
    /**
     * Update Role
     * 
     * @param Array 
     * @return int
     */
    public function update(Array $data): int
    {
        return $this->roleRepository->update($data['roleId'],[
            'display_name' => $data['roleName'],
            'slug' => str_slug($data['roleName'])
        ]);
    }

    /**
     * Delete Role
     * 
     * @param Role 
     * @return bool
     */
    public function delete(Role $role): bool
    {
        if($role->users->isEmpty()){ // delete record only if the role is not assigned to any user
            return $this->roleRepository->destroy($role->id);
        }
        
        return false;
    }
}
