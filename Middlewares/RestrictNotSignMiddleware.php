<?php

namespace Middlewares;

use Vstore\Router\Http\Middleware;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Used to restrict not signed users from dashboard
 */
class RestrictNotSignMiddleware extends Middleware
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * @param Session $session
     * @param Request $request
     */
    public function __construct(
        Session $session,
        Request $request
    ) {
        $this->session = $session;
        $this->request = $request->createFromGlobals();
    }

    /**
     * @return RedirectResponse|true
     */
    public function handle(): bool|RedirectResponse
    {
        return true;
        // Restrict not signed users from dashboard
        // TODO implement API call fixes here
        if ((!str_contains($this->request->getRequestUri(), 'login') &&
            !str_contains($this->request->getRequestUri(), 'register')) &&
            $this->isGuestUser()
        ) {
            return new RedirectResponse('/account/login');
        }

        // Redirect signed users from login and register pages, only logout page is allowed
        if ((str_contains($this->request->getRequestUri(), 'login') ||
            str_contains($this->request->getRequestUri(), 'register')) &&
            !$this->isGuestUser()
        ) {
            return new RedirectResponse('/');
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function isGuestUser(): bool
    {
        $result  = false;

        if ($this->session->has('user_role') && $this->session->get('user_role') == 'guest') {
            $result = true;
        }

        return $result;
    }
}
