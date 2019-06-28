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
    $this->setSss('exercice', 'score');
    $this->setSss('niveau', 0);
    $listeTrophees = $this->getTrophees();
    $listeScores = $this->getScores();
    return $this->render('/score.html.twig',["scores" => $listeScores,
                                             "trophees" => $listeTrophees]);
  }

  /**
   * @Route("/apprendre_score", name="apprendre_score")
   */
  public function apprendre()
  {
    $this->setSss('exercice', 'score');
    $listeTrophees = $this->getTrophees();
    return $this->render('/apprendre_score.html.twig',["trophees" => $listeTrophees]);
  }
  
  /* Donne la liste de tous les trophées .
   */
  private function getTrophees()
  {
    $liste = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('App:Trophee')
                  ->getListe();
    
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