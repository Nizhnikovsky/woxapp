<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 23.02.19
 * Time: 18:16
 */

namespace App\Domain\Model\User;


/**
 * Interface UserRepositoryInterface
 * @package App\Entity\User\User
 */
interface UserRepositoryInterface
{

    /**
     * @param int $userId
     * @return User
     */
    public function findById(int $userId): ?User;
    /**
     * @return array
     */
    public function findAll(): array;
    /**
     * @param User $user
     */
    public function save(User $user): void;
    /**
     * @param User $user
     */
    public function delete(User $user): void;

    /**
     * @param $username
     * @return User|null
     */
    public function loadUserByUsername($username) :?User;


}
