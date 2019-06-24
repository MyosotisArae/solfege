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
    $this->session->set('exercice', 'rythme');
    return $this->render('/rythme.html.twig');
  }
}

?>