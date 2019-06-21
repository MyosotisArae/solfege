<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApprendreController extends ParentController
{
  /**
   * @Route("/apprendre", name="apprendre")
   */
  public function main()
  {
    $_SESSION['exercice'] = 'score';
    return $this->render('/apprendre.html.twig',["session" => $_SESSION]);
  }
}

?>