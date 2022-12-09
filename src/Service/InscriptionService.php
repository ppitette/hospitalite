<?php

namespace App\Service;

use App\Repository\InscriptionRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class InscriptionService
{
    public function __construct(
        private RequestStack $requestStack,
        private InscriptionRepository $inscriptionRepository,
        private PaginatorInterface $paginator
    ) {
    }

    public function getPaginatedInscrits(?string $ent = null, bool $desist = true, ?int $lgn = 8): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $inscritsQuery = $this->inscriptionRepository->findForPagination($ent, $desist);
        $page = $request->query->getInt('page', 1);
        $limit = $lgn;

        return $this->paginator->paginate($inscritsQuery, $page, $limit);
    }
}
