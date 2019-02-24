<?php

namespace App\Infrastructure\Http\Rest\Controller;

use App\Application\Service\NotificationService;
use App\Application\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DateTime;


class NotificationController extends FOSRestController
{

    /**
     * @var NotificationService
     */
    private $notificationService;


    private $userService;

    /**
     * NotificationController constructor.
     * @param NotificationService $notificationService
     * @param UserService $userService
     */
    public function __construct(NotificationService $notificationService, UserService $userService)
    {
        $this->notificationService = $notificationService;
        $this->userService = $userService;
    }

    /**
     * @Rest\Post("/notification/create")
     * @param Request $request
     * @return View
     */
    public function createNotification(Request $request):View
    {
        $name = $request->get('name');
        $user_id = $request->get('user_id');
        $date_start = DateTime::createFromFormat('Y-m-d H:i:s', $request->get('date_start'));
        $date_end = DateTime::createFromFormat('Y-m-d H:i:s', $request->get('date_end'));
        $description = $request->get('description');
        $user = $this->userService->getUser($user_id);
        $notification = $this->notificationService->addNotification($user, $name, $date_start, $date_end, $description);

        return View::create(["notification" => $notification], Response::HTTP_CREATED);
    }

    /**
     * @Rest\Put("/notification/{notificationId}")
     * @param  $notificationId
     * @param Request $request
     * @return View
     */
    public function updateNotification($notificationId,Request $request):View
    {
        $name = $request->get('name');
        $user_id = $request->get('user_id');
        $date_start = DateTime::createFromFormat('Y-m-d H:i:s', $request->get('date_start'));
        $date_end = DateTime::createFromFormat('Y-m-d H:i:s', $request->get('date_end'));
        $description = $request->get('description');
        $user = $this->userService->getUser($user_id);
        $notification = $this->notificationService->updateNotification($notificationId,$user, $name, $date_start, $date_end, $description);
        return View::create(["notification" => $notification], Response::HTTP_CREATED);
    }

    /**
     * @Rest\Delete("/notification/{notificationId}")
     * @param  $notificationId
     * @param Request $request
     * @return View
     */
    public function deleteNotification(int $notificationId):View
    {
        $this->notificationService->deleteNotification($notificationId);
        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $page
     * @Rest\Get("/notifications/latest/{page}")
     * @return View
     */
    public function getLatestNotifications(int $page):View
    {
        $date = new DateTime('now');
        $notifications = $this->notificationService->getLatestNotifications($date,$page);
        return View::create($notifications, Response::HTTP_OK);
    }

    /**
     * @param int $page
     * @Rest\Get("/notifications/future/{page}")
     * @return View
     */
    public function getFutureNotifications(int $page):View
    {
        $date = new DateTime('now');
        $notifications = $this->notificationService->getFutureNotifications($date,$page);
        return View::create($notifications, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @Rest\Post("/notification/search")
     * @return View
     */
    public function searchNotification(Request $request):View
    {
        $needle = $request->get('needle');
        $table = $request->get('table');
        $notifications = $this->notificationService->searchNotification($needle,$table);
        return View::create($notifications, Response::HTTP_OK);
    }

}
