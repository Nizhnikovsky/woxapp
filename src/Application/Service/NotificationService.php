<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 23.02.19
 * Time: 20:45
 */

namespace App\Application\Service;

use Doctrine\ORM\EntityNotFoundException;
use App\Domain\Model\Notification\Notification;
use App\Domain\Model\Notification\NotificationRepositoryInterface;
use App\Domain\Model\User\User;

final class NotificationService
{
    /**
     * @var NotificationRepositoryInterface
     */
    private $notificationRepository;

    /**
     * NotificationService constructor.
     * @param NotificationRepositoryInterface $notificationRepository
     */
    public function __construct(NotificationRepositoryInterface $notificationRepository){
        $this->notificationRepository = $notificationRepository;
    }
    /**
     * @param int $notificationId
     * @return Notification
     * @throws EntityNotFoundException
     */
    public function getNotification(int $notificationId): Notification
    {
        $notification = $this->notificationRepository->findById($notificationId);
        if (!$notification) {
            throw new EntityNotFoundException('Notification with id '.$notificationId.' does not exist!');
        }
    }
    /**
     * @return array|null
     */
    public function getAllNotifications(): ?array
    {
        return $this->notificationRepository->findAll();
    }

    /**
     * @param User $user_id
     * @param string $name
     * @param \DateTime $date_start
     * @param \DateTime $date_end
     * @param string $description
     * @return Notification
     * @throws \ErrorException
     */
    public function addNotification(User $user_id, string $name,\DateTime $date_start,\DateTime $date_end, string $description): Notification
    {
        $notification = new Notification();
        $notification->setUserId($user_id);
        $notification->setName($name);
        $notification->setDateStart($date_start);
        $notification->setDateEnd($date_end);
        $notification->setDescription($description);
        try{
            $this->notificationRepository->save($notification);
            return $notification;
        }catch (\Exception $e)
        {
            throw new \ErrorException($e->getMessage());
        }

    }
    /**
     * @param int $notificationId
     * @param User $user_id
     * @param string $name
     * @param \DateTime date_start
     * @param \DateTime date_end
     * @param string $description
     * @return Notification
     * @throws EntityNotFoundException
     */
    public function updateNotification(int $notificationId, User $user_id, string $name,\DateTime $date_start,\DateTime $date_end, string $description): Notification
    {
        $notification = $this->notificationRepository->findById($notificationId);
        if (!$notification) {
            throw new EntityNotFoundException('Notification with id '.$notificationId.' does not exist!');
        }
        $notification->setUserId($user_id);
        $notification->setName($name);
        $notification->setDateStart($date_start);
        $notification->setDateEnd($date_end);
        $notification->setDescription($description);
        $this->notificationRepository->save($notification);
        return $notification;
    }

    /**
     * @param int $notificationId
     * @throws EntityNotFoundException
     */
    public function deleteNotification(int $notificationId): void
    {
        $notification = $this->notificationRepository->findById($notificationId);
        if (!$notification) {
            throw new EntityNotFoundException('Notification with id '.$notificationId.' does not exist!');
        }
        $this->notificationRepository->delete($notification);
    }

    /**
     * @param \DateTime $date
     * @param int $page
     * @return array|null
     */
    public function getLatestNotifications(\DateTime $date,int $page): ?array
    {
        $notifications = $this->notificationRepository->getLatest($date,$page);
        return $notifications;
    }

    /**
     * @param \DateTime $date
     * @param int $page
     * @return array|null
     */
    public function getFutureNotifications(\DateTime $date, int $page): ?array
    {
        $notifications = $this->notificationRepository->getInFuture($date, $page);
        return $notifications;
    }

    /**
     * @param string $needle
     * @param string $table
     * @return array|null
     */
    public function searchNotification(string $needle, string $table):?array
    {
        $notifications = $this->notificationRepository->search($needle, $table);
        return $notifications;
    }
}
