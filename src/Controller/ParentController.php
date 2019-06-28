<?php
namespace App\Controller;

use App\Entity\Trophee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
 
class ParentController extends AbstractController
{
  public function __construct(SessionInterface $session)
  {
    $this->session = $session;
    //$this->util = $this->get("utilitaires");
  }

  public static function getSubscribedServices(): array
  {
      return array_merge(parent::getSubscribedServices(), [ // Melange du tableau des services par defaut avec les notres
          'utilitaires' => 'App\Service\Utilitaires'
      ]);
  }

  protected function getNiveau()
  {
    return $this->getDoctrine()
                ->getManager()
                ->getRepository('App:Score')
                ->getNiveau($this->session->get('exercice'));
  }

  protected function saveScore(int $niveau, int $score)
  {
      $em = $this->getDoctrine()
                 ->getManager();
      // Comparaison avec le meilleur score enregistré.
      $maxScore = $em->getRepository('App:Score')
                     ->getScoreDuNiveau($niveau, $this->session->get('exercice'));
      if ($maxScore->getScore() >= $score) return false;

      $maxScore->setScore($score);
      $em->persist($maxScore);
      $em->flush();

      return true;
  }

  protected function obtentionTrophee(int $id)
  {
    $trophee = $this->getTrophee($id);
    if ($trophee->obtenu) return false;

    $em = $this->getDoctrine()->getManager();
    $trophee->setObtenu(true);
    $em->persist($trophee);
    $em->flush();

    return true;
  }
  
  private function getTrophee(int $id)
  {
    foreach ($this->getSss('listeTrophees') as $trophee)
    {
      if ($trophee->id == $id) return $trophee;
    }
  }
  
  ////////////////////////////////////////////////////////////////////
  // Fonctions permettant de :                                      //
  // - vérifier si une variable de session existe et est non vide   //
  // - lire une variable de session                                 //
  ////////////////////////////////////////////////////////////////////
  
  public function isSetSss(string $nomVar)
  {
    if ($this->session)
    {
      if ($this->session->get($nomVar))
      {
        if ($this->session->get($nomVar) == null) return false;
        if ($this->session->get($nomVar) == '') return false;
        return true;
      }
    }
    return false;
  }
  
  public function getSss(string $nomVar)
  {
    if ($this->isSetSss($nomVar))
    {
      return $this->session->get($nomVar);
    }
    return null;
  }
  
  public function setSss(string $nomVar, $valeur)
  {
    $this->session->set($nomVar, $valeur);
  }

  protected function getCategorie(string $cat)
  {
    if (!$this->isSetSss('categorie_'.$cat))
    {
      $this->setSss('categorie_'.$cat,
        $this->getDoctrine()
             ->getManager()
             ->getRepository('App:Vocabulaire')
             ->getCategorie($cat) );
    }
    return $this->getSss('categorie_'.$cat);
  }
}
?>