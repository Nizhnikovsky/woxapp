<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 24.02.19
 * Time: 9:59
 */

namespace App\Application\Service;

use SymfonyBundles\RedisBundle\Redis\ClientInterface;

final class RedisService
{
    /**
     * @var ClientInterface
     */
    private $redisServer;

    /**
     * RedisService constructor.
     * @param ClientInterface $redisServer
     */
    public function __construct(ClientInterface $redisServer)
    {
        $this->redisServer = $redisServer;
    }

    /**
     * @param $key
     * @param $data
     * @return mixed
     */
    public function setToRedis($key,$data)
    {
        return $this->redisServer->set($key,$data);
    }

    /**
     * @param $key
     * @return int
     */
    public function deleteFromRedis($key)
    {
        return $this->redisServer->del($key);
    }

    /**
     * @param $key
     * @return string
     */
    public function getFromRedis($key)
    {
        return $this->redisServer->get($key);
    }

}
