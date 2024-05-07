<?php

namespace Model\Api;

use Model\Api\UserInterface;

interface UserRepositoryInterface
{
    public function get($user);

    public function save($user);

    public function delete($user);
}
