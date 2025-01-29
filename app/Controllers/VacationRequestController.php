<?php
declare(strict_types=1);

namespace App\Controllers;

use Twig\Environment;
use Psr\Http\Message\ServerRequestInterface;
use App\Repositories\VacationRequestRepository;

class VacationRequestController
{
    public function __construct(
        private VacationRequestRepository $vacationRequestRepository,
        private Environment $twig,
    )
    {}

    public function index()
    {
        $vacationRequests = $this->vacationRequestRepository->findAll();

        return $this->twig->render('vacation_requests/index.twig', [
            'vacationRequests' => $vacationRequests,
        ]);
    }

    public function create(): string
    {
        return $this->twig->render('vacation_requests/create.twig');
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
        $vacationRequest = $this->vacationRequestRepository->findByVacationRequestId(intval($params['vacationRequestId']));

        if ($vacationRequest) {
            return $this->twig->render('vacation_requests/edit.twig', [
                'vacationRequest' => $vacationRequest
            ]);
        }
    }

    public function update(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        $this->vacationRequestRepository->update($data);

        header('Location: /vacation_requests');
        exit;
    }

    public function delete(ServerRequestInterface $request, array $params)
    {
        $vacationRequest = $this->vacationRequestRepository->findByVacationRequestId(intval($params['vacationRequestId']));

        if ($vacationRequest) {
            $this->vacationRequestRepository->delete($vacationRequest);
            
            header('Location: /vacation_requests');
            exit;
        }
        return false;
    }
}