<?php

namespace Model;

use Vstore\Router\Model\AbstractCollection;
use Model\User;
use Model\UserRepository;

/**
 * Class UserCollection
 */
class UserCollection extends AbstractCollection
{
    /**
     * UserCollection constructor.
     */
    public function __construct()
    {
        $this->_init(User::class, UserRepository::class);
    }
}
