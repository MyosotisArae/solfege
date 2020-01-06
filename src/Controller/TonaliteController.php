<?php
namespace App\Controller;

use App\Entity\Vocabulaire;
use App\Objet\Question;
use App\Objet\Portee;
use App\Objet\P_Elt;
use App\Objet\P_Note;
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
      case 1: return $this->getCategorie('ton');
      case 2: return array();
      case 3:
      case 4: return $this->getVocabulaireNiv3et4();
      case 5: return $this->getCategorie('tempo');
      case 6: return $this->getCategorie('expression');
    }
  }

  protected function setLibelleQuestion(Vocabulaire $bonneReponse)
  {
    switch ($this->getSss('niveau'))
    {
      case 1: $bonneReponse->setTexteQuestion("Que signifie ce symbole ?");
              break;
      case 2:  $bonneReponse->setTexteQuestion("Donne le nom de cet intervalle (2 notes + le nom) en utilisant les boutons ci-dessus.");
              break;
      case 3: $bonneReponse->setTexteQuestion("Trouve la note de même tonalité que celle-ci (pour la même clé) :");
              break;
      case 4: // Question déjà mise à jour dans dans getVocabulaireNiv3et4()
              break;
      case 5: $bonneReponse->setTexteQuestion("Donne le nom complet de cet intervalle (2 notes + le nom + le qualificatif) en utilisant les boutons ci-dessus.");
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
      case 1 : $this->setSss('modele', 'QCM_symbole'); break;
      case 2 : $this->setSss('modele', 'QCM_intervalle'); break;
      case 3 :
      case 4 : $this->setSss('modele', 'QCM_portee'); break;
      case 5 :
      case 6 : $this->setSss('modele', 'QCM_nom'); break;
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
    $pNote1 = new P_Note();
    $pNote2 = new P_Note();
    $pNote1->setOctave($octave);
    $pNote2->setOctave($octave);
    $duree = $this->cst->getDureeAleatoire();
    // Tirer une clé au hazard. 70% de chances d'avoir une clé de sol.
    $nomCle = $this->cst->get_cle_sol();
    $chances = random_int(1,100);
    if ($chances < 30) $nomCle =$this->cst->get_cle_fa();
    $p = new Portee($nomCle);
    $pNote1->setLettre($this->cst->getLettreRandom());
    $pNote2->setLettre($this->cst->getLettreRandom());
    // Une chance sur deux d'avoir une altération sur la 2ème.
    $chances = random_int(1,100);
    $nomAteration = "";
    if ($chances < 25) $nomAteration =$this->cst->get_diese();
    else if ($chances < 50)
    {
      // Si les deux notes sont identiques, éviter le bémol.
      if ($pNote1->getLettre() == $pNote2->getLettre()) $nomAteration = $this->cst->get_diese();
      else $nomAteration = $this->cst->get_bemol();
    }
    $harmonique = false;
    // Seulement si les deux notes sont différentes:
    if ($pNote1->getLettre() != $pNote2->getLettre())
    {
      // Comparer la hauteur des deux notes, SANS altération pour l'instant, pour les ranger de la plus basse à la plus haute.
      $h1 = $p->getHauteur($pNote1);
      $h2 = $p->getHauteur($pNote2);
      if ($h1 > $h2)
      {
        $pNote = $pNote1;
        $pNote1 = $pNote2;
        $pNote2 = $pNote;
      }
      // Si l'écart entre les notes est suffisant, 40% de chances d'avoir
      // un intervalle harmonique (un accord) plutôt que mélodique.
      if (abs($h1-$h2) > 2)
      {
        $chances = random_int(1,100);
        if ($chances < 40) $harmonique = true;
      }
    }

    // Maintenant, pNote1 est la plus basse et pNote2 la plus haute (ou bien elles sont identiques).
    // Appliquons l'altération à la seconde.
    $elt1 = new P_Elt($p->getCle());
    $elt1->addNote($pNote1->getNom(), $duree);

    $elt2 = new P_Elt($p->getCle());
    $niv2 = $elt2->addNote($pNote2->getNom(), $duree);
    $pNote2->setAlteration($nomAteration);
    $elt2->addAlteration($nomAteration, $niv2);

    if ($harmonique)
    { // Intervalle harmonique
      $p->addNote($pNote2->getNom(), $nomAteration, $duree, [$pNote1->getNom()]);
    }
    else
    { // Intervalle mélodique
      $p->addNote($pNote1->getNom(), "", $duree);
      $p->addNote($pNote2->getNom(), $nomAteration, $duree);
    }

    // Construction du nom complet de l'intervalle :
    $nomIntervalle = $this->cst->getNomIntervalle($pNote1->getNom(), $pNote2->getNom());
    $quaIntervalle = $this->cst->getQualificatifIntervalle($nomIntervalle, $elt1, $elt2);
    $nom_note1 = $this->cst->getNomNote($p->getCle()->getModificateur(), $pNote1->getLettre());
    $nom_note2 = $this->cst->getNomNote($p->getCle()->getModificateur(), $pNote2->getLettre());
    $nomComplet = $nom_note1 . ' ' . $nom_note2 . ' ' . $nomIntervalle . ' ' . $quaIntervalle;

    // Construction de la réponse
    $bonneReponse = new Vocabulaire();
    $bonneReponse->setPortee2($p);
    $bonneReponse->setNom($nomComplet);

    $question = new Question();
    $question->addReponse($bonneReponse);
    $this->setSss('question',$question);    
    $this->setLibelleQuestion($bonneReponse);
  }

  /**
   * Niveau 3 :
   * Question = portée avec une note altérée (ou pas)
   * Réponse  = portée avec une autre note dont l'altération est différente (ou absente)
   * @return array d'objets Vocabulaire
   */
  protected function getVocabulaireNiv3et4()
  {
    $diffMax = 6;
    $signe = 1;
    $nbDemiTons = 0;
    $phrase = "";  
    $pNoteQuestion = new P_Note();
    if ($this->getSss('niveau') == 4)
    {
        // Niveau 4
        $chances = random_int(1,10);
        // Nombre de demi tons à enlever ou ajouter pour obtenir la réponse :
        $nbDemiTons = random_int(1,$diffMax);
        $nbTons = (int)($nbDemiTons/2);
        if ($nbTons == 0) { $phrase = "un demi ton "; }
        else
        {
          if ($nbTons == 1) { $phrase = "un ton "; }
          else { $phrase = $nbTons." tons "; }
          if ($nbDemiTons % 2 == 1) $phrase .= "et demi ";
        }
        if ($chances < 6) $signe = -1;
        if ($signe == 1) { $phrase .= "au-dessus "; }
        else  { $phrase .= "en-dessous "; }
        $nbDemiTons *= $signe;
    }

    // Si c'est la première question, contruire la liste des réponses (une question par note => 7).
    // Questions dans portee1, réponse dans portee2
    $dejaDemande = $this->getSss('dejaDemande');
    if ($dejaDemande != null && count($dejaDemande) > 0) return $this->getSss('Vocabulaire');

    $tableauDeReponses = [];
    $lettres = $this->cst->get_lettres();

    // Parcourir les notes de la gamme (indice 0 = do) afin d'avoir une question sur chacune d'elles.
    foreach (range(1,7) as $indice)
    {
      $pNoteQuestion->setLettre($lettres[$indice-1]);
      $pNoteQuestion->setOctave(random_int(2,3));

      $chances = random_int(1,10);
      // do/fa : 3 chances sur 10 de mettre un bémol.
      if (strpos("CF", $pNoteQuestion->getLettre()) !== false && $chances <= 3) $pNoteQuestion->setAlteration($this->cst->get_bemol());
      // mi/si : 3 chances sur 10 de mettre un dièse.
      else if (strpos("EB", $pNoteQuestion->getLettre()) !== false && $chances <= 3) $pNoteQuestion->setAlteration($this->cst->get_diese());
      // Sinon, mettre une altération dans 6 cas sur 10, ou systématiquement s'il faut trouver une note de même tonalité.
      else if (($chances <= 6) || ($nbDemiTons == 0))
      {
        $pNoteQuestion->setAlteration( $this->cst->get_bemol());
        if ($chances <= 3) $pNoteQuestion->setAlteration($this->cst->get_diese());
      }

      // La bonne réponse est dans pNoteQuestion
      $nomQaffichable = $this->cst->getNomNote(0,$pNoteQuestion->getLettre()).$this->cst->getMotAlteration($pNoteQuestion->getAlteration());
      // Elle correspondra à la réponse pNote :
      $p1 = new Portee($this->cst->get_cle_sol());
      $pNote = $p1->getNoteSelonTonalite($pNoteQuestion, $nbDemiTons);
      $nomRaffichable = $this->cst->getNomNote(0,$pNote->getLettre()).$this->cst->getMotAlteration($pNote->getAlteration());

      $bonneReponse = new Vocabulaire();
      $bonneReponse->setId($indice);
      $nomNoteQuestion = $this->cst->getNomNote(0,$pNoteQuestion->getLettre());
      if ($this->getSss('niveau') == 4)
      {
        $bonneReponse->setCommentaire("le ".$nomRaffichable);
        $bonneReponse->setDescription(" est ".$phrase." du ".$nomQaffichable.".");
        $bonneReponse->setTexteQuestion("Trouve la note située ".$phrase." de celle-ci (les notes du bas sont en clé de sol sans armature):");
      }
      else
      {
        $bonneReponse->setCommentaire("le ".$nomQaffichable);
        $bonneReponse->setDescription(" a la même tonalité que le ".$nomRaffichable.".");
      }

      // On a le nom de la réponse et on sait quelles sont les altérations.
      // Construisons les portées.
      // Question : portee1
      // Au niveau 3, on mettra l'altération devant la note.
      // Au niveau 4, on mettra 1 à 6 altérations dans l'armature.
      if ($this->getSss("niveau") == 3) { $p1->addNote($pNoteQuestion->getNom(), $pNoteQuestion->getAlteration(), $this->cst->getDureeAleatoire()); }
      else
      {
        // Dans tous les cas, on met une armature.
        // S'il n'y a pas d'altération,
        // - soit on les met quand même et on ajoute un bécarre, (cas A1)
        //   - armature en dièse (cas A1.1)
        //   - armature en bémol (cas A1.2)
        // - soit on en met moins afin que la note ne soit pas altérée. (cas A2)
        //   - armature en dièse, mais pas pour un FA (cas A2.1)
        //   - armature en bémol, mais pas pour un SI (cas A2.2)
        // S'il y a une altération, elle est dans l'armature. (cas B)
        // L'armature peut contenir des altérations supplémentaires (non indispensables pour altérer la note) dans les cas A1 et B.
        $plusDalterations = true;
        $retirer = 0; // Nombre d'altérations à retirer de l'armature.
        $alterationArmature = $this->cst->get_diese();
        $armature = $this->cst->getArmature($nomNoteQuestion, $pNoteQuestion->getAlteration(), $plusDalterations);

        // Pas d'altération. On en choisit une au hazard pour créer l'armature.
        if ($pNoteQuestion->getAlteration() == "")
        {
          $chances = random_int(1,10);
          if ($chances <= 5) $pNoteQuestion->setAlteration($this->cst->get_becarre()); // Cas A1
          else
          {
            // Cas A2
            $plusDalterations = false;
            $retirer = -1; 
          }
          // Armature en dièse ou en bémol ?
          if ($pNoteQuestion->getLettre() == 'F') $alterationArmature = $this->cst->get_bemol(); // Cas A?.2
          else if ($pNoteQuestion->getLettre() == 'B') $alterationArmature = $this->cst->get_diese(); // Cas A?.1
          else if ($chances <= 5) $alterationArmature = $this->cst->get_bemol(); // Cas A?.2

          if ($retirer < 0)
          {
            // ordre de la note dans l'armature sélectionnée.
            $indiceNote = $this->cst->getIndiceArmature($nomNoteQuestion, $alterationArmature);
            // Retirer une ou plusieurs altérations à l'armature, mais il doit en rester au moins une.
            $retirer = -1 * (1+random_int(0, $indiceNote-1));
          }
        }
        // Cas B
        else
        {
          $alterationArmature = $pNoteQuestion->getAlteration();
          $pNoteQuestion->setAlteration("");
        }
        // Créer l'armature
        $armature = $this->cst->getArmature($nomNoteQuestion, $alterationArmature, $plusDalterations);
        if ($retirer < 0)
        {
          $armature = array_slice($armature,0,$retirer) ;
        }

        foreach ($armature as $positionAlteration) { $p1->addAlteration($positionAlteration, $alterationArmature); }

        // Ajouter un chiffrage (dont la mesure est supérieure ou égale à la durée de la note), puis un blanc pour que la note ne soit pas collée à l'armature.
        $duree = $this->cst->getDureeAleatoire();
        $p1->addSigne($this->cst->getChiffrageAleatoire($duree));
        $p1->addSigne($this->cst->get_portee());

        $p1->addNote($pNoteQuestion->getNom(), $pNoteQuestion->getAlteration(),  $duree);
      }
      $bonneReponse->setPortee1($p1);
      // Réponse : portee2
      $p2 = new Portee();
      $p2->addNote($pNote->getNom(), $pNote->getAlteration(), $this->cst->getDureeAleatoire());
      $bonneReponse->setPortee2($p2);

      # Construction de la liste des réponses fausses selon leur différence de ton avec la note de la question.
      # Cette différence ne doit ni être 0 (=note de la question), ni être égale à nbDemiTons(=note de la réponse).
      $differences = [];
      foreach (range(-1*$diffMax,$diffMax) as $nbDemiTonsMR)
      {
        if (($nbDemiTonsMR != 0) && ($nbDemiTonsMR != $nbDemiTons)) { $differences[] = $nbDemiTonsMR; }
      }
      $diffDemiTons = [];
      while (count($diffDemiTons) <= 5)
      {
        // Mélanger le tableau et prélever le premier élément.
        shuffle($differences);
        $diffDemiTons[] = array_shift($differences);
      }
      // Combiner tout ça en évitant la bonne réponse
      $indiceMauvaiseReponse = $indice * 10;
      foreach ($diffDemiTons as $nbDemiTonsMR)
      {
        $pNoteMR = $p1->getNoteSelonTonalite($pNoteQuestion, $nbDemiTonsMR);
        $mauvaiseReponse = new Vocabulaire();
        $mauvaiseReponse->setId($indiceMauvaiseReponse);
        $p2 = new Portee();
        $p2->addNote($pNoteMR->getNom(), $pNoteMR->getAlteration(), $this->cst->getDureeAleatoire());
        $mauvaiseReponse->setPortee2($p2);
        $bonneReponse->addMauvaiseReponse($mauvaiseReponse);
      }

      $tableauDeReponses[] = $bonneReponse;
    }
    // Mettre ce tableau dans la session afin de le retrouver lors de la prochaine question de ce niveau.
    $this->setSss('Vocabulaire', $tableauDeReponses);
    return $tableauDeReponses;
  }

  protected function getQuestion()
  {
    switch ($this->getSss('niveau'))
    {
      case 1: parent::getQuestion();
    break;
      case 2: $this->setSss('avecQualificatif', false);
              $this->getQuestionNiv2();
              break;
      case 3:
      case 4: parent::getQuestion();
              break;
      case 5: $this->setSss('avecQualificatif', true);
              $this->getQuestionNiv2();
              break;
    }
  }

  protected function testReponseFournie(Question $question, $reponseFournie)
  {
    switch ($this->getSss('niveau'))
    {
      case 1:
      case 3:
      case 4: return parent::testReponseFournie($question, $reponseFournie);
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
    else  { $msg = "Non. En vérité, ";}
    switch ($this->getSss('niveau'))
    {
      case 1 : $msg .= "c'est un " . $bonneReponse->getnom() . ".";
               $msg .= " " . $bonneReponse->getDescription()." " . $bonneReponse->getCommentaire();
               break;
      case 2 : $msg .= "cet intervalle se nomme " . $bonneReponse->getnom() . ".";
               $msg .= " " . $bonneReponse->getDescription()." " . $bonneReponse->getCommentaire();
               break;
      case 3 : 
      case 4 : $msg .= $bonneReponse->getCommentaire().$bonneReponse->getDescription();
               break;
      case 5 :
      case 6 : $msg = "";
    }
    

    $this->setSss('correction', $msg);
  }
}

?>