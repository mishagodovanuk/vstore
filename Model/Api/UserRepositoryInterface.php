<?php

namespace Model\Api;

use Model\Api\UserInterface;

/**
 *
 */
interface UserRepositoryInterface
{
    /**
     * @param $user
     * @return mixed
     */
    public function get($user);

    /**
     * @param $user
     * @return mixed
     */
    public function save($user);

    /**
     * @param $user
     * @return mixed
     */
    public function delete($user);
}
