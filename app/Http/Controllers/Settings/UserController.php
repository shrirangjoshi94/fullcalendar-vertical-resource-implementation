<?php
namespace App\Http\Controllers\Settings;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    /**
     * RatingService object
     *
     * @var UserService
     */
    private $userService;

    /**
     * To initialize objects/variables
     *
     * @param UserService $userService
     * 
     * @return void
     */
    public function __construct(UserService $userService) 
    {
        $this->userService = $userService;
    }

    public function getLoggedInUser()
    {
        return auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('settings.user.index');
    }

    /**
     * Display a listing of the resource.
     * 
     * @return JsonResponse
     */
    public function getAllUsers():JsonResponse
    {
        return $this->userService->getAllUsers();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @return int
     */
    public function update(Request $request): int
    {
        return $this->userService->updateUser($request->userId, $request->roleId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
