<?php
namespace App\Repository;

use App\Entity\Trophee;
use App\Entity\Musicien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TropheeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trophee::class);
    }

    public function getTrophees()
    {
        $qb = $this->createQueryBuilder('t')
                 ->orderBy('t.id');

        return $qb->getQuery()
                  ->getResult();
    }
}