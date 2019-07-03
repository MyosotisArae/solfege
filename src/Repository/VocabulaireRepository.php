<?php
namespace App\Repository;

use App\Entity\Vocabulaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class VocabulaireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vocabulaire::class);
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Mes requetes                                 //
    ///////////////////////////////////////////////////////////////////////////////

    /**
      * Retourne une certaine catégorie de réponses.
      */
    public function getCategorie(string $cat)
    {
      return $this->createQueryBuilder('v')
                  ->where('v.categorie = :cat')
                  ->setParameter('cat', $cat)
                  ->orderBy('v.ordre, v.nom')
                  ->getQuery()
                  ->getResult();
    }
    
    ///////////////////////////////////////////////////////////////////////////////
    //                              Mes fonctions                                //
    ///////////////////////////////////////////////////////////////////////////////

}