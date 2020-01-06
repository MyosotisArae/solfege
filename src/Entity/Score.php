<?php

namespace App\Entity;

use App\Objet\P_constantes;
use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table(name="score")
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
class Score
{
    public function __construct(int $n, int $d, int $s, int $id)
    {
      $this->niveau = $n;
      $this->discipline = $d;
      $this->score = $s;
      $this->musicien = $id;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="discipline", type="integer", nullable=false)
     */
    private $discipline;

    /**
     * @var int
     *
     * @ORM\Column(name="niveau", type="integer", nullable=false)
     */
    private $niveau;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=false)
     */
    private $score;

    /**
     * Relation vers le propriétaire du score
     * @ORM\Column(name="musicien", type="integer", nullable=false))
     */
    private $musicien;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscipline(): ?int
    {
        return $this->discipline;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function getScoreMax(): ?int
    {
        $cst = new P_constantes();
        return $cst->getScoreMax_int($this->getDiscipline(), $this->getNiveau());
    }

    public function getMusicien()
    {
        return $this->musicien;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setScore(int $s)
    {
        $this->score = $s;
        return $this;
    }
    public function setMusicien(int $m)
    {
        $this->musicien = $m;
        return $this;
    }

}
