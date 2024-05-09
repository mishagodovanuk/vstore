<?php

declare(strict_types=1);

namespace Controller;

use Middlewares\AccessRoleMiddleware;
use Model\Vehicle as VehicleModel;
use Model\VehicleRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Vstore\Router\Http\Controller;
use Vstore\Router\View\LayoutProccessor;

/**
 *  Vehicle controller.
 */
class Vehicle extends Controller
{
    /**
     * @var array|string[]
     */
    public array $middlewareBefore = [
        AccessRoleMiddleware::class
    ];

    /**
     * @var Session
     */
    protected Session $session;

    /**
     * @var Response
     */
    protected Response $response;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var VehicleRepository
     */
    protected VehicleRepository $vehicleRepository;

    /**
     * @var LayoutProccessor
     */
    protected LayoutProccessor $layoutProccessor;

    /**
     * @param Session $session
     * @param Response $response
     * @param LayoutProccessor $layoutProccessor
     * @param Request $request
     * @param VehicleRepository $vehicleRepository
     */
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

        $this->layoutProccessor->setData('userdata', $this->getSessionUser());
    }

    /**
     * @return Response
     */
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

    /**
     * @param mixed $id
     * @return Response|RedirectResponse
     */
    public function viewAction(mixed $id): Response | RedirectResponse
    {
        if (!$id) {
            return new RedirectResponse('/vehicle/list');
        }

        $item = null;

        try {
            $item = $this->vehicleRepository->getById($id);
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', 'Vehicle not found');

            return new RedirectResponse('/vehicle/list');
        }

        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle("Vehicle view")
                ->setData('item', $item)
                ->render('vehicle/view')
        );
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function deleteAction(mixed $id): bool
    {
        try {
            $this->vehicleRepository->deleteById($id);
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', 'Vehicle not found');

            return false;
        }

        $this->session->getFlashBag()->add('success', 'Vehicle deleted');

        return true;
    }

    /**
     * @param mixed $id
     * @return Response|RedirectResponse
     */
    public function editAction(mixed $id): Response | RedirectResponse
    {
        $item = null;

        if ($id) {
            try {
                $item = $this->vehicleRepository->getById($id);
            } catch (\Exception $e) {
                $this->session->getFlashBag()->add('error', 'Vehicle not found');

                return new RedirectResponse('/vehicle/list');
            }
        }

        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Edit Vehicle')
                ->setData('item', $item)
                ->render('vehicle/form')
        );
    }

    /**
     * @return Response
     */
    public function createAction(): Response
    {
        return $this->response->setContent(
            $this->layoutProccessor
                ->setTitle('Create Vehicle')
                ->setData('item', new VehicleModel())
                ->render('vehicle/form')
        );
    }


    /**
     * @return Response
     */
    public function saveAction(): Response
    {
        $type = $this->request->request->get('type');

        if ($type) {
            $model = new VehicleModel();
            $model->setName($type);

            try {
                $model = $this->vehicleRepository->save($model);
                $this->session->getFlashBag()->add('success', 'Vehicle saved');

                return new RedirectResponse("/vehicle/{$model->getId()}");
            } catch (\Exception $e) {
                $this->session->getFlashBag()->add('error', 'Vehicle not saved');
            }
        }

        return new RedirectResponse('/vehicle/list');
    }

    /**
     * @param $id
     * @return Response
     */
    public function updateAction($id): Response
    {
        if ($id) {
            $type = $this->request->request->get('type');
            $model = new VehicleModel();
            $model->setId($id);
            $model->setName($type);

            try {
                if ($this->vehicleRepository->update($model)) {
                    $this->session->getFlashBag()->add('success', 'Vehicle updated');
                };
            } catch (\Exception $e) {
                $this->session->getFlashBag()->add('error', 'Vehicle not updated');
            }
        }

        return new RedirectResponse($this->request->headers->get('referer'));
    }

    /**
     * @return array|bool
     */
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
