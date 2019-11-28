<?php
namespace App\Controller;

use App\Entity\Vocabulaire;
use App\Objet\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstrumentController extends ExerciceController
{
  /**
   * @Route("/instrument", name="instrument")
   */
  public function main1()
  {
    $this->setSss('titreExo', 'instruments' );
    return $this->question('instrument');
  }

  /**
   * @Route("/instrumentCorrection{numRep}", name="instrumentCorrection", requirements={"numRep" = "\d+"})
   */
  public function main2($numRep)
  {
    return $this->correction('instrument', $numRep);
  }

  /**
   * @Route("/apprendre_instrument", name="apprendre_instrument")
   */
  public function main3()
  {
    $this->reinitNiveau();
    return $this->apprentissage('instrument');
  }

  /**
   * Cette fonction récupère la catégorie du niveau en cours dans la table Vocabulaire.
   * @return array d'objets Vocabulaire
   */
  protected function getVocabulaire()
  {
    switch ($this->getSss('niveau'))
    {
      case 1:
      case 2:
      case 3: return $this->getInstrumentsAvecSon();
      case 4:
      case 5: 
      case 6: return; // On ne passe plus par là à partir du niveau 4.
    }
  }

  protected function getInstrumentsAvecSon()
  {
    $liste = $this->getCategorie('instrument');
    $i = count($liste);
    while ($i > 0)
    {
      $i -= 1;
      if ($liste[$i]->getSymbole() == null) { unset($liste[$i]); }
      else if (strlen($liste[$i]->getSymbole()) < 1) { unset($liste[$i]); }
    }
    return $liste;
  }

  protected function getQuestion()
  {
    if ($this->getSss('niveau') < 4) return parent::getQuestion();
    $liste = array
    (
                                    // Réponse n°x du niveau y : y-x
                                    // y=4   5    6           8        10
      new Vocabulaire("violon"),      // 4-0                  8-1
      new Vocabulaire("hautbois"),    // 4 2      6-6
      new Vocabulaire("voix (ténor)"),// 4-2                  
      new Vocabulaire("trombone"),    // 4-3      6-3         8-5
      new Vocabulaire("violoncelle"), // 4-4
      new Vocabulaire("basson"),      //     5-0              8-6
      new Vocabulaire("timbales"),    //     5-1
      new Vocabulaire("piano"),       //     5-2
      new Vocabulaire("cymbales"),    //     5-3
      new Vocabulaire("gong"),        //     5-4
      new Vocabulaire("alto"),        //          6-0         8-0      10-3 et 10-5
      new Vocabulaire("piccolo"),     //          6-1
      new Vocabulaire("orgue"),       //          6-2
      new Vocabulaire("banjo"),       //          6-4
      new Vocabulaire("contrebasse"), //          6-5,        8-2
      new Vocabulaire("clarinette"),  //                      8-3
      new Vocabulaire("cor"),         //                      8-4      10-1
      new Vocabulaire("clavecin"),    //                               10-0
      new Vocabulaire("flûte"),       //                               10-2
      new Vocabulaire("voix (soprano)")//                              10-4
    );
    // Bonnes réponses selon le niveau, ordonnées par n° de question (indice 0 = question 1)
    switch ($this->getSss('niveau'))
    {
      case 4 : $bonnesReponses = [0,1,2,3,4]; break;
      case 5 : $bonnesReponses = [5,6,7,8,9]; break;
      case 6 : $bonnesReponses = [10,11,12,3,13,14,1]; break;
      case 8 : $bonnesReponses = [10,0,14,15,16,2,5]; break;
      case 10: $bonnesReponses = [17,16,18,10,19,10]; break;
    }
    // Assurons-nous qu'aucun d'eux n'aura pour id 1.
    $id = 2;
    foreach ($liste as $reponse)
    {
      $reponse->setId($id);
      $id += 1;
    }
    // L'id 1 est réservé à la bonne réponse.
    $nq = ((int)$this->getSss('numQuestion')) - 1;
    $numBonneReponse = $bonnesReponses[$nq];
    $liste[$numBonneReponse]->setId(1);
    // Le morceau à jouer :
    $liste[$numBonneReponse]->setSymbole("/niveau4/t".$this->getSss('niveau')."-".$nq);
    $this->setLibelleQuestion($liste[$numBonneReponse]);
    $question = new Question();
    $question->addReponse($liste[$numBonneReponse]);
    $question->setPropositions($liste, count($liste));
    $question->melangerPropositions();
    $this->setSss('question',$question);
  }

  protected function setLibelleQuestion(Vocabulaire $bonneReponse)
  {
    switch ($this->getSss('niveau'))
    {
      case 1: $bonneReponse->setTexteQuestion("Indique à quelle famille cet instrument appartient :");
              break;
      case 2:  $bonneReponse->setTexteQuestion("Ecoute et trouve la famille de cet instrument :");
              break;
      case 3: $bonneReponse->setTexteQuestion("Trouve le nom de cet instrument :");
              break;
      case 4: $bonneReponse->setTexteQuestion("Trouve le nom l'instrument qui joue la mélodie :");
              break;
      case 5: //$bonneReponse->setTexteQuestion("");
              break;
      case 6: //$bonneReponse->setTexteQuestion("");
              break;
    }
  }

  protected function initNiveau()
  {
    parent::initNiveau();
    switch ($this->getSss('niveau'))
    {
      case 1 : $this->setSss('modele', 'QCM_commentaire2'); break;
      case 2 : $this->setSss('modele', 'QCM_son_famille'); break;
      case 3 : // Vérifier le trophée Oreille musicale avant la fin de l'exercice.
               $nbBonnesRep = $this->getSss('nbBonnesRep');
               if ($nbBonnesRep > 3) { $this->obtentionTrophee(102); }
               $this->setSss('modele', 'QCM_son_nom'); break;
      case 4 :
      case 5 :
      case 6 :
      case 8 :
      case 10: $this->setSss('modele', 'QCM_son_nom'); break;
    }
  }

  protected function addFaussesReponses(Question $question, Vocabulaire $bonneReponse, array $tableau = null)
  {
    $question->addProposition($bonneReponse);
    // Nombre total de réponses à proposer (dont la bonne) : 4
    $familles = array("cordes","bois","cuivres","percussions");
    $i = array_search($bonneReponse->getCommentaire(), $familles);
    unset($familles[$i]);
    // $familles ne contient plus que les types d'instruments à mettre comme "fausse réponse".
    // On les cherche. Quand on en trouve un, on le retire de $familles, et on continue
    // jusqu'à ce qu'on ait vidé le tableau $familles.
    foreach ($tableau as $instrument)
    {
      if (in_array($instrument->getCommentaire(), $familles))
      {
        $question->addProposition($instrument);
        $i = array_search($instrument->getCommentaire(), $familles);
        unset($familles[$i]);
        // Est-ce qu'on a toutes nos réponses ?
        if (count($familles) == 0) break;
      }
    }
    $question->melangerPropositions();
  }

  protected function complementCorrection(Vocabulaire $bonneReponse)
  {
    $msg = $this->getSss('correction');
    $correct = true;
    if (substr($msg,0,3) == 'Oui') { $msg = "Tu as vu juste, "; }
    else  { $msg = "Eh non, "; $correct = false; }
    $complement = "";
    // Article devant le nom de l'instrument : à adapter s'il commence par une voyelle.
    $article = "de ";
    if (in_array($bonneReponse->getNom()[0],['a','e','i','o','u','y'])) $article = "d'";
    
    // Ajouter le nom de l'image est du son (nom.jpg, nom.mp3)
    $this->setSss('media', $bonneReponse->getSymbole());

    switch ($this->getSss('niveau'))
    {
      case 1 : $msg .= 'la réponse était "'.$bonneReponse->getCommentaire().'"';
               if (strlen($bonneReponse->getDescription()) > 0)
                 $complement =", car ".$bonneReponse->getDescription();
               break;
      case 2 :
      case 3 : $msg .= "ça vient d'un morceau ".$article.$bonneReponse->getNom()." (famille des " . $bonneReponse->getCommentaire().")";
               break; 
      
      case 4 : 
      case 5 :
      case 6 : if ($correct) $msg = "Exact ! La réponse est bien ".$bonneReponse->getNom();
               else
               {
                 $msg = "Ce n'est pas ça, désolé.";
                 $this->setSss("media",""); // On l'efface pour éviter de montrer la bonne réponse.
               }
    }
    $msg .= $complement.".";

    $this->setSss('correction', $msg);
  }
}

?>