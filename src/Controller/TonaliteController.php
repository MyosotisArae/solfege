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
    $this->getCategorie('nuance');
    return $this->apprentissage('tonalite');
  }

  protected function initVocab()
  {
    switch ($this->getSss('niveau'))
    {
      case 1: $this->constructionDesReponsesNiv1(); break;
      case 4: 
      case 2:
      case 5: $this->setSss('vocabulaire', $this->getCategorie('tempo') ); break;
      case 3:
      case 6: $this->setSss('vocabulaire', $this->getCategorie('expression') ); break;
    }
    $this->setSss('listeIndices',$this->getIndices());
  }

  /* Cette fonction crée une liste de questions et réponses pour le niveau 1
   */
  private function constructionDesReponsesNiv1()
  {
    $listeDeReponses = array();
    // En premier, la bonne reponse :
    $reponse = new Vocabulaire("dièse","Hausse la note d'un demi ton","diese","Hausse la note d'un demi ton");
    $listeDeReponses[] = $reponse;
    // Ensuite, les fausses reponses :
    $reponse = new Vocabulaire("bémol","Baisse la note d'un demi ton","","Baisse la note d'un demi ton");
    $listeDeReponses[] = $reponse;
    $reponse = new Vocabulaire("bécarre","Supprime toute altération sur cette note","","Supprime toute altération sur cette note");
    $listeDeReponses[] = $reponse;
    $reponse = new Vocabulaire("accent","Indique qu'il faut jouer cette note plus fort","","Indique qu'il faut jouer cette note plus fort");
    $listeDeReponses[] = $reponse;
    $reponse = new Vocabulaire("point d'orgue","Indique que cette note doit être prolongée","","Indique que cette note doit être prolongée");
    $listeDeReponses[] = $reponse;
    $reponse = new Vocabulaire("presto","Indique qu'il faut jouer cette note rapidement","","Indique qu'il faut jouer cette note rapidement");
    $listeDeReponses[] = $reponse;
    $this->setSss('vocabulaire', $listeDeReponses);
    $this->setSss('listeIndices', range(0,count($listeDeReponses) - 1));
  }

  protected function getQuestion()
  {
    $this->constructionDesReponsesNiv1();
    $listeIndices = $this->getSss('listeIndices');
    $reponse = array_shift($listeIndices);
    $question = new Question();
    $question->addReponse($this->getSss('vocabulaire')[$reponse]);
    $this->setSss('listeIndices',$listeIndices);

    // Niveau 7 et plus : plus besoin de liste de fausses réponses.
    //if ($this->getSss('niveau') < 7) $this->addFaussesReponses($question, $reponse);
    
    $this->setSss('question',$question);
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
    /*
    if (substr($msg,0,3) == 'Oui') { $msg = "Oui, en effet, "; }
    else  { $msg = "Non. En fait,  "; }
    switch ($this->getSss('niveau'))
    {
      case 1 : $msg .= "ce silence équivaut à ".$bonneReponse->getOrdre()." croches.";
               break;
      case 2 : 
      case 3 :
      case 4 :
      case 5 :
      case 6 : $msg = "";
    }
    $msg .= " Donc, " . $bonneReponse->getCommentaire();
    */

    $this->setSss('correction', $msg);
  }
}

?>