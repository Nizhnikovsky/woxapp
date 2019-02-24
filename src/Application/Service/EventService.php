<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 23.02.19
 * Time: 21:59
 */

namespace App\Application\Service;

use Doctrine\ORM\EntityNotFoundException;
use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\EventRepositoryInterface;
use App\Domain\Model\User\User;

final class EventService
{
    /**
     * @var EventRepositoryInterface
     */
    private $eventRepository;
    /**
     * UserService constructor.
     * @param EventRepositoryInterface $eventRepository
     */
    public function __construct(EventRepositoryInterface $eventRepository){
        $this->eventRepository = $eventRepository;
    }
    /**
     * @param int $eventId
     * @return Event
     * @throws EntityNotFoundException
     */
    public function getEvent(int $eventId): Event
    {
        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            throw new EntityNotFoundException('Event with id '.$eventId.' does not exist!');
        }
        return $event;
    }
    /**
     * @return array|null
     */
    public function getAllEvents(): ?array
    {
        return $this->eventRepository->findAll();
    }

    /**
     * @param User $user_id
     * @param string $name
     * @param \DateTime date_start
     * @param \DateTime date_end
     * @return Event
     * @throws \Exception
     */
    public function addEvent(User $user_id, string $name,\DateTime $date_start,\DateTime $date_end): Event
    {
        $event = new Event();
        $event->setUserId($user_id);
        $event->setName($name);
        $event->setDateStart($date_start);
        $event->setDateEnd($date_end);
        $this->eventRepository->save($event);
        return $event;
    }
    /**
     * @param int $eventId
     * @param User $user_id
     * @param string $name
     * @param \DateTime date_start
     * @param \DateTime date_end
     * @return Event
     * @throws EntityNotFoundException
     */
    public function updateEvent(int $eventId, User $user_id, string $name,\DateTime $date_start,\DateTime $date_end): Event
    {
        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            throw new EntityNotFoundException('Event with id '.$eventId.' does not exist!');
        }
        $event->setUserId($user_id);
        $event->setName($name);
        $event->setDateStart($date_start);
        $event->setDateEnd($date_end);
        $this->eventRepository->save($event);
        return $event;
    }

    /**
     * @param int $eventId
     * @throws EntityNotFoundException
     */
    public function deleteEvent(int $eventId): void
    {
        $event = $this->eventRepository->findById($eventId);
        if (!$event) {
            throw new EntityNotFoundException('Event with id '.$eventId.' does not exist!');
        }
        $this->eventRepository->delete($event);
    }

    /**
     * @param \DateTime $date
     * @param int $page
     * @return array|null
     */
    public function getLatestEvents(\DateTime $date,int $page): ?array
    {
        $events = $this->eventRepository->getLatest($date,$page);
        return $events;
    }

    /**
     * @param \DateTime $date
     * @param int $page
     * @return array|null
     */
    public function getFutureEvents(\DateTime $date, int $page): ?array
    {
        $events = $this->eventRepository->getInFuture($date, $page);
        return $events;
    }

    /**
     * @param string $needle
     * @param string $table
     * @return array|null
     */
    public function searchEvent(string $needle, string $table):?array
    {
        $events = $this->eventRepository->search($needle, $table);
        return $events;
    }

}
