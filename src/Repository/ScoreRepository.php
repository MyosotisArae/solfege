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
        $this->listeDisciplines = array("italien","instrument","tonalite","rythme");
    }
    
    public $listeDisciplines;
    
    ///////////////////////////////////////////////////////////////////////////////
    //                              Mes fonctions                                //
    ///////////////////////////////////////////////////////////////////////////////

    private function getIndiceDiscipline($discipline)
    {
      return array_search($discipline, $this->listeDisciplines);
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
      foreach ($this->listeDisciplines as $dis)
      {
        $liste[$dis] = array();
      }
      foreach ($listeComplete as $sc)
      {
        $liste[$this->listeDisciplines[$sc->getDiscipline()]][] = $sc;
      }

      return $liste;
    }

    // Retourne le numéro du prochain niveau à terminer dans cette discipline.
    public function getNiveau($discipline)
    {
      $qb = $this->createQueryBuilder('s')
                 ->select('MAX(s.niveau)')
                 ->where('s.discipline = :d')
                 ->setParameter('d', $this->getIndiceDiscipline($discipline))
                 ->andWhere('s.score = 6');

      $niveau = $qb->getQuery()
                   ->getSingleScalarResult();

      if ($niveau == null) $niveau = 0;

      return $niveau;
    }

    // Retourne l'entité score pour ce niveau et cette discipline,
    // une nouvelle entité si aucun score n'existe encore pour ces critères.
    public function getScoreDuNiveau(int $niveau, string $discipline)
    {
      $qb = $this->createQueryBuilder('s')
                 ->where('s.discipline = :d')
                 ->setParameter('d', $this->getIndiceDiscipline($discipline))
                 ->andWhere('s.niveau = :n')
                 ->setParameter('n', $niveau);

      $score = $qb->getQuery()
                  ->getOneOrNullResult();

      if ($score == null) $score = new Score($niveau, $this->getIndiceDiscipline($discipline), 0);

      return $score;
    }
}