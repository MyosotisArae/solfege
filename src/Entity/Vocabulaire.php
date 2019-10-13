<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Objet\Portee;

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
      //if ($id != null) $this->id = $id;
      $this->nom = $n;
      $this->description = $d;
      $this->symbole = $s;
      $this->commentaire = $c;
      $this->categorie = '';
      $this->ordre = 0;
      $this->texteQuestion = '';

      $this->mauvaisesReponses = array();

      // Utilis� pour stocker une liste d'images dans certains exercices,
      // comme dans rythme niveau 1 par exemple.
      $this->portee1 = null;
      $this->portee2 = null;
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

    /**
     * Relation pour trouver les "mauvaises r�ponses" � associer � chaque �l�ment.
     * @ORM\ManyToMany(targetEntity="App\Entity\Vocabulaire")
     */
    private $mauvaisesReponses; // Tableau d'�l�ments de Vocabulaire
    
    private $portee1;
    private $portee2;

    private $texteQuestion;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getportee1(): ?Portee
    {
        return $this->portee1;
    }

    public function getportee2(): ?Portee
    {
        return $this->portee2;
    }

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

    public function getMauvaisesReponses()
    {
        $liste = array();
        foreach ($this->mauvaisesReponses as $mr) { $liste[] = $mr; }  
        return $liste;
    }

    public function getTexteQuestion(): ?string
    {
        return $this->texteQuestion;
    }
    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setPortee1(Portee $p)
    {
        $this->portee1 = $p;
        return $this;
    }

    public function setPortee2(Portee $p)
    {
        $this->portee2 = $p;
        return $this;
    }

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

    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function setTexteQuestion(string $t)
    {
        $this->texteQuestion = $t;
        return $this;
    }

    public function addMauvaiseReponse(Vocabulaire $m)
    {
        $this->mauvaisesReponses[] = $m;
        return $this;
    }

}
?>