<?php
namespace App\Repository;

use App\Entity\Score;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Score::class);
        $this->disciplines = array("italien","instrument","tonalite","rythme");
    }
    
    ///////////////////////////////////////////////////////////////////////////////
    //                              Mes fonctions                                //
    ///////////////////////////////////////////////////////////////////////////////

    private function getIndiceDiscipline($discipline)
    {
      return array_search($discipline, $this->disciplines);
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Mes requetes                                 //
    ///////////////////////////////////////////////////////////////////////////////


    // Retourne les scores dans l'ordre alphabétique des disciplines puis des niveaux.
    /**
      * @return array[Score[]]
      */
    public function getListe()
    {
      $qb = $this->createQueryBuilder('s')
            ->orderBy('s.discipline, s.niveau');

      $listeComplete = $qb
                       ->getQuery()
                       ->getResult();

      // Classer ces objets par discipline
      $liste = array();
      foreach ($this->disciplines as $dis)
      {
        $liste[$dis] = array();
      }
      foreach ($listeComplete as $sc)
      {
        $liste[$this->disciplines[$sc.discipline]][] = $sc;
      }

      return $liste;
    }

    public function getNiveau($discipline)
    {
      $qb = $this->createQueryBuilder('s')
                 ->select('MAX(s.niveau)')
                 ->where('s.discipline = :d')
                 ->setParameter('d', $this->getIndiceDiscipline($discipline));

      $niveau = $qb->getQuery()
                   ->getSingleScalarResult();

      if ($niveau == null) $niveau = 0;

      return $niveau;
    }
}