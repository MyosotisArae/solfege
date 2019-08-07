<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChoixController extends ExerciceController
{
  /**
   * @Route("/choix{nom}_{titre}", name="Choix")
   */
  public function main($nom, $titre)
  {
    return $this->render('/choix.html.twig', ["nom" => $nom, "titre" => $titre]);
  }

}

?>