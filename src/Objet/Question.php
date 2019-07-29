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
      $this->reponses = array();
    }

    /**
     * @var array
     */
    private $propositions; // Liste des choix proposés (bonnes et mauvaises réponses)

    /**
     * @var array
     */
    private $reponses; // La/les bonnes réponses dans le tableau propositions

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

    public function getReponses(): ?array
    {
        return $this->reponses;
    }

    public function getReponse(): ?Vocabulaire
    {
        return $this->reponses[0];
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setPropositions(array $a)
    {
        $this->propositions = $a;
        return $this;
    }

    public function setReponses(array $a)
    {
        $this->reponses = $a;
        return $this;
    }

    public function setReponse(Vocabulaire $a)
    {
        $this->reponses[0] = $a;
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
        $this->reponses[] = $v;
    }
}
?>