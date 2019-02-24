<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 23.02.19
 * Time: 18:24
 */

namespace App\Infrastructure\Repository;

use App\Domain\Model\User\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Model\User\UserRepositoryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Class UserRepository
 * @package App\Infrastructure\Repository
 */
final class UserRepository implements UserRepositoryInterface,UserLoaderInterface
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
        $this->objectRepository = $this->entityManager->getRepository(User::class);
    }
    /**
     * @param int $userId
     * @return User
     */
    public function findById(int $userId): ?User
    {
        return $this->objectRepository->find($userId);
    }

    public function loadUserByUsername($username): ?User
    {
        return $this->objectRepository->findOneBy(array('username' => $username));
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->objectRepository->findAll();
    }
    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
    /**
     * @param User $user
     */
    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

}
