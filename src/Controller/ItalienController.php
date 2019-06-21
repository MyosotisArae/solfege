<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItalienController extends ParentController
{
  /**
   * @Route("/italien", name="italien")
   */
  public function main()
  {
    $_SESSION['exercice'] = 'italien';
    return $this->render('/italien.html.twig',["session" => $_SESSION]);
  }

  private function getNiveau()
  {
    $liste = $this->getDoctrine()
        ->getManager()
        ->getRepository('App:Score')
        ->getNiveau($_SESSION['exercice']);
    
    return $liste;
  }
}

?>