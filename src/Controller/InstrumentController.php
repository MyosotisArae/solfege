<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstrumentController extends ParentController
{
  /**
   * @Route("/instrument", name="instrument")
   */
  public function main()
  {
    $this->session->set('exercice', 'instrument');
    return $this->render('/instrument.html.twig');
  }
}

?>