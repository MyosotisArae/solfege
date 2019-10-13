<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trophee
 *
 * @ORM\Table(name="trophee")
 * @ORM\Entity(repositoryClass="App\Repository\TropheeRepository")
 */
class Trophee
{
    public function __construct(int $id)
    {
        $this->id = $id;
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
     * @var string
     *
     * @ORM\Column(name="nom", type="text", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=200, nullable=false)
     */
    private $description;

    /**
     * @var \string
     *
     * @ORM\Column(name="image", type="text", length=20, nullable=false)
     */
    private $image;

    /**
     * @var int
     *
     * Relation vers le propriétaire du score
     */
    private $musicien;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getMusicien(): ?int
    {
        return $this->musicien;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setMusicien($m = null)
    {
        $this->musicien = $m;
        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Fonctions                                    //
    ///////////////////////////////////////////////////////////////////////////////

    public function dejaObtenu()
    {
      if ($this->musicien > 0) return true;
      return false;
    }
}
