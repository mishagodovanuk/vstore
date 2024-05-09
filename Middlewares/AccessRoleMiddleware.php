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
class AccessRoleMiddleware extends Middleware
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var RouterPermission
     */
    protected RouterPermission $routerPermission;

    /**
     * @var string|int|mixed
     */
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
        $access = $this->routerPermission->getPermission($this->currentRouterId);

        if ($this->checkPermission($access, $this->getUserRole())) {
            $redirectUrl = $this->getUserRole() == 'guest' ? '/login' : $this->request->headers->get('referer');

            return new RedirectResponse($redirectUrl);
        }

        return true;
    }

    /**
     * @param $access
     * @param string $role
     * @return bool|string
     */
    protected function checkPermission($access, string $role): bool|string
    {
        if (array_key_exists($role, $access)) {
            return $access[$role];
        }

        return false;
    }

    /**
     * @return string
     */
    private function getUserRole(): string
    {
        return $this->session->get('user_role') ?? 'guest';
    }
}
