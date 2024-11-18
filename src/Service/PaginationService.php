<?php

namespace App\Service;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PaginationService
{
    public function __construct(private PaginatorInterface $paginator) {}

    public function paginate(Request $request, Query $query, int $limit = 5): PaginationInterface
    {
        return $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );
    }
}