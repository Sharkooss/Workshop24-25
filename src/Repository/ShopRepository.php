<?php

// src/Repository/ShopRepository.php

namespace App\Repository;

use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Shop>
 */
class ShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shop::class);
    }

    /**
     * Récupérer les produits avec un stock positif
     *
     * @return Shop[]
     */
    public function findProductsInStock(): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.stock_shop > 0')
            ->getQuery()
            ->getResult();
    }
}

