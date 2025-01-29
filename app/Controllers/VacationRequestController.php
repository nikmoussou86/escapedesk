<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Entity\User;
use Twig\Environment;
use Psr\Http\Message\ServerRequestInterface;
use App\Repositories\Contracts\SessionHandlerInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\VacationRequestRepositoryInterface;

class VacationRequestController extends BaseController
{
    public function __construct(
        protected SessionHandlerInterface $sessionHandler,
        private VacationRequestRepositoryInterface $vacationRequestRepository,
        protected UserRepositoryInterface $userRepository,
        private Environment $twig,
    )
    {}

    public function index()
    {
        $authUser = $this->getAuthUser();
        $vacationRequests = $this->vacationRequestRepository->getThem($authUser);

        return $this->twig->render('vacation_requests/index.twig', [
            'authUser' => $authUser,
            'vacationRequests' => $vacationRequests,
        ]);
    }

    public function create(): string
    {
        return $this->twig->render('vacation_requests/create.twig', [
            'authUser' => $this->getAuthUser(),
        ]);
    }

    public function store(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        // Store the user in the database
        $this->vacationRequestRepository->create($data);

        header('Location: /vacation_requests');
        exit;
    }

    public function edit(ServerRequestInterface $request, array $params)
    {
        $this->hasAccess('vacation.update');
        
        $vacationRequest = $this->vacationRequestRepository->findByVacationRequestId(intval($params['vacationRequestId']));

        if ($vacationRequest) {
            return $this->twig->render('vacation_requests/edit.twig', [
                'vacationRequest' => $vacationRequest,
                'authUser' => $this->getAuthUser()
            ]);
        }
    }

    public function update(ServerRequestInterface $request)
    {
        $this->hasAccess('vacation.update');

        $data = $request->getParsedBody();

        $this->vacationRequestRepository->update($data);

        header('Location: /vacation_requests');
        exit;
    }

    public function delete(ServerRequestInterface $request)
    {   
        $data = $request->getParsedBody();
        $vacationRequest = $this->vacationRequestRepository->findVacRequestById(intval($data['id']));

        if ($vacationRequest) {
            $this->vacationRequestRepository->delete($vacationRequest);
            
            header('Location: /vacation_requests');
            exit;
        }
        return false;
    }

    protected function getAuthUser(): ?User
    {        
        if (isset($_SESSION['user_id'])) {
            $authUser = $this->userRepository->findByUserId(intval($_SESSION['user_id']));

            if ($authUser) {
                return $authUser;
            }
            return null;
        }
        return null;
    }
}