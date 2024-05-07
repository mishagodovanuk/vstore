<?php

namespace Controller;

use Middlewares\RestrictNotSignMiddleware;
use Model\Auth;
use Model\User;
use Model\UserRepository;
use Model\Validator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Vstore\Router\Http\Controller;
use Vstore\Router\View\LayoutProccessor;

/**
 * Account controller.
 */
class Account extends Controller
{
    /**
     * @var array|string[]
     */
    public array $middlewareBefore = [
        RestrictNotSignMiddleware::class
    ];

    /**
     * @var Response
     */
    protected Response $response;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var Session
     */
    protected Session $session;

    /**
     * @var Auth
     */
    protected Auth $auth;

    /**
     * @var Validator
     */
    protected Validator $validator;

    /**
     * @param Request $request
     * @param Response $response
     * @param LayoutProccessor $layoutProccessor
     * @param UserRepository $userRepository
     * @param Session $session
     * @param Auth $auth
     * @param Validator $validator
     */
    public function __construct(
        Request $request,
        Response $response,
        LayoutProccessor $layoutProccessor,
        UserRepository $userRepository,
        Session $session,
        Auth $auth,
        Validator $validator
    ) {
        $this->request = $request->createFromGlobals();
        $this->response = $response;
        $this->layoutProccessor = $layoutProccessor;
        $this->userRepository = $userRepository;
        $this->session = $session;
        $this->auth = $auth;
        $this->validator = $validator;
    }

    /**
     * @return Response | string
     */
    public function loginAction(): Response | string
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Login page')
                ->render('login', layout: 'default')
        );
    }

    /**
     * @return Response
     */
    public function registerAction(): Response
    {
        return $this->response->setContent($this->layoutProccessor
            ->setTitle('Register page')
            ->render('register',layout:  'default'));
    }

    /**
     * @return RedirectResponse
     */
    public function loginPostAction(): RedirectResponse
    {
        $email = $this->request->request->get('email');
        $password = $this->request->request->get('password');

        if (!$this->validator->isValidEmail($email)) {
            $this->session->getFlashBag()->add('error', 'Invalid email or password');

            return new RedirectResponse($this->request->headers->get('referer'));
        }

        $user = new User();
        $user->setData('email', $email);
        $user->setData('password', $password);

        if ($this->auth->authorize($user)) {
            return new RedirectResponse('/');
        }

        $this->session->getFlashBag()->add('error', 'Error during authorization, please try again.');

        return new RedirectResponse($this->request->headers->get('referer'));
    }

    /**
     * @return RedirectResponse
     */
    public function registerPostAction(): RedirectResponse
    {
        $email = $this->request->request->get('email');
        $password = $this->request->request->get('password');
        $name = $this->request->request->get('username');
        $role = $this->request->request->get('role');
        $errors = 0;

        if ($this->auth->checkIfUserExists($email)) {
            $this->session->getFlashBag()->add('error', 'User already exists');

            return new RedirectResponse($this->request->headers->get('referer'));
        }

        if (!$this->validator->isValidEmail($email)) {
            $this->session->getFlashBag()->add('error', 'Invalid email');
            $errors++;
        }

        if (!$this->validator->isValidUsername($name)) {
            $this->session->getFlashBag()->add(
                'error',
                'Invalid username, Allows alphanumeric characters and underscores, with a length between 3 and 20 characters'
            );
            $errors++;
        }
        if (!$this->validator->isValidPassword($password)) {
//            $this->session->getFlashBag()->add(
//                'error',
//                'Invalid password, Requires at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character'
//            );
//            $errors++;
        }

        if ($errors > 0) {
            return new RedirectResponse($this->request->headers->get('referer'));
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setName($name);
        $user->setRole($role);

        try {
            $this->userRepository->save($user);
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', $e->getMessage());

            return new RedirectResponse('/account/login');
        }

        $this->session->getFlashBag()->add('success', 'User registered successfully');

        return new RedirectResponse('/account/login');
    }

    /**
     * @return RedirectResponse
     */
    public function logoutAction(): RedirectResponse
    {
        $this->auth->logout();

        return new RedirectResponse('/account/login');
    }
}
