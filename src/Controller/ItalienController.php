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
    return $this->apprentissage('italien',
                                [
                                  'categorie_nuance' => $this->getCategorie('nuance'),
                                  'categorie_tempo' => $this->getCategorie('tempo'),
                                  'categorie_expression' => $this->getCategorie('expression')
                                ]);
  }

  /**
   * Cette fonction récupère la catégorie du niveau en cours dans la table Vocabulaire.
   * @return array d'objets Vocabulaire
   */
  protected function getVocalulaire()
  {
    switch ($this->getSss('niveau'))
    {
      case 1:
      case 4: return $this->getCategorie('nuance'); 
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
      case 3 : $this->setSss('modele', 'QCM_nom'); break;
      case 4 : $this->setSss('modele', 'QCM_commentaire1'); break;
      case 5 ://à faire
      case 6 : $this->setSss('modele', 'QCM_nom'); break;
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
      case 1 : $msg .= $bonneReponse->getNom().'" qui signifie "'.$bonneReponse->getDescription().'". '.ucfirst($bonneReponse->getCommentaire());
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