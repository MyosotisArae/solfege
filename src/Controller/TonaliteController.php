<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TonaliteController extends ParentController
{
  /**
   * @Route("/tonalite", name="tonalite")
   */
  public function main()
  {
    $this->session->set('exercice', 'tonalite');
    return $this->render('/tonalite.html.twig');
  }
}

?>