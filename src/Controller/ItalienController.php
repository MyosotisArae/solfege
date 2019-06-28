<?php
namespace App\Controller;

use App\Entity\Vocabulaire;
use App\Objet\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItalienController extends ExerciceController
{
  /**
   * @Route("/italien", name="italien")
   */
  public function main1()
  {
    $this->setSss('titreExo', 'termes italiens' );
    return $this->question('italien');
  }

  /**
   * @Route("/italienCorrection{numRep}", name="italienCorrection", requirements={"numRep" = "\d+"})
   */
  public function main2($numRep)
  {
    return $this->correction('italien', $numRep);
  }

  /**
   * @Route("/apprendre_italien", name="apprendre_italien")
   */
  public function main3()
  {
    $this->getCategorie('nuance');
    $this->getCategorie('tempo');
    $this->getCategorie('expression');
    return $this->apprentissage('italien');
  }

  protected function initVocab()
  {
    switch ($this->getSss('niveau'))
    {
      case 1:
      case 4: $this->setSss('vocabulaire', $this->getCategorie('nuance') ); break;
      case 2:
      case 5: $this->setSss('vocabulaire', $this->getCategorie('tempo') ); break;
      case 3:
      case 6: $this->setSss('vocabulaire', $this->getCategorie('expression') ); break;
    }
    $this->setSss('listeIndices',$this->getIndices());
  }

  protected function initNiveau()
  {
    parent::initNiveau();
    switch ($this->getSss('niveau'))
    {
      case 1 :
      case 2 :
      case 3 :
      case 4 :
      case 5 :
      case 6 : $this->typeDeQuestion = 1; break;
    }
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
    $suite = "";
    switch ($this->getSss('niveau'))
    {
      case 2 :
      case 5 : $suite = ' Tempo '.$bonneReponse->getCommentaire().'.'; break;
    }
    switch ($this->getSss('niveau'))
    {
      case 1 : $msg .= $bonneReponse->getNom().'" qui signifie "'.$bonneReponse->getDescription().'".';
               break;
      case 4 : $msg .= $bonneReponse->getnom().'", '."c'est à dire ".'"';
      case 2 :
      case 5 :
      case 3 :
      case 6 : $msg .= $bonneReponse->getDescription().'".';
    }
    $msg .= $suite;

    $this->setSss('correction', $msg);
  }
}

?>