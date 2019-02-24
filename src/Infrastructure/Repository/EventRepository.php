<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 23.02.19
 * Time: 20:30
 */

namespace App\Infrastructure\Repository;


use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\EventRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

/**
 * Class EventRepository
 * @package App\Repository
 */
final class EventRepository implements EventRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    /**
     * ArticleRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Event::class);
    }
    /**
     * @param int $eventId
     * @return Event
     */
    public function findById(int $eventId): ?Event
    {
        return $this->objectRepository->find($eventId);
    }
    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->objectRepository->findAll();
    }
    /**
     * @param Event $event
     */
    public function save(Event $event): void
    {
        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }
    /**
     * @param Event $event
     */
    public function delete(Event $event): void
    {
        $this->entityManager->remove($event);
        $this->entityManager->flush();
    }

    /**
     * @param DateTime $date
     * @param int $page
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getLatest(DateTime $date, int $page): array
    {
        $date = $date->format('Y-m-d H:i:s');
        $limit = 3;
        $offset = $limit * ($page -1);
        $conn = $this->entityManager->getConnection();

        $sql = 'SELECT * FROM event p WHERE p.date_start < :date_now ORDER BY p.date_start ASC LIMIT '.$limit.' OFFSET '.$offset;
        $stmt = $conn->prepare($sql);
        $stmt->execute(['date_now' => $date]);

        return $stmt->fetchAll();
    }

    /**
     * @param DateTime $date
     * @param int $page
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getInFuture(DateTime $date, int $page): array
    {
        $date = $date->format('Y-m-d H:i:s');
        $limit = 3;
        $offset = $limit * ($page -1);

        $conn = $this->entityManager->getConnection();

        $sql = 'SELECT * FROM event p WHERE p.date_start > :date_now ORDER BY p.date_start ASC LIMIT '.$limit. ' OFFSET '.$offset;
        $stmt = $conn->prepare($sql);
        $stmt->execute(['date_now' => $date]);

        return $stmt->fetchAll();
    }

    /**
     * @param string $needle
     * @param string $table
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function search(string $needle, string $table): ?array
    {
        $conn = $this->entityManager->getConnection();
        $sql = 'SELECT * FROM event p WHERE p.'.$table.' = '.'"'.$needle.'"';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}
