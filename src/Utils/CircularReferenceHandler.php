<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 23.02.19
 * Time: 20:23
 */

namespace App\Utils;


class CircularReferenceHandler
{
    public function __invoke($object)
    {
        return $object->getId();
    }
}
