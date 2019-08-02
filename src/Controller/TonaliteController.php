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
  public function main1()
  {
    $this->setSss('titreExo', 'les tonalités' );
    return $this->question('tonalite');
  }

  /**
   * @Route("/tonaliteCorrection{numRep}", name="tonaliteCorrection", requirements={"numRep" = "\d+"})
   */
  public function main2($numRep)
  {
    return $this->correction('tonalite', $numRep);
  }

  /**
   * @Route("/apprendre_tonalite", name="apprendre_tonalite")
   */
  public function main3()
  {
    $this->getCategorie('nuance');
    return $this->apprentissage('tonalite');
  }
}

?>