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
                  ->getQuery()
                  ->getResult();
    }
    /**
      * Retourne tous les éléments de la catégorie "instrument"
      * qui ont leur champ symbole renseigné (c'est leur mp3).
      */
/*
    public function getInstrumentsAvecSon()
    {
      return $this->createQueryBuilder('v')
                  ->where('v.categorie = :cat')
                  ->setParameter('cat', 'instrument')
                  ->andwhere('v.symbole IS NOT NULL')
                  ->getQuery()
                  ->getResult();
    }
*/
    ///////////////////////////////////////////////////////////////////////////////
    //                              Mes fonctions                                //
    ///////////////////////////////////////////////////////////////////////////////

}