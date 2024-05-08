<?php

declare(strict_types=1);

namespace Model;

use Model\Api\UserInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Auth
 */
class Auth
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var Session
     */
    protected Session $session;

    /**
     * @param UserRepository $userRepository
     * @param Session $session
     */
    public function __construct(
        UserRepository $userRepository,
        Session $session
    ) {
        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    /**
     * @param $model
     * @return bool
     */
    public function authorize($model): bool
    {
        $user = $this->checkIfUserExists($model->getEmail());

        if (!$user) {
            $this->session->getFlashBag()->add('error', 'User not found, please register first.');

            return false;
        }

        if (password_verify($model->getPassword(), $user->getPassword())) {
            $this->session->clear();
            $this->session->invalidate();
            $this->session->set('user_name', $user->getName());
            $this->session->set('user_email', $user->getEmail());
            $this->session->set('user_role', $user->getRole());

            return true;
        }

        $this->session->getFlashBag()->add('error', 'Invalid password, please try again.');

        return false;
    }

    /**
     * @param string $email
     * @return UserInterface|bool
     */
    public function checkIfUserExists(string $email) : UserInterface | bool
    {
        if ($email !== '') {
            $user = $this->userRepository->getByEmail($email);

            if (!$user->getId()) {
                return false;
            }
        }

        return $user;
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->session->clear();
        $this->session->invalidate();
        $this->session->set('user_role', 'guest');
    }
}
