<?php
namespace App\Controller;

use App\Entity\Vocabulaire;
use App\Objet\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TonaliteController extends ExerciceController
{
  /**
   * @Route("/tonalite", name="tonalite")
   */
  public function main1()
  {
    $this->setSss('titreExo', 'les tonalités' );
    return $this->question('tonalite');
  }

  /**
   * @Route("/tonaliteCorrection{numRep}", name="tonaliteCorrection", requirements={"numRep" = "\d+"})
   */
  public function main2($numRep)
  {
    return $this->correction('tonalite', $numRep);
  }

  /**
   * @Route("/apprendre_tonalite", name="apprendre_tonalite")
   */
  public function main3()
  {
    $this->reinitNiveau();
    return $this->apprentissage('tonalite');
  }

  /**
   * Cette fonction récupère la catégorie du niveau en cours dans la table Vocabulaire.
   * @return array d'objets Vocabulaire
   */
  protected function getVocalulaire()
  {
    switch ($this->getSss('niveau'))
    {
      case 1: return $this->getCategorie('ton');
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
      case 1 : $this->setSss('modele', 'QCM_symbole'); break;
      case 2 :
      case 3 :
      case 5 :
      case 6 : $this->setSss('modele', 'QCM_nom'); break;
      case 4 : $this->setSss('modele', 'QCM_description'); break;
    }
  }

  protected function complementCorrection(Vocabulaire $bonneReponse)
  {
    $msg = $this->getSss('correction');
    if (substr($msg,0,3) == 'Oui') { $msg = "Oui, en effet, "; }
    else  { $msg = "Non. En fait,  "; }
    $msg .= "c'est un ";
    switch ($this->getSss('niveau'))
    {
      case 1 : $msg .= $bonneReponse->getnom().".";
               break;
      case 2 : 
      case 3 :
      case 4 :
      case 5 :
      case 6 : $msg = "";
    }
    $msg .= " " . $bonneReponse->getDescription()." " . $bonneReponse->getCommentaire();

    $this->setSss('correction', $msg);
  }
}

?>