<?php

namespace Middlewares;

use Vstore\Router\Http\Middleware;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Vstore\Router\RouterPermission;
use Psr\Container\ContainerInterface;

/**
 * Used to restrict not signed users from dashboard
 */
class RestrictNotSignMiddleware extends Middleware
{
    /**
     * @var Session
     */
    private Session $session;

    protected RouterPermission $routerPermission;

    protected string|int $currentRouterId;

    /**
     * @param Session $session
     * @param Request $request
     * @param RouterPermission $routerPermission
     * @param ContainerInterface $container
     */
    public function __construct(
        Session $session,
        Request $request,
        RouterPermission $routerPermission,
        ContainerInterface $container
    ) {
        $this->session = $session;
        $this->request = $request->createFromGlobals();
        $this->routerPermission = $routerPermission;
        $this->currentRouterId = $container->get('currentRouterId');
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
