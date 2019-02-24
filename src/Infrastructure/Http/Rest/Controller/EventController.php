<?php

namespace App\Infrastructure\Http\Rest\Controller;

use App\Application\Service\EventService;
use App\Application\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class EventController extends FOSRestController
{

    /**
     * @var EventService
     */
    private $eventService;


    private $userService;

    /**
     * EventController constructor.
     * @param EventService $eventService
     * @param UserService $userService
     */
    public function __construct(EventService $eventService, UserService $userService)
    {
        $this->eventService = $eventService;
        $this->userService = $userService;
    }

    /**
     * @Rest\Post("/event/create")
     * @param Request $request
     * @return View
     */
    public function createEvent(Request $request):View
    {
        $name = $request->get('name');
        $user_id = $request->get('user_id');
        $date_start = DateTime::createFromFormat('Y-m-d H:i:s', $request->get('date_start'));
        $date_end = DateTime::createFromFormat('Y-m-d H:i:s', $request->get('date_end'));
        $user = $this->userService->getUser($user_id);
        $event = $this->eventService->addEvent($user, $name, $date_start, $date_end);

        return View::create(["event" => $event], Response::HTTP_CREATED);
    }

    /**
     * @Rest\Put("/event/{eventId}")
     * @param  $eventId
     * @param Request $request
     * @return View
     */
    public function updateNotification($eventId,Request $request):View
    {
        $name = $request->get('name');
        $user_id = $request->get('user_id');
        $date_start = DateTime::createFromFormat('Y-m-d H:i:s', $request->get('date_start'));
        $date_end = DateTime::createFromFormat('Y-m-d H:i:s', $request->get('date_end'));
        $user = $this->userService->getUser($user_id);
        $event = $this->eventService->updateEvent($eventId,$user, $name, $date_start, $date_end);
        return View::create(["event" => $event], Response::HTTP_CREATED);
    }

    /**
     * @Rest\Delete("/event/{eventId}")
     * @param  $eventId
     * @param Request $request
     * @return View
     */
    public function deleteNotification(int $eventId):View
    {
        $this->eventService->deleteEvent($eventId);
        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $page
     * @Rest\Get("/event/latest/{page}")
     * @return View
     */
    public function getLatestEvents(int $page):View
    {
        $date = new DateTime('now');
        $event = $this->eventService->getLatestEvents($date,$page);
        return View::create($event, Response::HTTP_OK);
    }

    /**
     * @param int $page
     * @Rest\Get("/event/future/{page}")
     * @return View
     */
    public function getFutureEvents(int $page):View
    {
        $date = new DateTime('now');
        $event = $this->eventService->getFutureEvents($date,$page);
        return View::create($event, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @Rest\Post("/event/search")
     * @return View
     */
    public function searchEvent(Request $request):View
    {
        $needle = $request->get('needle');
        $table = $request->get('table');
        $event = $this->eventService->searchEvent($needle,$table);
        return View::create($event, Response::HTTP_OK);
    }

}
