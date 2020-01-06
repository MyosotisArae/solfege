<?php
namespace App\Repository;

use App\Entity\Musicien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MusicienRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Musicien::class);
    }

    // Retourne le musicien ayant ce nom ou un nouveau musicien d'id 0.
    /**
      * @return Musicien
      */
      public function getMusicien(string $nom)
      {
        $qb = $this->createQueryBuilder('m')
                   ->andwhere('m.nom = :mus')
                   ->setParameter('mus', $nom);
  
        $listeComplete = $qb
                         ->getQuery()
                         ->getResult();
  
        if (count($listeComplete) == 0)
        {
            return new Musicien;
        }
        return $listeComplete[0];
      }
  
}