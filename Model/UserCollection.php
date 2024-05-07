<?php

namespace Model;

use Vstore\Router\Model\AbstractCollection;
use Model\User;
use Model\UserRepository;

/**
 *
 */
class UserCollection extends AbstractCollection
{
    /**
     *
     */
    public function __construct()
    {
        $this->_init(User::class, UserRepository::class);
    }
}
