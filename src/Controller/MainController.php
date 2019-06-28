<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends ParentController
{
  /**
   * @Route("/main", name="main")
   */
  public function main()
  {
    $this->setSss('exercice', 'main');
    $this->setSss('numQuestion', 0);
    $this->setSss('nbBonnesRep', 0);
    $this->getTrophees();
    return $this->render('/main.html.twig');
  }
  
  /* Obtiens la liste de tous les trophées (Uniquement lors du premier appel)
   * et la met dans la variable de session listeTrophees.
   */
  private function getTrophees()
  {
    if ($this->isSetSss('listeTrophees')) return false;

    $this->setSss('listeTrophees',
      $this->getDoctrine()
           ->getManager()
           ->getRepository('App:Trophee')
           ->getListe() );
  }
}

?>