<?php
namespace App\Repository;

use App\Models\User;
use Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;

class UserRepository extends Repository
{
    /**
     * @var object
     */
    protected $model;

    /**
     * To initialize objects/variables
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * fetch all users
     *
     * @return JsonResponse
     */
    public function getAllUsers(): JsonResponse
    {
        return Datatables::of($this->model->with('role'))
        ->addColumn('action', '<div class="row pl-3"><a href="#" class="btn btn-lg editClass"><i class="fa fa-edit"></i></a></div>')
        ->addIndexColumn()
        ->toJson();
    }

}
