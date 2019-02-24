<?php

namespace App\Infrastructure\Http\Rest\Controller;

use App\Application\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Application\Service\RedisService;


class SecurityController extends FOSRestController
{

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTManager;

    /**
     * @var RedisService $redisService
     */
    private $redisService;


    /**
     * UserController constructor.
     * @param UserService $userService
     * @param JWTTokenManagerInterface $JWTManager
     * @param RedisService $redisService
     * @param RefreshTokenManagerInterface $JWTRefreshManager
     */
    public function __construct(UserService $userService, JWTTokenManagerInterface $JWTManager,RedisService $redisService)
    {
        $this->userService = $userService;
        $this->JWTManager = $JWTManager;
        $this->redisService = $redisService;
    }

    /**
     * @Rest\Post("/login")
     * @param Request $request
     * @return View
     */
    public function loginUser(Request $request): View
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $user = $this->userService->checkCredentials($username,$password);
        if ($user)
        {
            $token = $this->JWTManager->create($user);
            $this->redisService->deleteFromRedis($user->getId());
            $this->redisService->setToRedis($user->getId(),$token);
            return View::create(['token' => $token], Response::HTTP_CREATED);
        }
        return View::create(['message' => "User unauthorized"], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @Rest\Post("/register")
     * @param Request $request
     * @return View
     */
    public function register(Request $request): View
    {
        $phone = (int)$request->get('phone');
        $status = (int)$request->get('status');
        $user = $this->userService->addUser($request->get('first_name'), $request->get('last_name'), $phone, $status, $request->get('password'), $request->get('username'));
        $token = $this->JWTManager->create($user);
        return View::create(["user" => $user, "token" => $token], Response::HTTP_CREATED);
    }

    /**
     * Removes the User resource
     * @Rest\Delete("/user/{userId}")
     * @param int $userId
     * @return View
     */
    public function deleteUser(int $userId): View
    {
        $this->userService->deleteUser($userId);
        $this->redisService->deleteFromRedis($userId);
        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\Post("/password_reset")
     * @param Request $request
     * @return View
     */
    public function passwordReset(Request $request):View
    {
        $old_password = $request->get('old_password');
        $new_password = $request->get('new_password');
        $username = $request->get('username');

        $user = $this->userService->changePassword($username,$old_password,$new_password);
        if ($user)
        {
            return View::create([], Response::HTTP_NO_CONTENT);
        }
        return View::create([], Response::HTTP_NOT_FOUND);
    }
}
