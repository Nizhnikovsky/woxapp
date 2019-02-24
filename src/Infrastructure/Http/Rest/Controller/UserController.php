<?php

namespace App\Infrastructure\Http\Rest\Controller;


use App\Application\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package App\Infrastructure\Http\Rest\Controller
 */
final class UserController extends FOSRestController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Retrieves an User resource
     * @Rest\Get("/user/{userId}")
     * @param int $userId
     * @return View
     */
    public function getSingleUser(int $userId): View
    {
        $user = $this->userService->getUser($userId);
        return View::create($user, Response::HTTP_OK);
    }

    /**
     * Retrieves a collection of Users resource
     * @Rest\Get("/users")
     * @return View
     */
    public function getUsers(): View
    {
        $users = $this->userService->getAllUsers();
        return View::create($users, Response::HTTP_OK);
    }

    /**
     * Replaces User resource
     * @Rest\Put("/user/{userId}")
     * @param int $userId
     * @param Request $request
     * @return View
     */
    public function putUser(int $userId, Request $request): View
    {
        $phone = (int)$request->get('phone');
        $status = (int)$request->get('status');
        $user = $this->userService->updateUser($userId, $request->get('first_name'), $request->get('last_name'),$phone,$status,$request->get('password'),$request->get('username'));
        return View::create($user, Response::HTTP_OK);
    }

}
