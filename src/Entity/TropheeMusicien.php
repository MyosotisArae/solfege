<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Musicien
 *
 * @ORM\Table(name="trophee_musicien")
 * @ORM\Entity(repositoryClass="App\Repository\TropheeMusicienRepository")
 */
class TropheeMusicien
{
    public function __construct($t, $m)
    {
        $this->trophee = $t;
        $this->musicien = $m;
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
     * @ORM\Column(name="trophee", type="integer", nullable=false)
     */
    private $trophee;

    /**
     * @var int
     *
     * @ORM\Column(name="musicien", type="integer", nullable=false)
     */
    private $musicien;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrophee(): ?int
    {
        return $this->trophee;
    }

    public function getMusicien(): ?int
    {
        return $this->musicien;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setTrophee($t)
    {
        $this->trophee = $t;
        return $this;
    }

    public function setMusicien($m)
    {
        $this->musicien = $m;
        return $this;
    }
}
