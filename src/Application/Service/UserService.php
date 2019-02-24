<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 23.02.19
 * Time: 18:21
 */

namespace App\Application\Service;

use Doctrine\ORM\EntityNotFoundException;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    private $password_encoder;
    /**
     * UserService constructor.
     * @param UserRepositoryInterface $userRepository
     * @param UserPasswordEncoderInterface $password_encoder
     */
    public function __construct(UserRepositoryInterface $userRepository, UserPasswordEncoderInterface $password_encoder){
        $this->userRepository = $userRepository;
        $this->password_encoder = $password_encoder;
    }
    /**
     * @param int $userId
     * @return User
     * @throws EntityNotFoundException
     */
    public function getUser(int $userId): User
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
        }
        return $user;
    }

    /**
     * @param string $username
     * @param string $password
     * @return User|bool|null
     */
    public function checkCredentials(string $username, string $password)
    {
        $user = $this->userRepository->loadUserByUsername($username);
        if ($user && $this->password_encoder->isPasswordValid($user,$password))
        {
            return $user;
        }
        return false;

    }
    /**
     * @param string $username
     * @return User
     * @throws EntityNotFoundException
     */
    public function getUserByName(string $username): User
    {
        $user = $this->userRepository->loadUserByUsername($username);
        if (!$user) {
            throw new EntityNotFoundException('User with username '.$username.' does not exist!');
        }
        return $user;
    }

    /**
     * @return array|null
     */
    public function getAllUsers(): ?array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param string $first_name
     * @param string $last_name
     * @param int $phone
     * @param int $status
     * @param string $password
     * @param string $username
     * @return User
     * @throws \Exception
     */
    public function addUser(string $first_name, string $last_name,int $phone, int $status,string $password, string $username): User
    {
        $user = new User();
        $user->setFirstName($first_name);
        $user->setLastName($last_name);
        $user->setPhone($phone);
        $user->setStatus($status);
        $user->setUsername($username);
        $user->setCreatedAt(new \DateTime('now'));
        $user->setPassword($this->password_encoder->encodePassword($user, $password));
        $this->userRepository->save($user);
        return $user;
    }
    /**
     * @param int $userId
     * @param string $first_name
     * @param string $last_name
     * @param int $phone
     * @param int $status
     * @param string $password
     * @param string $username
     * @return User
     * @throws EntityNotFoundException
     */
    public function updateUser(int $userId, string $first_name, string $last_name, int $phone, int $status, string $password, string $username): User
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
        }
        $user->setFirstName($first_name);
        $user->setLastName($last_name);
        $user->setPhone($phone);
        $user->setStatus($status);
        $user->setUsername($username);
        $user->setPassword($this->password_encoder->encodePassword($user, $password));
        $this->userRepository->save($user);
        return $user;
    }

    /**
     * @param $username
     * @param $old_password
     * @param $new_password
     * @return User|bool|null
     */
    public function changePassword($username, $old_password, $new_password)
    {
        $user = $this->userRepository->loadUserByUsername($username);
        if ($user && $this->password_encoder->isPasswordValid($user,$old_password))
        {
            $user->setPassword($this->password_encoder->encodePassword($user, $new_password));
            $this->userRepository->save($user);
            return $user;
        }
        return false;
    }
    /**
     * @param int $userId
     * @throws EntityNotFoundException
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
        }
        $this->userRepository->delete($user);
    }
}
