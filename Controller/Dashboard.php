<?php

namespace Controller;


use Symfony\Component\HttpFoundation\Response;
use Vstore\Router\Http\Controller;
use Vstore\Router\View\LayoutProccessor;
use Symfony\Component\HttpFoundation\Session\Session;
use Middlewares\RestrictNotSignMiddleware;

/**
 *
 */
class Dashboard extends Controller
{
    public static array $roles = [
        'admin' => 'all',
        'user' => 'all'
    ];

    public array $middlewareBefore = [
        RestrictNotSignMiddleware::class
    ];


    /**
     * @var Response
     */
    protected Response $response;

    protected Session $session;

    /**
     * @param Response $response
     * @param LayoutProccessor $layoutProccessor
     * @param Session $session
     */
    public function __construct(
        Response $response,
        LayoutProccessor $layoutProccessor,
        Session $session
    ) {
        $this->response = $response;
        $this->layoutProccessor = $layoutProccessor;
        $this->session = $session;

        $this->layoutProccessor->setData('userdata', $this->getSessionUser());
    }

    /**
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Dashboard')
                ->render('dashboard', ['user'=>'John Doe'])
        );
    }

    /**
     * @return Response
     */
    public function companyAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Company')
                ->render('maintenance', ['content'=>'Still in development'])
        );
    }

    /**
     * @return Response
     */
    public function driversAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Drivers')
                ->render('maintenance', ['content'=>'Still in development'])
        );
    }

    /**
     * @return Response
     */
    public function warehouseAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Warehouse')
                ->render('maintenance', ['content'=>'Still in development'])
        );
    }

    /**
     * @return Response
     */
    public function driverAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Driver')
                ->render('maintenance', ['content'=>'Still in development'])
        );
    }

    /**
     * @return Response
     */
    public function productAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Product')
                ->render('maintenance', ['content'=>'Still in development'])
        );
    }

    /**
     * @return Response
     */
    public function adminAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Admin')
                ->render('maintenance', ['content'=>'Still in development'])
        );
    }

    private function getSessionUser() : array | bool
    {
        $result = false;

        if ($this->session->has('user_name')) {
            $result = [
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('user_role')
            ];
        }

        return $result;
    }
}
