<?php

namespace App\Repository;

use App\Models\Role;
use App\Repository\Repository;
use Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;

class RoleRepository extends Repository
{
    /**
     * @var object
     */
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * fetch all Roles
     *
     * @return JsonResponse
     */
    public function getAllRoles(): JsonResponse
    {
        return Datatables::of(Role::query())
            ->addColumn('action', 'settings.role.partials.action')
            ->addIndexColumn()
            ->toJson();
    }
}
