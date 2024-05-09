<?php

declare(strict_types=1);

namespace Middlewares;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Vstore\Router\Http\Middleware;
use Vstore\Router\RouterPermission;

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
        Response $response,
        RouterPermission $routerPermission,
        ContainerInterface $container
    ) {
        parent::__construct($request, $response);

        $this->session = $session;
        $this->routerPermission = $routerPermission;
        $this->currentRouterId = $container->get('currentRouterId');
    }

    /**
     * @return RedirectResponse|true
     */
    public function handle(): bool|RedirectResponse
    {
        $access = $this->routerPermission->getPermission($this->currentRouterId);

        if (!$this->checkPermission($access, $this->getUserRole())) {
            //TODO fix this part of code, curretly it's a little trick
            $redirectUrl = $this->getUserRole() == 'guest' ? '/account/login' : $this->request->headers->get('referer');
            $redirectUrl = $redirectUrl ?? '/';

            return new RedirectResponse($redirectUrl);
        }

        return true;
    }

    /**
     * @param $access
     * @param string $role
     * @return bool
     */
    protected function checkPermission($access, string $role): bool
    {
        if (array_key_exists($role, $access)) {
            return true;
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
