<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 23.02.19
 * Time: 20:28
 */

namespace App\Domain\Model\Notification;

/**
 * Interface NotificationRepositoryInterface
 * @package App\Domain\Model\Notification
 */
interface NotificationRepositoryInterface
{
    /**
     * @param int $notificationId
     * @return Notification
     */
    public function findById(int $notificationId): ?Notification;
    /**
     * @return array
     */
    public function findAll(): array;
    /**
     * @param Notification $notification
     */
    public function save(Notification $notification): void;
    /**
     * @param Notification $notification
     */
    public function delete(Notification $notification): void;

    /**
     * @param \DateTime $dateTime
     * @param int $page
     * @return array
     */
    public function getLatest(\DateTime $dateTime, int $page):array ;

    /**
     * @param \DateTime $dateTime
     * @param int $page
     * @return array
     */
    public function getInFuture(\DateTime $dateTime,int $page):array ;

    /**
     * @param string $needle
     * @param string $table
     * @return array|null
     */
    public function search(string $needle, string $table):?array;

}
