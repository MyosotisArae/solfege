<?php
namespace App\Controller;

use App\Entity\Vocabulaire;
use App\Objet\Question;
use App\Objet\Portee;
use App\Objet\P_Image;
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
  protected function getVocalulaire()
  {
    switch ($this->getSss('niveau'))
    {
      case 1: return $this->constructionDesReponsesNiv1();
      case 4:
      case 2:
      case 5: return $this->getCategorie('tempo');
      case 3:
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
    // Les propositions contiendront tous les silences trouvés en base.
    // Pour chacune d'elles, on va construire une portée contenant des
    // notes, et une autre contenant des silences.
    $resultat = array();
    $cst = P_image::getInstance();
    foreach ($listeDeReponses as $rep)
    {
      $ps = new Portee("");
      $ps->addSilence($rep->getSymbole(), $rep->getOrdre());
      $rep->setPorteeSilence($ps);
      // Ajouter la note de même durée que ce silence
      $pn = new Portee("");
      $nomNote = $cst->getLettreRandom().strval(random_int(2,3));
      $pn->addNote($nomNote, "", $rep->getOrdre());
      $rep->setPorteeNote($pn);
      $resultat[] = $rep;
    }
    return $resultat;
  }
/*
  protected function addFaussesReponses(Question $question, Vocabulaire $bonneReponse, array $tableau=null)
  {
    // Prendre les réponses fausses qui ont été choisies pour cette réponse.
    $question->setPropositions($t);
    // Ajouter la bonne réponse à cette liste, NON ! elle y est déjà
    $question->addProposition($bonneReponse);
    print_r("  ".count($question->getPropositions())." resultats obtenus.  ");
    // et mélanger le tout.
    $question->melangerPropositions();
  }
*/
  /* Cette fonction récupère en base la liste des silences
   * et remplit le champ description avec une valeur exprimée
   * en figures de notes (croches, noires...)
   * A CORRIGER :
   * - utiliser Portee
   * - retourner à chaque fois les 6 reponses :
   *   - croche
   *   - noire
   *   - noire pointée
   *   - blanche
   *   - blanche pointée
   *   - ronde
   */
  private function constructionDesReponsesNiv2()
  {
    $figures = array
    (
      // Figures correspondant à une croche:
      1 => array( 0 => '|',
                  1 => 'A',
                  2 => 'Z',
                  3 => 'S',
                  4 => 'D',
                  5 => 'F',
                  6 => 'G',
                  7 => 'H',
                  8 => 'J',
                  9 => 'I',
                  10=> 'K',
                  11=> 'O',
                  12=> 'L',
                  13=> 'P',
                  14=> ':',
                  15=> '{',
                  16=> '}',
                  17=> '|'
                ),
      // Figures correspondant à une noire:
      2 => array( 0 => '&',
                  1 => 'Q',
                  2 => 'E',
                  3 => 'R',
                  4 => 'T',
                  5 => 'Y',
                  6 => '(',
                  7 => ')',
                  8 => 'U',
                  9 => '_',
                  10=> '^',
                  11=> '%',
                  12=> '+',
                  13=> '$',
                  14=> '*',
                  15=> 'W',
                  16=> '#',
                  17=> '@'
                ),
      3 => array(),
      // Figures correspondant à une blanche:
      4 => array( 0 => '~',
                  1 => "'",
                  2 => 'z',
                  3 => 'h',
                  4 => 'j',
                  5 => 'k',
                  6 => 'l',
                  7 => 'm',
                  8 => 'x',
                  9 => 'c',
                  10=> 'v',
                  11=> 'b',
                  12=> 'n',
                  13=> ',',
                  14=> ';',
                  15=> '!',
                  16=> ',',
                  17=> '.',
                  18=> '/'
                ),
      5 => array(),
      6 => array(),
      7 => array(),
    // Figures correspondant à une ronde:
      8 => array( 0 => '\\',
                  1 => 'a',
                  2 => 'e',
                  3 => 'r',
                  4 => 't',
                  5 => 'y',
                  6 => 'u',
                  7 => 'i',
                  8 => 'o',
                  9 => 'p',
                  10=> 'q',
                  11=> 's',
                  12=> 'd',
                  13=> 'f',
                  14=> 'g',
                  15=> 'w',
                  16=> '[',
                  17=> ']'
                )
    );
    $listeDeReponses = $this->getCategorie('silence');
    // Pour chaque silence présent dans cette liste,
    // fabriquer une liste de notes (caractères Lassus) de même durée.
    $i = 0;
    foreach ($listeDeReponses as $silence)
    {
      // Ce tableau contiendra la liste des notes ajoutées à la description,
      // sous la forme d'un entier (1=croche, 2=noire...). Il servira à formuler
      // la phrase de correction, un texte stocké dans commentaire.
      $commentaireCorrection = [];
      $duree = $silence->getOrdre();
      $silence->setCommentaire("ce silence dure ".$this->getDureeSimple($duree).'.');
      $figure = "";
      while ($duree > 0)
      {
        // Tirer au hasard une duree égale ou inférieure à celle de ce silence.
        $d = random_int(1,$duree);
        // Tirer au hasard une figure de notes parmi celles de cette durée, s'il y en a,
        // et l'ajouter à la liste de figures représentant ce silence.
        if (count($figures[$d]) > 0)
        {
          $i = random_int(0,count($figures[$d])-1);
          $figure .= $figures[$d][$i];
          $duree -= $d;
          $commentaireCorrection[] = $d;
        }
      }
      $silence->setDescription($figure);
      $silence->setCommentaire($this->creerCommentaire($commentaireCorrection));
    }
    // Création du commentaire (affiché lors de la correction)
    //$bonneReponse->setCommentaire($this->creerCommentaire($commentaireCorrection));
    //$question->setReponse($bonneReponse);
    $listeDeReponses[$i] = $silence;
    $i += 1;
    return $listeDeReponses;
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
  
  /* Retourne un texte retournant la durée du temps fourni.
   * $duree : exprimée en nombre de croches.
   */ 
  private function getDureeSimple(int $duree)
  {
    switch ($duree)
    {
      case 1 : return "une croche";
      case 2 : return "une noire";
      case 3 : return "trois croches";
      case 4 : return "une blanche";
      case 5 : return "cinq croches";
      case 6 : return "trois noires, soit une blanche pointée";
      case 7 : return "sept croches";
      case 8 : return "une ronde";
      default : return "";
    }
  }

  protected function initNiveau()
  {
    parent::initNiveau();
    switch ($this->getSss('niveau'))
    {
      case 1 : $this->setSss('modele', 'QCM_Portee'); break;
      case 2 :
      case 3 :
      case 5 :
      case 6 : $this->setSss('modele', 'QCM_nom'); break;
      case 4 : $this->setSss('modele', 'QCM_description'); break;
    }
  }

  protected function complementCorrection(Vocabulaire $bonneReponse)
  {
    $msg = $this->getSss('correction');
    if (substr($msg,0,3) == 'Oui') { $msg = "Oui, "; }
    else  { $msg = "Non. En fait,  "; }
    switch ($this->getSss('niveau'))
    {
      case 1 : $msg .= "ce silence vaut ".$bonneReponse->getOrdre()." croche";
               if ($bonneReponse->getOrdre() > 1) $msg .= "s";
               break;
      case 2 : 
      case 3 :
      case 4 :
      case 5 :
      case 6 : $msg = "";
    }
    $msg .= " (" . $bonneReponse->getCommentaire().").";

    $this->setSss('correction', $msg);
  }
}

?>