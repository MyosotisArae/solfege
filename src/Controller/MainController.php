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
    // S'il y a au moins un trophée dans la liste d'attente,
    // afficher le prochain.
    $this->setProchainTrophee();

    $this->reinitNiveau();
    return $this->render('/main.html.twig');
  }
  /**
   * @Route("/lexique", name="lexique")
   */
  public function lexique()
  {
    $this->setSss('exercice', 'lexique');
    return $this->render('/lexique.html.twig');
  }
}

?>