<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table(name="vocabulaire")
 * @ORM\Entity(repositoryClass="App\Repository\VocabulaireRepository")
 */
class Vocabulaire
{
    public function __construct(string $n='', string $d='', string $s='', string $c='')
    {
      $this->nom = $n;
      $this->description = $d;
      $this->symbole = $s;
      $this->commentaire = $c;
      $this->categorie = '';
      $this->ordre = 0;
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
     * @ORM\Column(name="categorie", type="text", length=20, nullable=false)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="text", length=100, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=200, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="symbole", type="text", length=100, nullable=false)
     */
    private $symbole;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", length=2000, nullable=false)
     */
    private $commentaire;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer", nullable=false)
     */
    private $ordre;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getSymbole(): ?string
    {
        return $this->symbole;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setNom(string $n)
    {
        $this->nom = $n;
        return $this;
    }

    public function setDescription(string $d)
    {
        $this->description = $d;
        return $this;
    }

    public function setSymbole(string $s)
    {
        $this->symbole = $s;
        return $this;
    }

    public function setCommentaire(string $c)
    {
        $this->commentaire = $c;
        return $this;
    }

    public function setCategorie(string $c)
    {
        $this->categorie = $c;
        return $this;
    }

    public function setOrdre(int $o)
    {
        $this->ordre = $o;
        return $this;
    }

}
?>