<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Response;
use Vstore\Router\Http\Controller;
use Vstore\Router\View\LayoutProccessor;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Model\VehicleRepository;

class Vehicle extends Controller
{
    protected Session $session;

    protected Response $response;

    protected Request $request;

    protected VehicleRepository $vehicleRepository;

    protected LayoutProccessor $layoutProccessor;

    public function __construct(
        Session $session,
        Response $response,
        LayoutProccessor $layoutProccessor,
        Request $request,
        VehicleRepository $vehicleRepository
    ) {
        $this->session = $session;
        $this->response = $response;
        $this->layoutProccessor = $layoutProccessor;
        $this->request = $request->createFromGlobals();
        $this->vehicleRepository = $vehicleRepository;
    }

    public function listAction(): Response
    {
        $items = $this->vehicleRepository->list();

        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Vehicle List')
                ->setData('items', $items)
                ->render('vehicle/list')
        );
    }

    public function createViewAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Create Vehicle')
                ->render('vehicle/form')
        );
    }

    public function createAction(): Response
    {
        return new RedirectResponse('/vehicle/list');
    }

    public function viewAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('View Vehicle')
                ->render('vehicle/view')
        );
    }

    public function editViewAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Edit Vehicle')
                ->render('vehicle/form')
        );
    }

    public function updateAction(): Response
    {
        return new RedirectResponse('/vehicle/list');
    }
}