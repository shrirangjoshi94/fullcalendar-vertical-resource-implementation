<?php

namespace App\Http\Controllers\Settings;

use App\Models\Role;
use Illuminate\View\View;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    /**
     * Service object
     *
     * @var RoleService
     */
    private $roleService;

    /**
     * To initialize objects/variables
     *
     * @param RoleService $roleService
     */
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('settings.role.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function getAllRoles(): JsonResponse
    {
        return $this->roleService->getAllRoles();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RoleRequest  $request
     * @return RedirectResponse
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        $this->roleService->store($request->all());

        return redirect('/roles')->with('message', 'Role is Created!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     * @return int
     */
    public function update(RoleRequest $request): int
    {
        return $this->roleService->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role  $role
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        if(!$this->roleService->delete($role)){
            return redirect('/roles')->with('error', "Role can't be deleted, As it assigned to some users");
        }

        return redirect('/roles')->with('message', 'Role is deleted!');
    }
}
