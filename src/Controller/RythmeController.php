<?php
namespace App\Controller;

use App\Entity\Vocabulaire;
use App\Objet\Question;
use App\Objet\Portee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RythmeController extends ExerciceController
{
  /**
   * @Route("/rythme", name="rythme")
   */
  public function main1()
  {
    $this->setSss('titreExo', 'rythme' );
    return $this->question('rythme');
  }

  /**
   * @Route("/rythmeCorrection{numRep}", name="rythmeCorrection", requirements={"numRep" = "\d+"})
   */
  public function main2($numRep)
  {
    return $this->correction('rythme', $numRep);
  }

  /**
   * @Route("/apprendre_rythme", name="apprendre_rythme")
   */
  public function main3()
  {
    return $this->apprentissage('rythme');
  }

  /**
   * Cette fonction récupère la catégorie du niveau en cours dans la table Vocabulaire.
   * @return array d'objets Vocabulaire
   */
  protected function getVocabulaire()
  {
    switch ($this->getSss('niveau'))
    {
      case 1: return $this->constructionDesReponsesNiv1();
      case 2: 
      case 3: $this->setSss('afficherNom', '1');
              return $this->getCategorie("chiffrage");
      case 4:
      case 5: return $this->getCategorie('tempo');
      case 6: return $this->getCategorie('expression');
    }
  }

  protected function constructionDesReponsesNiv1()
  {
    $listeDeReponses = $this->getCategorie('silence');
    // Dans la liste ainsi récupérée, ce qui nous intéresse est :
    // - la duree du silence
    //   en nombre de croches  : ordre
    // - son nom               : nom
    // - sa description        : description
    // - son image (sans point):symbole
    // Les propositions contiendront toutes les silences trouvés en base.
    // Pour chacune d'elles, on va construire une portée contenant des
    // notes, et une autre contenant des silences.
    $resultat = array();
    foreach ($listeDeReponses as $rep)
    {
      $ps = new Portee("");
      $ps->addSilence($rep->getSymbole(), $rep->getOrdre());
      $rep->setPortee1($ps);
      // Ajouter la note de même durée que ce silence
      $pn = new Portee("");
      $nomNote = $this->cst->getLettreRandom().strval(random_int(2,3));
      $pn->addNote($nomNote, "", $rep->getOrdre());
      $rep->setPortee2($pn);
      $resultat[] = $rep;
    }
    return $resultat;
  }
  
  /* Retourne un texte retournant la durée du temps fourni.
   * $duree : exprimée en nombre de croches.
   */ 
  private function creerCommentaire($tableau)
  {
    $typeFigure = array( 1 => 'croche',
                         2 => 'noire',
                         4 => 'blanche',
                         8 => 'ronde'
                       );
    $txt = "";
    // On va regrouper les notes identiques.
    // Ex : si on a 1,1,2,1 (croche, croche, noire, croche),
    // on stockera 1,2,1 (croche, noire, croche) dans listeDesFiguresDeNotes
    // et 2,1,1 dans nombreDeNotes (2 croches, 1 noire, 1 croche)
    $dureePrecedente = $tableau[0];
    $listeDesFiguresDeNotes = [$dureePrecedente];
    $nombreDeNotes = [1];
    $i = 1;
    while ($i < count($tableau))
    {
      $duree = $tableau[$i];
      if ($duree == $dureePrecedente)
      {
        $le_dernier = array_pop($nombreDeNotes);
        $nombreDeNotes[] = $le_dernier + 1;
      }
      else
      {
        $listeDesFiguresDeNotes[] = $duree;
        $nombreDeNotes[] = 1;
      }
      $i += 1;
      $dureePrecedente = $duree;
    }
    // On peut maintenant créer le commentaire.
    $nb = count($nombreDeNotes); // nombreDeNotes et listeDesFiguresDeNotes ont le même cardinal.
    $i = 0;
    $texte = "";
    $virgule = "";
    while ($i < $nb)
    {
      $texte .= $virgule . $nombreDeNotes[$i] . " " . $typeFigure[$listeDesFiguresDeNotes[$i]];
      if ($nombreDeNotes[$i] > 1) $texte .= "s";
      $i += 1;
      $virgule = ", ";
    }
    $texte = "Il fallait choisir la réponse : " . $texte . ".";
    return $texte;
  }

  protected function addFaussesReponses(Question $question, Vocabulaire $bonneReponse, array $tableau=null)
  {
    switch ($this->getSss('niveau'))
    {
      case 1 : parent::addFaussesReponses($question,$bonneReponse,$tableau); break;
      case 2 : $this->addFaussesReponsesNiv2($question,$bonneReponse); break;
      case 3 : $this->addFaussesReponsesNiv3($question,$bonneReponse); break;
      case 4 :
      case 5 :
      case 6 :
    }
  }

  protected function addFaussesReponsesNiv2(Question $question, Vocabulaire $bonneReponse)
  {
    $nbCrochesBonneReponse = $bonneReponse->getOrdre();
    $bonneReponse->setSymbole("../portee/".$bonneReponse->getSymbole());
    $question->addReponse($bonneReponse);
    // Toutes les durees :
    $dureesEnCroches = [2,4,6,8,9,12];
    while (count($dureesEnCroches) > 0)
    {
      $nbCroches = array_shift($dureesEnCroches);
      $reponse = new Vocabulaire($this->getDureeMesure($nbCroches));
      $reponse->setOrdre($nbCroches);
      if ($nbCroches == $nbCrochesBonneReponse) $reponse->setId($bonneReponse->getId());
      $question->addProposition($reponse);
    }
    $question->melangerPropositions();
  }
  
  protected function addFaussesReponsesNiv3(Question $question, Vocabulaire $bonneReponse, array $tableau=null)
  {
    // Deux réponses possibles : binaire ou ternaire
    // 1. Créer une portée pour la bonne reponse
    $nbCrochesBonneReponse = $bonneReponse->getOrdre();
    $pn = new Portee($this->cst->get_cle_sol());   // La clé,
    $pn->addSigne($bonneReponse->getSymbole());    // le chiffrage,
    $this->addMesure($nbCrochesBonneReponse, $pn); // les notes d'une mesure
    $pn->addSigne("barre_mesure");                 // une barre de mesure,
    $this->addMesure($nbCrochesBonneReponse, $pn); // une autre mesure
    $pn->addSigne("barre_mesure");                 // une barre de mesure,
    $this->addMesure($nbCrochesBonneReponse, $pn); // une dernière mesure
    $pn->addSigne("barre_mesure_fin");             // une barre de mesure de fin.
    $bonneReponse->setPortee1($pn);
    $question->setReponse($bonneReponse);

    // 2. Deux réponses possibles
    $autreRep = new Vocabulaire();
    if ($bonneReponse->getNom() == "binaire") $autreRep->setNom("ternaire");
    else $autreRep->setNom("binaire");
    $question->addProposition($bonneReponse);
    $question->addProposition($autreRep);
    $question->melangerPropositions();
  }

  private function addMesure(int $nbCrochesBonneReponse, Portee $p)
  {
    $nbCroches = $nbCrochesBonneReponse;
    $dureesPossibles=[1,2,3,4,6,8]; // croche, noire, noire pointée, blanche, blanche pointée et ronde.
    while ($nbCroches > 0)
    {
      $dureeMax = min(8,$nbCroches);
      $duree = $dureesPossibles[random_int(0, count($dureesPossibles)-1)];
      // S'il n'est plus possible de caser cette durée dans ce qu'il reste de la mesure, reboucler.
      if ($duree > $nbCroches) continue;
      $nomNote = $this->cst->getLettreRandom().strval(random_int(2,3));
      $p->addNote($nomNote, "", $duree);
      $nbCroches -= $duree;
    }
  }

  private function getDureeMesure(int $nbCroches)
  {
    // Dans certains cas, ce nb de croches de croches peut être exprimé en noires, voire en blanches.
    // Pour diversifier les résultats, on va appliquer les statistiques suivantes:
    // - 40% de chances d'exprimer la durée en blanches si c'est possible.
    // - 40% de chances de l'exprimer en noires si c'est possible, 60% si on ne peut pas l'exprimer en blanches.
    // - on l'exprime en croches si on n'est pas tombé sur un des cas précédents.
    $stat = random_int(0,100);
    $nbNoires = intval($nbCroches/2);
    // Cas 1 : On peut l'exprimer en blanches.
    if ($nbCroches%4 == 0)
    {
      $nbBlanches = intval($nbCroches/4);
      if ($stat < 40)
      {
         $reponse = $nbBlanches." blanche";
         if ($nbBlanches > 1) $reponse .= "s";
         return $reponse;
      }
      else if ($stat < 80)
      {
         $reponse = $nbNoires." noire";
         if ($nbNoires > 1) $reponse .= "s";
         return $reponse;
      }
    }
    else
    // Cas 2 : On peut l'exprimer en noires, mais pas en blanches.
    if ($nbCroches%2 == 0)
    {
      if ($stat < 60)
      {
         $reponse = $nbNoires." noire";
         if ($nbNoires > 1) $reponse .= "s";
         return $reponse;
      }
    }
    return $nbCroches." croches";
  }

  protected function setLibelleQuestion(Vocabulaire $bonneReponse)
  {
    switch ($this->getSss('niveau'))
    {
      case 1: $bonneReponse->setTexteQuestion("Trouve la note qui a la même durée que ce silence :");
              break;
      case 2:  $bonneReponse->setTexteQuestion("Indique quelles séries de notes on peut mettre dans ce type de mesure :");
              break;
      case 3: $bonneReponse->setTexteQuestion("Quelle est la pulsation ?");
              break;
      case 4: $bonneReponse->setTexteQuestion("Lis la définition donnée ci-dessous et trouve à quel symbole elle correspond.");
              break;
      case 5: $bonneReponse->setTexteQuestion("Numérote ces nuances de la plus faible (1) à la plus forte (8).");
              break;
      case 6: $bonneReponse->setTexteQuestion("Quelle est la signification de ".$bonneReponse->getNom(). "?");
              break;
    }
  }
  
  protected function initNiveau()
  {
    parent::initNiveau();
    switch ($this->getSss('niveau'))
    {
      case 1 :
      case 3 : $this->setSss('modele', 'QCM_portee'); break;
      case 2 : $this->setSss('modele', 'QCM_symbole'); break;
      case 4 : $this->setSss('modele', 'QCM_commentaire1'); break;
      case 5 :
      case 6 : $this->setSss('modele', 'QCM_nom'); break;
    }
  }

  protected function complementCorrection(Vocabulaire $bonneReponse)
  {
    $msg = $this->getSss('correction');
    if (substr($msg,0,3) == 'Oui') { $msg = "Oui. "; }
    else  { $msg = "Non. "; }
    switch ($this->getSss('niveau'))
    {
      case 1 : $msg .= "Ce silence vaut ".$bonneReponse->getOrdre()." croche";
               if ($bonneReponse->getOrdre() > 1) $msg .= "s";
               $msg .= " (" . $bonneReponse->getCommentaire().").";
               break;
      case 2 :
      case 3 : $msg .= "  ".$bonneReponse->getCommentaire(). "  ".$bonneReponse->getDescription()."  La pulsation se divise en "
                       .$this->calculSubdivisionPulsation($bonneReponse->getCommentaire())
                       .", c'est donc une mesure ".$bonneReponse->getNom().".";
               break;
      case 4 :
      case 5 :
      case 6 : $msg = "";
    }

    $this->setSss('correction', $msg);
  }

  private function calculSubdivisionPulsation($commentaire)
  {
    $txt = "";
    $pulsations  = array('blanche','noire pointée','noire');
    $subDivision = array('2 noires','3 croches', '2 croches');
    $indice = 0;
    foreach ($pulsations as $p)
    {
      if (strpos($commentaire,$p) !== false)
      {
        return $subDivision[$indice];
        break;
      }
      $indice += 1;
    }
    return " [erreur - réponse inconnue] ";
  }
}

?>