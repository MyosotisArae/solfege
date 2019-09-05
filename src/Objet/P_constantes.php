<?php
namespace App\Objet;

use App\Objet\P_Elt;

/**
 * P_constantes
 *
 */
class P_constantes {

  /**
    * Constructeur de la classe
    *
    * @param void
    * @return void
    */
  public function __construct()
  {
    $this->becarre = "becarre";
    $this->bemol = "bemol";
    $this->blanche = "blanche";
    $this->cle_fa = "cle_fa";
    $this->cle_sol = "cle_sol";
    $this->croche = "croche";
    $this->demi_soupir = "demi_soupir";
    $this->demie_pause = "demie_pause";
    $this->diese = "diese";
    $this->noire = "noire";
    $this->pause = "pause";
    $this->point_interligne = "point_interligne";
    $this->point_ligne = "point_ligne";
    $this->barre = "barre";
    $this->portee = "portee";
    $this->ronde = "ronde";
    $this->soupir = "soupir";
    $this->nomNote_cleSol = ['do','ré','mi','fa','sol','la','si'];
    $this->lettres = 'CDEFGAB';
    $this->demiTons = [0,2,4,5,7,9,11];
    $this->nomsIntervalles = array ("prime","seconde","tierce","quarte","quinte","sixte","septième","octave","neuvième","dixième","onzième","douzième");

    // Les notes, points et altérations sont placés au niveau F2
    // (1er interligne de la portée). La note F2 sera donc placée
    // au niveau de base : 0. Le placement des notes nécessite de
    // retrancher à leur niveau celui de F2 pour que leur image
    // soit à la bonne hauteur par rapport à la portée.
    $this->decalageGraphique = 0;
    $this->decalageGraphique = $this->getNiveau("F2");

    // Indices des disciplines
    $this->listeDisciplines = array("italien","instrument","tonalite","rythme");
    // Nombre de questions (et donc score max) par discipline et par niveau:
    $this->maxParDisciplines = array(
                                    [6,6,6,6,8], // italien
                                    [6,6,6,8,8], // instrument
                                    [7,6,7,8,6], // tonalite
                                    [6,6,6,8,8]  // rythme
                                    );
  }
 
  // Indices des disciplines
  private $listeDisciplines;
  // Nombre de questions (et donc score max) par discipline et par niveau:
  private $maxParDisciplines;
  // Nom des intervalles (indépendant de la clé et des altérations)
  private $nomsIntervalles;
  // Noms des fichiers image (tous implicitement .gif)
  private $becarre;
  private $bemol;
  private $blanche;
  private $cle_fa;
  private $cle_sol;
  private $croche;
  private $demi_soupir;
  private $demie_pause;
  private $diese;
  private $noire;
  private $pause;
  private $point_interligne;
  private $point_ligne;
  private $barre;
  private $portee;
  private $ronde;
  private $soupir;
  private $decalageGraphique;

  public function getIndiceDiscipline(string $discipline) { return array_search($discipline, $this->listeDisciplines); }
  public function get_listeDisciplines() { return $this->listeDisciplines; }
  public function getScoreMax(string $exercice, int $niveau)
  {
    $indiceDiscipline = array_search($exercice, $this->listeDisciplines);
    return $this->maxParDisciplines[$indiceDiscipline][$niveau-1];
  }
  public function getScoreMax_int(int $indiceDiscipline, int $niveau)
  {
    return $this->maxParDisciplines[$indiceDiscipline][$niveau-1];
  }
  public function get_lettres() { return $this->lettres; }
  public function get_becarre() { return $this->becarre; }
  public function get_bemol() { return $this->bemol; }
  public function get_mot_bemol() { return"bémol "; }
  public function get_blanche() { return $this->blanche; }
  public function get_cle_fa() { return $this->cle_fa; }
  public function get_cle_sol() { return $this->cle_sol; }
  public function get_croche() { return $this->croche; }
  public function get_demi_soupir() { return $this->demi_soupir; }
  public function get_demie_pause() { return $this->demie_pause; }
  public function get_diese() { return $this->diese; }
  public function get_mot_diese() { return "dièse "; }
  public function get_noire() { return $this->noire; }
  public function get_pause() { return $this->pause; }
  public function get_portee() { return $this->portee; }
  public function get_ronde() { return $this->ronde; }
  public function get_soupir() { return $this->soupir; }
  public function get_decalageGraphique() { return $this->decalageGraphique; }

  private $lettres;
  // Nombre de demi tons EN CLE DE SOL de C à C, puis de C à D, puis de C à E... et de C à B
  private $demiTons;

  public function getLettreRandom()
  {
    $i = random_int(0, strlen($this->lettres)-1);
    return $this->lettres[$i];
  }

  public function get_point(int $niveau)
  {
    if ($niveau % 2 == 0) return $this->point_interligne;
    return $this->point_ligne;
  }

  public function get_barre()
  {
    return $this->barre;
  }

  /**
    * Cette fonction retourne une valeur qui permet de comparer le nombre de
    * demi tons entre différentes notes. Sa valeur n'a aucun sens isolément.
    * @var nomNote : lettre + chiffre (Ex : "G2")
    * $var modificateur : décalage à appliquer à la liste des notes à cause de la clé
    * car dans ma notation, A,B,C,...,G indique un niveau sur la portée,
    * - en clé de sol, do-ré-mi... = C-D-E... 
    * - en clé de fa,  do-ré-mi... = A-B-C...
    */
  public function getHauteur(string $nomNote, int $modificateur)
  {
    // Ici, la lettre va indiquer le "vrai" nom de la note (C=do, ...).
    $lettre = strpos($this->lettres, $nomNote[0]) + $modificateur;
    // $lettre doit être entre 0 et 6 (c'est un indice pour le tableau demiTons)
    // et A et B, en clé de fa, correspondent à l'octave du dessus.
    $modifOctave = 0;
    if ($lettre < 0) { $lettre += 7; }
    if ($lettre > 6) { $lettre -= 7; $modifOctave = 1; }

    $octave= intval($nomNote[1]) - 2 + $modifOctave;
    return ($this->demiTons[$lettre] + (12 * $octave));
  }

  /**
   * Retourne le nom de l'intervalle (seconde, tierce, ...).
   * nomNote1 doit être la note la plus basse.
   */
  public function getNomIntervalle(string $nomNote1, string $nomNote2)
  {
    $indice1 = strpos($this->lettres, $nomNote1[0]) - (12 * intval($nomNote1[1]));
    $indice2 = strpos($this->lettres, $nomNote2[0]) - (12 * intval($nomNote2[1]));
    $valeurIntervalle = abs($indice2 - $indice1);
    return $this->nomsIntervalles[$valeurIntervalle];
  }

  /**
   * Retourne le qualificatif de l'intervalle (mineure, majeure, ...) en fonction
   * de son nom (seconde, tierce, ...) et du nombre de tons entre les notes.
   * Géré jusqu'à la neuvième mineure.
   */
  public function getQualificatifIntervalle(string $nomIntervalle, P_Elt $elt1, P_Elt $elt2)
  {
    $valeurIntervalle = array_search($nomIntervalle, $this->nomsIntervalles);
    // Nombre de demi tons entre les notes :
    $hauteur = abs($elt2->getHauteur() - $elt1->getHauteur());
    switch ($hauteur)
    {
      case 0 : if ( $valeurIntervalle == 0) return "juste"; // prime
               else return "diminuée"; // seconde 
      case 1 : if ( $valeurIntervalle == 0) return "augmentée"; // prime
               else return "mineure"; // seconde 
      case 2 : if ( $valeurIntervalle == 1) return "majeure"; // seconde
               else return "diminuée"; // tierce   
      case 3 : if ( $valeurIntervalle == 1) return "augmentée"; // seconde
               else return "mineure"; // tierce
      case 4 : if ( $valeurIntervalle == 2) return "majeure"; // tierce
               else return "diminuée"; // quarte
      case 5 : if ( $valeurIntervalle == 2) return "augmentée"; // tierce
               else return "juste"; // quarte
      case 6 : if ( $valeurIntervalle == 3) return "augmentée"; // quarte
               else return "diminuée"; // quinte
      case 7 : if ( $valeurIntervalle == 4) return "juste"; // quinte
               else return "diminuée"; // sixte
      case 8 : if ( $valeurIntervalle == 4) return "augmentée"; // quinte
               else return "mineure"; // sixte
      case 9 : if ( $valeurIntervalle == 5) return "majeure"; // sixte
               else return "diminuée"; // septième
      case 10: if ( $valeurIntervalle == 5) return "augmentée"; // sixte
               else return "mineure"; // septième
      case 11: if ( $valeurIntervalle == 7) return "diminuée"; // octave
               else return "majeure"; // septième
      case 12: if ( $valeurIntervalle == 7) return "juste"; // octave
               else if ( $valeurIntervalle == 6) return "augmentée"; // septième
               else return "diminuée"; // neuvième
      case 13: if ( $valeurIntervalle == 7) return "augmentée"; // octave
               else return "mineure"; // neuvième
    }
    return "P_constantes.getQualificatifIntervalle() : hauteur ".$hauteur." non gérée.";
  }

  public function getNomNote(int $modificateurCle, string $nomNote)
  {
    $indice = strpos($this->lettres, $nomNote[0]) + $modificateurCle;
    if ($indice < 0) { $indice += 7; }
    if ($indice > 6) { $indice -= 7; }
    return $this->nomNote_cleSol[$indice];
  }

  /**
    * Niveau "graphique" (bien qu'on ne soit pas encore à des coordonnées en pixels
    * mais juste à une numérotation de la portée). Dans cette numérotation, F2 a le
    * niveau 0, car toutes les images (en particulier les notes : noire, croche...)
    * sont à ce niveau. Il doit être utilisable pour un placement par coordonnées,
    * donc le niveau doit être negatif au-dessus de F2 (G2=-1, A2=-2...) et positif
    * en-dessous (E2=1, D2 = 2...).
    * Les niveaux pairs sont ceux des notes qui sont sur une ligne, les notes dans
    * les interlignes ayant des niveaux impairs.
    */
  public function getNiveau(string $nomNote)
  {
    // Ici, la lettre va indiquer un niveau sur la portée (... E=1,F=0,G=-1).
    $lettre = strpos($this->lettres, $nomNote[0]);
    // F est la référence, on retranche la position de F
    $F = strpos($this->lettres, 'F');
    $lettre = $F - $lettre;

    // L'octave 2 est de niveau 0. (octave 3 = -7, octave 1 = +7)
    $octave = 7 * (2 - intval($nomNote[1]));
    $avantDecalage = $lettre + $octave;
    $resultat = $avantDecalage - $this->decalageGraphique;
    return $resultat;
  }

  /**
    * Retourne l'image correspondant à une note de cette durée.
    * @var duree : exprimée en nombre de croches
    */
  public function getImageNote(int $duree)
  {
    switch ($duree)
    {
      case 1  : return $this->get_croche();
      case 2  :
      case 3  : return $this->get_noire();
      case 4  :
      case 6  : return $this->get_blanche();
      default : return $this->get_ronde();
    }
  }
}
?>