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
    return $this->render('/main.html.twig',["session" => $_SESSION]);
  }
}

?>