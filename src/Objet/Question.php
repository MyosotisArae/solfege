<?php
namespace App\Objet;

use App\Entity\Vocabulaire;

/**
 * Question
 *
 */
class Question
{
    public function __construct()
    {
      $this->propositions = array();
      $this->reponse = null;
    }

    /**
     * @var array
     */
    private $propositions; // Liste des choix proposés (bonne et mauvaises réponses)

    /**
     * @var array
     */
    private $reponse; // L'id de la bonne réponse dans le tableau propositions

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getPropositions(): ?array
    {
        return $this->propositions;
    }

    public function getProposition(int $i): ?Vocabulaire
    {
        return $this->propositions[$i];
    }

    public function getReponse(): ?Vocabulaire
    {
        return $this->reponse;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Ajoute au maximum 5 propossitions à $this->propositions
     * Prend les nbMax premières s'il y en a trop dans $a
     *
     * $a Le tableau des propositions
     */
    public function setPropositions(array $a, int $nbMax = 5)
    {
        if (count($a) > $nbMax)
        {
          $this->propositions = array();
          foreach (range(1,$nbMax) as $i)
          {
            $this->propositions[] = array_shift($a);
          }
        }
        else $this->propositions = $a;
        return $this;
    }

    public function setReponse(Vocabulaire $a)
    {
        $this->reponse = $a;
        return $this;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Fonctions                                    //
    ///////////////////////////////////////////////////////////////////////////////

    public function addProposition(Vocabulaire $v)
    {
        $this->propositions[] = $v;
    }

    public function melangerPropositions()
    {
        shuffle($this->propositions);
    }

    public function addReponse(Vocabulaire $v)
    {
        $this->reponse = $v;
    }
}
?>