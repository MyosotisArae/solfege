<?php
namespace App\Repository;

use App\Entity\Score;
use App\Objet\P_constantes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Score::class);
        $this->cst = new P_constantes();
        $this->listeDisciplines = array("italien","instrument","tonalite","rythme");
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Mes requetes                                 //
    ///////////////////////////////////////////////////////////////////////////////


    // Retourne les scores dans l'ordre alphabétique des disciplines puis des niveaux.
    /**
      * @return array[Score[]]
      */
    public function getScores(int $id)
    {
      $qb = $this->createQueryBuilder('s')
                 ->andwhere('s.musicien = :mus')
                 ->setParameter('mus', $id)
                 ->orderBy('s.discipline, s.niveau');

      $listeComplete = $qb
                       ->getQuery()
                       ->getResult();

      // Classer ces objets par discipline
      $liste = array();
      foreach ($this->cst->get_listeDisciplines() as $dis)
      {
        $liste[$dis] = array();
      }
      foreach ($listeComplete as $sc)
      {
        $liste[$this->listeDisciplines[$sc->getDiscipline()]][] = $sc;
      }

      return $liste;
    }

    // Retourne le numéro du prochain niveau à terminer dans cette discipline pour le musicien avec cet id.
    public function getNiveau($discipline, int $id)
    {
      $qb = $this->createQueryBuilder('s')
                 ->select('MAX(s.niveau)')
                 ->andwhere('s.musicien = :mus')
                 ->andwhere('s.discipline = :d')
                 ->setParameter('mus', $id)
                 ->setParameter('d', $this->cst->getIndiceDiscipline($discipline));

      $niveau = $qb->getQuery()
                   ->getSingleScalarResult();

      if ($niveau == null) $niveau = 0;
      else
      {
        // Un score a été enregistré pour cette discipline. Vérifier si c'est le score max.
        $ceScore = $this->getScoreDuNiveau($niveau, $discipline, $id);
        if ($ceScore->getScore() < $ceScore->getScoreMax()) { $niveau -= 1; }
      }

      return $niveau;
    }

    // Retourne l'entité score pour ce niveau et cette discipline,
    // une nouvelle entité si aucun score n'existe encore pour ces critères.
    public function getScoreDuNiveau(int $niveau, string $discipline, int $id)
    {
      $indiceDiscipline = $this->cst->getIndiceDiscipline($discipline);
      $qb = $this->createQueryBuilder('s')
                 ->andwhere('s.musicien = :mus')
                 ->andwhere('s.discipline = :d')
                 ->andWhere('s.niveau = :n')
                 ->setParameter('mus', $id)
                 ->setParameter('d', $indiceDiscipline)
                 ->setParameter('n', $niveau);

      $score = $qb->getQuery()
                  ->getOneOrNullResult();

      if ($score == null) $score = new Score($niveau, $indiceDiscipline, 0, $id);

      return $score;
    }
}