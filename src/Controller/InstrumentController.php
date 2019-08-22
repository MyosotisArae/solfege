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
  protected function getVocalulaire()
  {
    switch ($this->getSss('niveau'))
    {
      case 1:
      case 2:
      case 3: return $this->getInstrumentsAvecSon();
      case 4: return $this->getInstrumentsAvecSon();
      case 5: return $this->getCategorie('tempo');
      case 6: return $this->getCategorie('expression');
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

  protected function initNiveau()
  {
    parent::initNiveau();
    switch ($this->getSss('niveau'))
    {
      case 1 : $this->setSss('modele', 'QCM_commentaire2'); break;
      case 2 : $this->setSss('modele', 'QCM_son_famille'); break;
      case 3 : $this->setSss('modele', 'QCM_son_nom'); break;
      case 4 : $this->setSss('modele', 'QCM_son_nom'); break;
      case 5 :
      case 6 : $this->setSss('modele', 'QCM_nom'); break;
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
    if (substr($msg,0,3) == 'Oui') { $msg = "Tu as vu juste, "; }
    else  { $msg = "Eh non, "; }
    $complement = "";
    // Article devant le nom de l'instrument : à adapter s'il commence par une voyelle.
    $article = "de ";
    if (in_array($bonneReponse->getNom()[0],['a','e','i','o','u','y'])) $article = "d'";
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
      case 6 : $msg = "";
    }
    $msg .= $complement.".";
    
    // Ajouter le nom de l'image est du son (nom.jpg, nom.mp3)
    $this->setSss('media', $bonneReponse->getSymbole());

    $this->setSss('correction', $msg);
  }
}

?>