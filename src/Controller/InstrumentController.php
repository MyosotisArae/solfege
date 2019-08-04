<?php
namespace App\Controller;

use App\Entity\Vocabulaire;
use App\Objet\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstrumentController extends ExerciceController
{
  /**
   * @Route("/instrument", name="instrument")
   */
  public function main1()
  {
    $this->setSss('titreExo', 'instruments' );
    return $this->question('instrument');
  }

  /**
   * @Route("/instrumentCorrection{numRep}", name="instrumentCorrection", requirements={"numRep" = "\d+"})
   */
  public function main2($numRep)
  {
    return $this->correction('instrument', $numRep);
  }

  /**
   * @Route("/apprendre_instrument", name="apprendre_instrument")
   */
  public function main3()
  {
    //$this->getCategorie('nuance');
    $this->reinitNiveau();
    return $this->apprentissage('instrument');
  }

  /**
   * Cette fonction récupère la catégorie du niveau en cours dans la table Vocabulaire.
   * @return array d'objets Vocabulaire
   */
  protected function getVocalulaire()
  {
    switch ($this->getSss('niveau'))
    {
      case 1: return $this->getCategorie('instrument');
      case 4: 
      case 2:
      case 5: return $this->getCategorie('tempo');
      case 3:
      case 6: return $this->getCategorie('expression');
    }
  }

  protected function initNiveau()
  {
    parent::initNiveau();
    switch ($this->getSss('niveau'))
    {
      case 1 : $this->setSss('modele', 'QCM_commentaire'); break;
      case 2 :
      case 3 : $this->setSss('modele', 'ecoute'); break;
      case 5 :
      case 6 : $this->setSss('modele', 'QCM_nom'); break;
      case 4 : $this->setSss('modele', 'QCM_description'); break;
    }
  }

  protected function addFaussesReponses(Question $question, Vocabulaire $bonneReponse)
  {
    $question->addProposition($bonneReponse);
    // Nombre total de réponses à proposer (dont la bonne) : 4
    $familles = array("corde","bois","cuivre","percussion");
    foreach ($familles as $f)
    {
      if ($f != $bonneReponse->getCommentaire())
      {
        $question->addProposition(new Vocabulaire("","","",$f));
      }
    }
    $question->melangerPropositions();
  }

  protected function complementCorrection(Vocabulaire $bonneReponse)
  {
    $msg = $this->getSss('correction');
    switch ($this->getSss('niveau'))
    {
      case 1 : $msg .= $bonneReponse->getCommentaire() . '"';
               if ($bonneReponse->getDescription() != "")
               {
                 $msg .= ", car " . $bonneReponse->getDescription();
               }
               break;
      case 2 : 
      case 4 :
      case 5 :
      case 6 : $msg .= $bonneReponse->getDescription().'".';
    }
    $msg .= '.';

    $this->setSss('correction', $msg);
  }
  
  function jouerMorceau()
  {
    $snd = new Audio("musiques/test.wav"); // buffers automatically when created
    $snd.play();
  }
}

?>