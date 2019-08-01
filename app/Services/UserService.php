<?php
namespace App\Services;

use Illuminate\Http\JsonResponse;
use App\Repository\UserRepository;
use App\Repository\ConsumeExternalApiRepository;

class UserService
{
    /**
     * UserRepository object
     *
     * @var object
     */
    private $userRepository;

    /**
     * ConsumeExternalApiRepository object
     *
     * @var object
     */
    private $consumeExternalApiRepository;

    /**
     * @param UserRepository $userRepository
     * @param ConsumeExternalApiRepository $consumeExternalApiRepository
     * @return void
     */
    public function __construct(
        UserRepository $userRepository, 
        ConsumeExternalApiRepository $consumeExternalApiRepository
    ) {
        $this->userRepository = $userRepository;
        $this->consumeExternalApiRepository = $consumeExternalApiRepository;
    }

    /**
     * @param array $userDetails
     * @return string
     */
    public function authenticateUsers(array $userDetails) : string
    {
        $method = 'POST';
        $authenticateUserUrl = config('pit-url')['pit-login-api'];
        $authUser = $this->consumeExternalApiRepository->callExternalApi($method, $authenticateUserUrl, $userDetails);;
        $userId = 0;
        if($authUser->error == 'false') {
            $attributes = [];
            $attributes['email'] = $authUser->data->contact_email;
            $attributes['username'] = $userDetails['username'];
            $searchForAuthenticatedUser = $this->userRepository->where($attributes);
            if(count($searchForAuthenticatedUser) == 0) {
                $insertUserInDB = $this->userRepository->create($attributes);
                $userId = $insertUserInDB->id;
            } else {
                $userId = $searchForAuthenticatedUser[0]->id;
            }
        }

        return $userId;
    }

    /**
     * Get all user's JsonResponse
     * 
     * @return JsonResponse
     */
    public function getAllUsers(): JsonResponse
    {
        return $this->userRepository->getAllUsers();
    }

    /**
     * Get all user's collection
     * 
     * @param int $userId
     * @param int $roleId
     * 
     * @return int
     */
    public function updateUser($userId, $roleId): int
    {
        return $this->userRepository->update($userId, [
            'role_id' => $roleId
        ]);
    }
}