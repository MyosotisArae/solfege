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

  protected function reinitNiveau()
  {
    $this->setSss('dejaDemande', array());
    $this->setSss('numQuestion', 0);
    $this->setSss('nbBonnesRep', 0);
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
  
  /**
   * Cette fonction détermine s'il existe ou non un objet valide nommé nomVar
   * dans une variable de session.
   * Correction du 04/08/2019 :
   * - un objet de type int valant 0 n'est pas null
   * - un objet de type array qui est vide n'est pas null
   * - un objet de type string qui est vide n'est pas null
   *
   * @return false si l'objet est null ou n'existe pas, true sinon
   */
  public function isSetSss(string $nomVar)
  {
    if ($this->session)
    {
      $x = $this->session->get($nomVar);
      if (is_int($x))
      {
        if ($x == 0) return true;
        else if ($x == null) return false;
        return true;
      }
      if (is_array($x))
      {
        if ($x == array()) return true;
        else if ($x == null) return false;
        return true;
      }
      if (is_string($x))
      {
        if ($x == '') return true;
        else if ($x == null) return false;
        return true;
      }
      if ($this->session->get($nomVar) == null) return false;
      return true;
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
    return $this->getDoctrine()
                ->getManager()
                ->getRepository('App:Vocabulaire')
                ->getCategorie($cat);
  }
}
?>