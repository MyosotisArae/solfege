<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RythmeController extends ParentController
{
  /**
   * @Route("/rythme", name="rythme")
   */
  public function main()
  {
    $_SESSION['exercice'] = 'rythme';
    return $this->render('/rythme.html.twig',["session" => $_SESSION]);
  }
}

?>