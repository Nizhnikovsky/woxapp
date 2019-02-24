<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 23.02.19
 * Time: 20:30
 */

namespace App\Infrastructure\Repository;

use App\Domain\Model\Notification\Notification;
use App\Domain\Model\Notification\NotificationRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

final class NotificationRepository implements NotificationRepositoryInterface
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
     * NotificationRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Notification::class);
    }
    /**
     * @param int $notificationId
     * @return Notification
     */
    public function findById(int $notificationId): ?Notification
    {
        return $this->objectRepository->find($notificationId);
    }
    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->objectRepository->findAll();
    }
    /**
     * @param Notification $notification
     */
    public function save(Notification $notification): void
    {
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
    /**
     * @param Notification $notification
     */
    public function delete(Notification $notification): void
    {
        $this->entityManager->remove($notification);
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

        $sql = 'SELECT * FROM notification p WHERE p.date_start < :date_now ORDER BY p.date_start ASC LIMIT '.$limit.' OFFSET '.$offset;
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

        $sql = 'SELECT * FROM notification p WHERE p.date_start > :date_now ORDER BY p.date_start ASC LIMIT '.$limit. ' OFFSET '.$offset;
        $stmt = $conn->prepare($sql);
        $stmt->execute(['date_now' => $date]);

        return $stmt->fetchAll();
    }

    public function search(string $needle, string $table): ?array
    {
        $conn = $this->entityManager->getConnection();
        $sql = 'SELECT * FROM notification p WHERE p.'.$table.' = '.'"'.$needle.'"';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


}
