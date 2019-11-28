<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trophee;

class MainController extends ParentController
{
  /**
   * @Route("/", name="parDefaut")
   */
  public function parDefaut()
  {
    return $this->main();
  }

  /**
   * @Route("/main", name="main")
   */
  public function main()
  {
    $user = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('App:Musicien')
                 ->find($this->cst->getIdMusicien());
    $this->setSss('musicien', $user);

    $this->setSss('exercice', 'main');
    // S'il y a au moins un trophée dans la liste d'attente,
    // afficher le prochain.
    $this->setProchainTrophee();

    $this->reinitNiveau();

    // Faut-il afficher le niveau Révision ?
    $trophee = $this->getTrophee(5);
    $niveauRevision = 0;
    if ($trophee->dejaObtenu()) $niveauRevision = 1;

    return $this->render('/main.html.twig', array('revision'=> $niveauRevision));
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
