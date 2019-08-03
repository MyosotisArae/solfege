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
    if (!isset($this->session)) { $this->session = $session; }
    $this->setSss('nomTrophee', '');
    $this->setSss('imageTrophee', '');
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

  protected function obtentionTrophee(int $id)
  {
    $trophee = $this->getTrophee($id);
    if ($trophee->dejaObtenu()) return false;

    // Enregistrer l'obtention de ce trophée.
    $trophee->setObtenu(1);
    $em = $this->getDoctrine()->getManager();
    $em->flush();

    // Afficher le trophée.
    $this->setSss('nomTrophee', $trophee->getNom());
    $this->setSss('imageTrophee', $trophee->getImage());

    return true;
  }
  
  private function getTrophee(int $id)
  {
    foreach ($this->getSss('listeTrophees') as $trophee)
    {
      if ($trophee->getId() == $id)
      {
        if ($trophee->dejaObtenu()) return $trophee;
      }
    }
    $em = $this->getDoctrine()->getManager();
    return $em->getRepository('App:Trophee')->find($id);
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
    //if (!$this->isSetSss('categorie_'.$cat))
    if (true)
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