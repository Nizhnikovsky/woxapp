<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 23.02.19
 * Time: 20:27
 */

namespace App\Domain\Model\Event;

/**
 * Interface EventRepositoryInterface
 * @package App\Domain\Model\Event
 */
interface EventRepositoryInterface
{
    /**
     * @param int $eventId
     * @return Event
     */
    public function findById(int $eventId): ?Event;
    /**
     * @return array
     */
    public function findAll(): array;
    /**
     * @param Event $event
     */
    public function save(Event $event): void;
    /**
     * @param Event $event
     */
    public function delete(Event $event): void;

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
