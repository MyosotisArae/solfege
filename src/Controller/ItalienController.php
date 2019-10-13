<?php
namespace App\Controller;

use App\Entity\Vocabulaire;
use App\Objet\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
   * @Route("/italienCorrectionForm", name="italienCorrectionForm")
   */
  public function main4(Request $request)
  {
    if ($request->getMethod() == Request::METHOD_POST){
      $texte = $request->request->get('texteFormulaire');
      return $this->correction('italien', $texte);
    }
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

  protected function testReponseFournie(Question $question, $reponseFournie)
  {
    if ($this->getSss('niveau') < 5) return parent::testReponseFournie($question, $reponseFournie);

    return ($reponseFournie == "12345678");
  }

  /**
   * Cette fonction récupère la catégorie du niveau en cours dans la table Vocabulaire.
   * @return array d'objets Vocabulaire
   */
  protected function getVocabulaire()
  {
    switch ($this->getSss('niveau'))
    {
      case 1:
      case 4:
      case 5: $this->setSss('valeurParDefaut', 'aaaaaaaa');
              return $this->getCategorie('nuance'); 
      case 2: return $this->getCategorie('tempo');
      case 3:
      case 6: return $this->getCategorie('expression');
    }
  }

  protected function setLibelleQuestion(Vocabulaire $bonneReponse)
  {
    switch ($this->getSss('niveau'))
    {
      case 1: $bonneReponse->setTexteQuestion("Que représente ce symbole ?");
              break;
      case 2: 
      case 3:
      case 6: $bonneReponse->setTexteQuestion("Quelle est la signification de ".$bonneReponse->getNom(). "?");
              break;
      case 4: $bonneReponse->setTexteQuestion("Lis la définition donnée ci-dessous et trouve à quel symbole elle correspond.");
              break;
      case 5: $bonneReponse->setTexteQuestion("Numérote ces nuances de la plus faible (1) à la plus forte (8).");
              break;
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
      case 6 : $this->setSss('modele', 'QCM_nom'); break;
      case 4 : $this->setSss('modele', 'QCM_commentaire1'); break;
      case 5 : $this->setSss('modele', 'QCM_ordre'); break;
    }
  }

  protected function addFaussesReponses(Question $question, Vocabulaire $bonneReponse, array $tableau = null)
  {
    if ($this->getSss('niveau') < 5) { return parent::addFaussesReponses($question,$bonneReponse,$tableau); }

    // Niveau 5 :
    $nuances = $this->getCategorie('nuance');
    $propositions = array();
    foreach ($nuances as $n)
    {
      if ($n->getOrdre() > 0) { $question->addProposition($n); }
    }
    $question->melangerPropositions();
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