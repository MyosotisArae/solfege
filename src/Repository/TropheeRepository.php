<?php
namespace App\Repository;

use App\Entity\Trophee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TropheeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trophee::class);
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Mes requetes                                 //
    ///////////////////////////////////////////////////////////////////////////////

    // Retourne la liste des trophées
    /**
      * @return Trophee[]
      */
    public function getListe($obtenus)
    {
      $qb = $this->createQueryBuilder('t')
                 ->orderBy('t.id');
      
      if ($obtenus) { $qb->andWhere('t.obtenu = true'); }

      return $qb
        ->getQuery()
        ->getResult()
      ;

    }
}