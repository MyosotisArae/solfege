<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table(name="score")
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
class Score
{
    public function __construct(int $n, int $d, int $s)
    {
      $this->niveau = $n;
      $this->discipline = $d;
      $this->score = $s;
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

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setScore(int $s)
    {
        $this->score = $s;
        return $this;
    }

}
