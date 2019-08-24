<?php
namespace App\Controller;

use App\Objet\P_constantes;
use App\Entity\Trophee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
 
class ParentController extends AbstractController
{
  private $listeDisciplines;  // Noms des disciplines (comme dans ScoreRepository)
  private $maxParDisciplines; // Donne, par discipline et par niveau, le score max
  protected $cst; // Constantes

  public function __construct(SessionInterface $session)
  {
    $this->cst = new P_constantes();
    if (!isset($this->session)) { $this->session = $session; }
    $this->setSss('nomTrophee', '');
    $this->setSss('imageTrophee', '');
    // Important : pas d'appel à doctrine dans ce constructeur.
    //$this->util = $this->get("utilitaires");
    
    $this->listeDisciplines = array("italien","instrument","tonalite","rythme");
    $this->maxParDisciplines = array(
                                    [6,6,6,6], // italien
                                    [6,6,6], // instrument
                                    [6], // tonalite
                                    [6,6,6]  // rythme
                                    );
  }

  /**
   * Donne le score maximum qui peut être obtenu pour ce niveau et cet exercice.
   */
  protected function getScoreMax(string $exercice, int $niveau)
  {
    $indiceDiscipline = array_search($exercice, $this->listeDisciplines);
    return $this->maxParDisciplines[$indiceDiscipline][$niveau-1];
  }

  /**
   * Retourne le code binaire qui correspond à la discipline :
   * 2 pour la discipline 0,
   * 4 pour la discipline 1,
   * 8 pour la discipline 2,
   * et 16 pour la discipline 3.
   * Un ET logique permettra ainsi de les identifier.
   */
  protected function getCodeBinaire(string $exercice)
  {
    $indiceDiscipline = array_search($exercice, $this->listeDisciplines);
    $code = 2;
    while ($indiceDiscipline > 0)
    {
      $code *= 2;
      $indiceDiscipline -= 1;
    }
    return $code;
  }

  public static function getSubscribedServices(): array
  {
      return array_merge(parent::getSubscribedServices(), [ // Melange du tableau des services par defaut avec les notres
          'utilitaires' => 'App\Service\Utilitaires'
      ]);
  }

  protected function reinitNiveau()
  {
    // Liste des id (dans Vocabulaire) des questions qui ont déjà été posées ce tour.
    $this->setSss('dejaDemande', array());
    // Numérotation des questions (de 1 à 6)
    $this->setSss('numQuestion', 0);
    // Numéro de la dernière question dont la réponse a été comptée (de 1 à 6)
    $this->setSss('numQuestionValidee', 0);
    // Nombre de bonnes réponses obtenues ce tour
    $this->setSss('nbBonnesRep', 0);
    $this->setSss('media', 0);
  }

  /**
   * Retourne true si cette question a déjà été validée (càd que sa réponse
   * a été comptabilisée et ajoutée au score), false sinon.
   */
  protected function dejaValidee()
  {
    $nq  = $this->getSss('numQuestion');
    $nqv = $this->getSss('numQuestionValidee');
    if ($nqv < $nq)
    {
      // Cette question n'a pas encore été validée. Mais c'est le cas, maintenant.
      $this->setSss('numQuestionValidee', $nq);
      return false;
    }
    return true;
  }
  
  protected function getNiveau()
  {
    return $this->getDoctrine()
                ->getManager()
                ->getRepository('App:Score')
                ->getNiveau($this->session->get('exercice'));
  }

  private function getFileDattenteTrophees()
  {
    $fileAttenteTrophees = $this->getSss('fileTrophees');
    if ($fileAttenteTrophees == null) $fileAttenteTrophees = array();
    return $fileAttenteTrophees;
  }
  
  protected function setProchainTrophee()
  {
    $fileAttenteTrophees = $this->getFileDattenteTrophees();
    if (count($fileAttenteTrophees) > 0)
    {
      $id = array_shift($fileAttenteTrophees);
      $this->obtentionTrophee($id);
      $this->setSss('fileTrophees', $fileAttenteTrophees);
    }
  }

  protected function obtentionTrophee(int $id)
  {
    // On ne peut afficher qu'un trophée à la fois.
    // S'il y en a déjà un de sauvegardé (nomTrophee non vide),
    // alors mettre celui-ci dans la file d'attente.
    if (strlen($this->getSss('nomTrophee')) > 0)
    {
      $fileAttenteTrophees = $this->getFileDattenteTrophees();
      $fileAttenteTrophees[] = $id;
      $this->setSss('fileTrophees', $fileAttenteTrophees);
      return;
    }

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
  
  /**
   * Contrôle d'obtention de trophées au moment où le score va être enregistré.
   * $exercice : nom de la discipline
   * $niveau   : niveau joué
   * $score    : nombre de bonnes réponses
   * $scoreMax : meilleur résultat obtenu jusqu'ici
   * Cette fonction n'est appelée QUE si le score a été amélioré (scoreMax > score)
   */
  protected function verificationTropheesOnScore(string $exercice, int $niveau, int $score, int $scoreMax)
  {
    $em = $this->getDoctrine()->getManager();

    // Ce booléen indique si le niveau en cours est réussi (score max atteint).
    $niveauReussi = ($score >= $this->getScoreMax($exercice, $niveau));

    // Trophée 100 : niveau réussi du premier coup
    // Si le score maximum enregistré est 0, on considère que le niveau n'a
    // jamais été essayé. Dans ce cas, si le score obtenu cette fois-ci est
    // 6, on accorde le trophée "niveau réussi du premier coup".
    if (($scoreMax == 0) && ($niveauReussi))
    {
      $this->obtentionTrophee(100);
    }

    // Trophée 102 : reconnaître au moins 4 instruments
    // Correspond au niveau 3 de Instruments.
    if (($score > 3) && ($exercice == "instrument") && ($niveau == 3))
    {
      $this->obtentionTrophee(102);
    }

    // Trophées 1,2,3,...10 : avoir réussi tous les niveaux 1,2,3,...10
    // Déjà, il faut que ce niveau-ci soit réussi.
    $nbNiveauxReussis = 0;
    if ($niveauReussi)
    {
      $nbNiveauxReussis = 1; // Celui-ci est réussi. Vérifions les autres.
      foreach ($this->listeDisciplines as $d)
      {
        $maxScore = $em->getRepository('App:Score')
                       ->getScoreDuNiveau($niveau, $d);
        if ($maxScore->getScore() >= $this->getScoreMax($exercice, $niveau))
        {
          // Le niveau max est atteint pour cette discipline.
          $nbNiveauxReussis += 1;
        }
      }
      if ($nbNiveauxReussis == 4) $this->obtentionTrophee($niveau);
    }

    // Trophée 104 : avoir testé toutes les disciplines
    $nbNiveauxTestes = $this->getCodeBinaire($exercice);
    foreach ($this->listeDisciplines as $d)
    {
      $maxScore = $em->getRepository('App:Score')
                     ->getScoreDuNiveau($niveau, $d);
      if ($maxScore->getScore() > 0)
      {
        $nbNiveauxTestes |= $this->getCodeBinaire($d);
      }
    }
    if ($nbNiveauxTestes == 30)
    {
      $this->obtentionTrophee(104);
      // Si un de ces niveaux au moins n'est pas encore réussi :
      if ($nbNiveauxReussis < 4)
      {
        // S'il s'agit des niveaux 8 :
        if ($niveau == 8) $this->obtentionTrophee(105);
        // S'il s'agit des niveaux 9 :
        if ($niveau == 9) $this->obtentionTrophee(106);
        // S'il s'agit des niveaux 10 :
        if ($niveau == 10) $this->obtentionTrophee(107);
      }
    }
  }
  
  /* Obtiens la liste de tous les trophées (Uniquement lors du premier appel)
   * et la met dans la variable de session listeTrophees.
   */
  private function getTrophees()
  {
    return $this->getDoctrine()
                ->getManager()
                ->getRepository('App:Trophee')
                ->getListe();
  }

  private function getTrophee(int $id)
  {
    return $this->getDoctrine()
                ->getManager()
                ->getRepository('App:Trophee')
                ->find($id);
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