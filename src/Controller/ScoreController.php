<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Musicien;
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
    if ($this->isSetUser()) $listeTrophees = $this->getTrophees();
    else $listeTrophees = [];
    $listeScores = $this->getScores($this->getUser()->getId());
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
                  ->getTrophees();

    // On met à "true" le champ "trophée déjà acquis" sur les lignes dont l'id est dans trophee_musicien pour cet utilisateur.
    $liste = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('App:TropheeMusicien')
                  ->completerListeTrophees($liste, $this->getUser()->getId())
                  ;

    return $liste;
  }
  
  /* Donne la liste des scores par discipline
  */
  private function getScores(int $id)
  {
    $liste = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('App:Score')
                  ->getScores($id);

    return $liste;
  }
}

?>