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
use Model\UserRepository;

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
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @param Session $session
     * @param Request $request
     * @param RouterPermission $routerPermission
     * @param ContainerInterface $container,
     * @param UserRepository $userRepository
     */
    public function __construct(
        Session $session,
        Request $request,
        Response $response,
        RouterPermission $routerPermission,
        ContainerInterface $container,
        UserRepository $userRepository
    ) {
        parent::__construct($request, $response);

        $this->session = $session;
        $this->routerPermission = $routerPermission;
        $this->currentRouterId = $container->get('currentRouterId');
        $this->userRepository = $userRepository;
    }

    /**
     * @return RedirectResponse|true
     */
    public function handle(): bool|RedirectResponse
    {
        $userRole = $this->getUserRole();
        $bearerToken = $this->getBearerToken();

        if ($bearerToken) {
           $user = $this->userRepository->getUserByToken($bearerToken);

           if ($user) {
                $userRole = $user->getRole();
           }
        }

        $access = $this->routerPermission->getPermission($this->currentRouterId);

        if ($access && !$this->checkPermission($access, $userRole)) {
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
     * @return bool|string
     */
    protected function getBearerToken(): bool|string
    {
        $bearerToken = $this->request->headers->get('authorization');

        if (!$bearerToken) {
            return false;
        }

        return trim(preg_replace("/\bBearer\b\s*/", "", $bearerToken));
    }

    /**
     * @return string
     */
    private function getUserRole(): string
    {
        return $this->session->get('user_role') ?? 'guest';
    }
}
