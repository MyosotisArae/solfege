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
     * @var int
     *
     * @ORM\Column(name="obtenu", type="integer", nullable=false)
     */
    private $obtenu;

    /**
     * @var \string
     *
     * @ORM\Column(name="image", type="text", length=20, nullable=false)
     */
    private $image;

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

    public function getObtenu()
    {
        return $this->obtenu;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setObtenu($obtenu)
    {
        $this->obtenu = $obtenu;
        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Fonctions                                    //
    ///////////////////////////////////////////////////////////////////////////////

    public function dejaObtenu()
    {
      if ($this->obtenu == 0) return false;
      return true;
    }
}
