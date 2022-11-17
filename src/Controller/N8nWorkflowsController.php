<?php

namespace App\Controller;

use App\Repository\N8nWorkflowRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class N8nWorkflowsController extends AbstractController
{

    public function __construct(private N8nWorkflowRepository $repo, private RequestStack $requestStack)
    {
        $this->repo = $repo;
    }

    public function __invoke()
    {
        $query = $this->repo->createQueryBuilder('w')
            ->select('w')
            ->orderBy('w.name', 'ASC')
            ->setMaxResults($this->requestStack->getCurrentRequest()->query->get('rows') ?? 10);

        if ($this->requestStack->getCurrentRequest()->query->get('category')) {
            $query
                ->innerJoin('w.categories', 'c')
                ->andWhere('c.id = :id')
                ->setParameter('id', $this->requestStack->getCurrentRequest()->query->get('category'));
        }

        if ($this->requestStack->getCurrentRequest()->query->get('search')) {
            $query->andWhere('w.name LIKE :search')
                ->setParameter('search', '%' . $this->requestStack->getCurrentRequest()->query->get('search') . '%');
        }

        $rows = $this->requestStack->getCurrentRequest()->query->get('rows', 10);
        $skip = $rows * $this->requestStack->getCurrentRequest()->query->get('skip', 0);

    	$query
      	    ->setFirstResult($skip)
      	    ->setMaxResults($rows);

        return [
            'workflows' => $query->getQuery()->getResult(),
        ];
    }
}
