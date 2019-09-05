<?php
namespace App\Controller;

use App\Entity\Vocabulaire;
use App\Objet\Question;
use App\Objet\Portee;
use App\Objet\P_Elt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TonaliteController extends ExerciceController
{
  /**
   * @Route("/tonalite", name="tonalite")
   */
  public function main1()
  {
    $this->setSss('titreExo', 'les tonalités' );
    return $this->question('tonalite');
  }

  /**
   * @Route("/tonaliteCorrectionForm", name="tonaliteCorrectionForm")
   */
  public function main4(Request $request)
  {
    if ($request->getMethod() == Request::METHOD_POST){
      $texte = $request->request->get('texteFormulaire');
      return $this->correction('tonalite', $texte);
    }
  }

  /**
   * @Route("/tonaliteCorrection{numRep}", name="tonaliteCorrection", requirements={"numRep" = "\d+"})
   */
  public function main2($numRep)
  {
    return $this->correction('tonalite', $numRep);
  }

  /**
   * @Route("/apprendre_tonalite", name="apprendre_tonalite")
   */
  public function main3()
  {
    $this->reinitNiveau();

    // Tableau des portées utilisées dans ce template
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("D3", "", 2);
    $p->setEchelle(0.4);
    $listePortees = array($p);//0
    $p = new Portee("");
    $p->addNote("E3", "", 2);
    $p->addNote("F3", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//1
    $p = new Portee("");
    $p->addNote("G2", "", 2);
    $p->addNote("B2", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//2
    $p = new Portee("");
    $p->addNote("D2", "", 2);
    $p->addNote("F2", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//3
    $p = new Portee("");
    $p->addNote("C2", "", 2);
    $p->addNote("F2", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//4
    $p = new Portee("");
    $p->addNote("C2", "", 2);
    $p->addNote("G2", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//5
    $p = new Portee("");
    $p->addNote("C2", "", 2);
    $p->addNote("C3", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//6
    $p = new Portee("");
    $p->addNote("C3", $this->cst->get_diese(), 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//7
    $p = new Portee("");
    $p->addNote("D3", $this->cst->get_bemol(), 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//8
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("C3", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//9
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("C3", $this->cst->get_diese(), 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//10
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("D3", $this->cst->get_bemol(), 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//11
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("D3", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//12
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("D3", $this->cst->get_diese(), 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//13
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("E3", $this->cst->get_bemol(), 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//14
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("E3", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//15
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("E3", $this->cst->get_diese(), 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//16
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("F3", $this->cst->get_bemol(), 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//17
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("F3", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//18
    $p = new Portee("");
    $p->addNote("C3", "", 2);
    $p->addNote("F3", $this->cst->get_diese(), 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//19
    $p = new Portee("");
    $p->addNote("F3", "", 2);
    $p->addNote("C3", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//20
    $p = new Portee($this->cst->get_cle_sol());
    $p->addNote("A2", "", 2);
    $p->addNote("B2", "", 2);
    $p->addNote("D3", "", 2);
    $p->addNote("E3", "", 2);
    $p->addNote("A3", "", 2);
    $p->addNote("G3", "", 2);
    $p->addNote("C3", "", 2);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//21
    $p = new Portee($this->cst->get_cle_sol());
    $p->addNote("C2", "", 2,["E3"]);
    $p->addNote("G2", "", 2,["G3"]);
    $p->addNote("C2", "", 2,["G3"]);
    $p->setEchelle(0.4);
    $listePortees[] = $p;//22

    return $this->apprentissage('tonalite',array("listePortees" => $listePortees));
  }

  /**
   * Cette fonction récupère la catégorie du niveau en cours dans la table Vocabulaire.
   * @return array d'objets Vocabulaire
   */
  protected function getVocabulaire()
  {
    switch ($this->getSss('niveau'))
    {
      case 1: $this->setSss('texteQuestion', "Que signifie ce symbole ?");
              return $this->getCategorie('ton');
      case 2: return array();
      case 3: return $this->getVocabulaireNiv3();
      case 4:
      case 5: return $this->getCategorie('tempo');
      case 6: return $this->getCategorie('expression');
    }
  }

  protected function initNiveau()
  {
    parent::initNiveau();
    switch ($this->getSss('niveau'))
    {
      case 1 : $this->setSss('modele', 'QCM_symbole'); break;
      case 2 : $this->setSss('modele', 'QCM_intervalle'); break;
      case 3 : $this->setSss('modele', 'QCM_portee'); break;
      case 5 :
      case 6 : $this->setSss('modele', 'QCM_nom'); break;
      case 4 : $this->setSss('modele', 'QCM_commentaire1'); break;
    }
  }

  protected function getQuestionNiv2()
  {
    // Le niveau 2 consiste à identifier un intervalle. Exemple de réponse : do mi tierce majeure
    // Le joueur ne doit pas le taper mais utiliser des boutons pour compléter ou effacer sa réponse.
    // Pas besoin d'une liste de fausses réponses, donc. Les boutons seront toujours les mêmes.
    // La question est formée d'une clé (plus souvent de sol que de fa, statistiquement) et
    // de deux notes, la deuxième pouvant avoir une altération.
    $octave = strval(random_int(2,3));
    $durees = [1,2,3,4,6,8];
    shuffle($durees);
    $duree = array_shift($durees);
    // Tirer une clé au hazard. 70% de chances d'avoir une clé de sol.
    $nomCle = $this->cst->get_cle_sol();
    $chances = random_int(1,100);
    if ($chances < 30) $nomCle =$this->cst->get_cle_fa();
    $p = new Portee($nomCle);
    $nomNote1 = $this->cst->getLettreRandom().$octave;
    $nomNote2 = $this->cst->getLettreRandom().$octave;
    // Une chance sur deux d'avoir une altération sur la 2ème.
    $chances = random_int(1,100);
    $nomAteration = "";
    if ($chances < 25) $nomAteration =$this->cst->get_diese();
    else if ($chances < 50)
    {
      // Si les deux notes sont identiques, éviter le bémol.
      if ($nomNote1 == $nomNote2) $nomAteration =$this->cst->get_diese();
      else $nomAteration =$this->cst->get_bemol();
    }
    $harmonique = false;
    // Seulement si les deux notes sont différentes:
    if ($nomNote1 != $nomNote2)
    {
      // Comparer la hauteur des deux notes, SANS altération pour l'instant, pour les ranger de la plus basse à la plus haute.
      $h1 = $this->cst->getHauteur($nomNote1, $p->getCle()->getModificateur());
      $h2 = $this->cst->getHauteur($nomNote2, $p->getCle()->getModificateur());      
      $note1 = $nomNote1;
      $note2 = $nomNote2;
      if ($h1 > $h2)
      {
        $nomNote1 = $note2;
        $nomNote2 = $note1;
      }
      // Si l'écart entre les notes est suffisant, 40% de chances d'avoir
      // un intervalle harmonique (un accord) plutôt que mélodique.
      if (abs($h1-$h2) > 2)
      {
        $chances = random_int(1,100);
        if ($chances < 40) $harmonique = true;
      }
    }

    // Maintenant, nomNote1 est la plus basse et nomNote2 la plus haute (ou bien elles sont identiques).
    // Appliquons l'altération à la seconde.
    $elt1 = new P_Elt($p->getCle());
    $elt1->addNote($nomNote1, $duree);

    $elt2 = new P_Elt($p->getCle());
    $niv2 = $elt2->addNote($nomNote2, $duree);
    $elt2->addAlteration($nomAteration, $niv2);

    if ($harmonique)
    { // Intervalle harmonique
      $p->addNote($nomNote2, $nomAteration, $duree, [$nomNote1]);
    }
    else
    { // Intervalle mélodique
      $p->addNote($nomNote1, "", $duree);
      $p->addNote($nomNote2, $nomAteration, $duree);
    }

    // Construction du nom complet de l'intervalle :
    $nomIntervalle = $this->cst->getNomIntervalle($nomNote1, $nomNote2);
    $quaIntervalle = $this->cst->getQualificatifIntervalle($nomIntervalle, $elt1, $elt2);
    $nom_note1 = $this->cst->getNomNote($p->getCle()->getModificateur(), $nomNote1);
    $nom_note2 = $this->cst->getNomNote($p->getCle()->getModificateur(), $nomNote2);
    $nomComplet = $nom_note1 . ' ' . $nom_note2 . ' ' . $nomIntervalle . ' ' . $quaIntervalle;

    // Construction de la réponse
    $bonneReponse = new Vocabulaire();
    $bonneReponse->setPortee2($p);
    $bonneReponse->setNom($nomComplet);

    $question = new Question();
    $question->addReponse($bonneReponse);
    $this->setSss('question',$question);

  }

  /**
   * Niveau 3 :
   * Question = portée avec une note altérée (ou pas)
   * Réponse  = portée avec une autre note dont l'altération est différente (ou absente)
   * @return array d'objets Vocabulaire
   */
  protected function getVocabulaireNiv3()
  {
    // Si c'est la première question, contruire la liste des réponses (une question par note => 7).
    // Questions dans portee1, réponse dans portee2
    if ($dejaDemande == null || count($dejaDemande) == 0)
    {
      $tableauDeReponses = [];
      $lettres = $this->cst->get_lettres();

      $transfoDiese = substr($lettres,  1) . substr($lettres, 0,  1); // Mettre la 1ère note à la fin.
      $transfoBemol = substr($lettres, -1) . substr($lettres, 0, -1); // Mettre la dernière note au début.

      $durees = [1,2,3,4,6,8];
      shuffle($durees);
      
      foreach (range(0,6) as $indice)
      {
        $noteQuestion = $lettres[$indice];
        $octaveQ = random_int(2,3);
        $octaveR = $octaveQ;
        $dureeQ = $durees[random_int(0,5)];
        $dureeR = $durees[random_int(0,5)];
        $bemolQ = false; // Bémol sur la question ?
        $dieseQ = false; // Dièse sur la question ?
        $bemolR = false; // Bémol sur la réponse ?
        $dieseR = false; // Dièse sur la réponse ?
        $alterationQ = "";
        $alterationR = "";
        $nomAlterationQ = "";
        $nomAlterationR = "";
        // C/F/E ou B : 1 chance sur 10 de ne pas y mettre d'altération.
        $chances = random_int(1,10);
        if (strpos("CFEB", $noteQuestion) !== false && $chances == 1)
        {
          if (strpos("CF", $noteQuestion) !== false) { $dieseR = true; }
          else { $bemolR = true; }
        }
        else
        // Dans les autres cas, on tire une altération au hazard.
        {
          if ($chances < 6)
          {
            $dieseQ = true;
            $alterationQ = $this->cst->get_diese();
            $nomAlterationQ = $this->cst->get_nom_diese();
            $bemolR = ( ($indice != 2) && ($indice != 6) );
          }
          else
          {
             $bemolQ = true;
             $alterationQ = $this->cst->get_bemol();
             $nomAlterationQ = $this->cst->get_nom_bemol();
             $dieseR = ( ($indice != 0) && ($indice != 3) );
          }
        }
        $noteReponse = "";
        if ($dieseR)
        {
          $alterationR = $this->cst->get_diese();
          $nomAlterationR = $this->cst->get_nom_diese();
          $noteReponse = $transfoDiese[$indice];
          if ($noteQuestion == 'C') { $octaveR -= 1; }
        }
        if ($bemolR)
        {
          $alterationR = $this->cst->get_bemol();
          $nomAlterationR = $this->cst->get_nom_bemol();
          $noteReponse = $transfoBemol[$indice]; 
          if ($noteQuestion == 'B') { $octaveQ -= 1; }
        }

        $bonneReponse = new Vocabulaire();
        $bonneReponse->setId($indice+1);
        $bonneReponse->setDescription("le ".$this->cst->getNomNote(0,$noteQuestion)." ".$nomAlterationQ."a la même tonalité que le ".$this->cst->getNomNote(0,$noteReponse)." ".$nomAlterationR.".");
        // On a le nom de la réponse et on sait quelles sont les altérations.
        // Construisons les portées.
        // Question : portee1
        $p1 = new Portee($this->cst->get_cle_sol());
        $p1->addNote($noteQuestion.$octaveQ, $alterationQ, $dureeQ);
        $bonneReponse->setPortee1($p1);
        // Réponse : portee2
        $p2 = new Portee($this->cst->get_cle_sol());
        $p2->addNote($noteReponse.$octaveR, $alterationR, $dureeR);
        $bonneReponse->setPortee2($p2);
        $tableauDeReponses[] = $bonneReponse;
      }
      // Mettre ce tableau dans la session afin de le retrouver lors de la prochaine question de ce niveau.
      $this->setSss('Vocabulaire', $tableauDeReponses);
      return $tableauDeReponses;
    }
    return $this->getSss('Vocabulaire');
  }

  protected function addFaussesReponses(Question $question, Vocabulaire $bonneReponse, array $tableau = null)
  {
    if ($this->getSss('niveau') != 3) return parent::addFaussesReponses($question,$bonneReponse,$tableau);
    // Ajouter la bonne réponse à cette liste,
    $question->addProposition($bonneReponse);
    // et mélanger le tout.
    $question->melangerPropositions();
  }

  protected function getQuestion()
  {
    switch ($this->getSss('niveau'))
    {
      case 1: parent::getQuestion(); break;
      case 2: $this->setSss('texteQuestion', "Donne le nom de cet intervalle (2 notes + nom) en utilisant les boutons.");
              $this->setSss('avecQualificatif', false);
              $this->getQuestionNiv2();
              break;
      case3:  $this->setSss('texteQuestion', "Trouve la note de même tonalité que celle-ci (avec la même clé) :");
              parent::getQuestion();
              break;
      case 5: $this->setSss('texteQuestion', "Donne le nom complet de cet intervalle (2 notes + nom + qualificatif) en utilisant les boutons.");
              $this->setSss('avecQualificatif', true);
              $this->getQuestionNiv2();
              break;
    }
  }

  protected function testReponseFournie(Question $question, $reponseFournie)
  {
    switch ($this->getSss('niveau'))
    {
      case 1:
      case 3: return parent::testReponseFournie($question, $reponseFournie);
      case 2:
      case 5: return $this->testReponseFournieNiv2($question, $reponseFournie);
    }
  }
  
  protected function testReponseFournieNiv2(Question $question, string $reponseFournie)
  {
    $bonneReponse = $question->getReponse();
    // Il faut trouver dans $texte (la réponse de l'utilisateur) chacun des mots de la bonne réponse.
    $nbMots = 3; // Seuls les 3 premiers mots sont demandés au niveau 2.
    if ($this->getSss('avecQualificatif') === true) $nbMots = 4;
    // Liste des mots qui doivent figurer dans la réponse:
    $listeMots = explode(' ', trim($bonneReponse->getNom()));
    $motsTrouves = true;
    foreach ($listeMots as $mot)
    {
      if (strpos($reponseFournie, $mot) === false) $motsTrouves = false; 
      $nbMots -= 1;
      if ($nbMots <= 0) break;
    }
    return $motsTrouves;
  }

  protected function complementCorrection(Vocabulaire $bonneReponse)
  {
    $msg = $this->getSss('correction');
    if (substr($msg,0,3) == 'Oui') { $msg = "Oui, très bien, "; }
    else  { $msg = "Non. En vérité,  "; }
    switch ($this->getSss('niveau'))
    {
      case 1 : $msg .= "c'est un " . $bonneReponse->getnom() . ".";
               break;
      case 2 : $msg .= "cet intervalle se nomme " . $bonneReponse->getnom() . ".";
               break;
      case 3 :
      case 4 :
      case 5 :
      case 6 : $msg = "";
    }
    $msg .= " " . $bonneReponse->getDescription()." " . $bonneReponse->getCommentaire();

    $this->setSss('correction', $msg);
  }
}

?>