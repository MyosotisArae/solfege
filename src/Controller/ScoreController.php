<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trophee;

class ScoreController extends ParentController
{
  /**
   * @Route("/score", name="score")
   */
  public function main()
  {
    $this->session->set('exercice', 'score');
    $listeTrophees = $this->getTrophees(true);
    $listeScores = $this->getScores();
    return $this->render('/score.html.twig',["scores" => $listeScores,
                                             "trophees" => $listeTrophees]);
  }
  /**
   * @Route("/apprendre_score", name="apprendre_score")
   */
  public function apprendre()
  {
    $this->session->set('exercice', 'score');
    $listeTrophees = $this->getTrophees(false);
    return $this->render('/apprendre_score.html.twig',["trophees" => $listeTrophees]);
  }
  
  /* Donne la liste de tous les trophées si $obtenus vaut false,
   * et seulement de ceux obtenus si $obtenus vaut true.
   */
  private function getTrophees($obtenus)
  {
    $liste = $this->getDoctrine()
        ->getManager()
        ->getRepository('App:Trophee')
        ->getListe($obtenus);
    
    return $liste;
  }
  
  /* Donne la liste des scores par discipline
  */
  private function getScores()
  {
    $liste = $this->getDoctrine()
        ->getManager()
        ->getRepository('App:Score')
        ->getListe();
    
    return $liste;
  }
}

?>