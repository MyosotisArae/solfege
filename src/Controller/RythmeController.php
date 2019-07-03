<?php
namespace App\Controller;

use App\Entity\Vocabulaire;
use App\Objet\Question;
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

  protected function initVocab()
  {
    switch ($this->getSss('niveau'))
    {
      case 1: $this->constructionDesReponsesNiv1(); break;
      case 4: 
      case 2:
      case 5: $this->setSss('vocabulaire', $this->getCategorie('tempo') ); break;
      case 3:
      case 6: $this->setSss('vocabulaire', $this->getCategorie('expression') ); break;
    }
    $this->setSss('listeIndices',$this->getIndices());
  }
  
  /* Cette fonction récupère en base la liste des silences
   * et remplit le champ description avec une valeur exprimée
   * en figures de notes (croches, noires...)
   */
  private function constructionDesReponsesNiv1()
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
    foreach ($listeDeReponses as $silence)
    {
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
        }
      }
      $silence->setDescription($figure);
    }
    $this->setSss('vocabulaire', $listeDeReponses);
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
      case 5 : return "cing croches";
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
      case 1 : $this->setSss('modele', 'QCM_Lassus'); break;
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
    $suite = "";
    switch ($this->getSss('niveau'))
    {
      case 2 :
      case 5 : $suite = ' Tempo '.$bonneReponse->getCommentaire().'.'; break;
    }
    switch ($this->getSss('niveau'))
    {
      case 1 : $msg .= $bonneReponse->getNom().'" qui signifie "'.$bonneReponse->getDescription().'". '.$bonneReponse->getCommentaire();
               break;
      case 4 : $msg .= $bonneReponse->getnom().'", '."c'est à dire ".'"';
      case 2 :
      case 5 :
      case 3 :
      case 6 : $msg .= $bonneReponse->getDescription().'".';
    }
    $msg .= $suite;

    $this->setSss('correction', $msg);
  }
}

?>