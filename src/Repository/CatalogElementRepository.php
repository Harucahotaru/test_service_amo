<?php

namespace App\Repository;

use App\Entity\CatalogElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CatalogElement>
 *
 * @method CatalogElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatalogElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatalogElement[]    findAll()
 * @method CatalogElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatalogElement::class);
    }

    public function add(CatalogElement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CatalogElement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
