<?php
namespace App\Controller;

use App\Objet\Vocabulaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItalienController extends ParentController
{
  /**
   * @Route("/italien", name="italien")
   */
  public function main()
  {
    $this->session->set('exercice', 'italien');
    $q = $this->session->get('question');
    if ($q>0) { $this->session->set('question', $q+1); }
    else { $this->session->set('question', 1); }
    $this->initVocab();
    return $this->render('/italien.html.twig',["niveau" => $this->getNiveau(), "question" => $this->getQuestion()]);
  }

  private function getNiveau()
  {
    return $this->getDoctrine()
                ->getManager()
                ->getRepository('App:Score')
                ->getNiveau($this->session->get('exercice'));
  }

  private function initVocab()
  {
    $this->Vocab = array
    (
      new Vocabulaire("pianississimo","très très doux","pianississimo","son particulièrement faible, à la limite d'exécution pour les instruments à vent"),
      new Vocabulaire("pianissimo","très doux","pianissimo","son très faible, difficile à exécuter, surtout dans les aigus"),
      new Vocabulaire("piano","doux","piano","son faible"),
      new Vocabulaire("mezzo piano","moyennement doux","mezzo_piano","niveau sonore légèrement relâché"),
      new Vocabulaire("mezzo forte","moyennement fort","mezzo_forte","nuance naturelle pour tous les instruments"),
      new Vocabulaire("forte","fort","forte","le son demande un peu d'énergie"),
      new Vocabulaire("fortissimo","très fort","fortissimo","exécuté avec violence, sans pour autant dénaturer le son"),
      new Vocabulaire("fortississimo","très très fort","fortississimo", "utilisé exceptionnellement pour un effet d'orchestre")
    );
  }

  private function getQuestion()
  {
    if ($this->session->get('question') < count($this->Vocab))
    {
      return $this->Vocab[$this->session->get('question')];
    }
  }
}

?>