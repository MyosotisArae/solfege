<?php
namespace App\Objet;

/**
 * P_image
 *
 */
class P_image {

  /**
   * @var P_image
   * @access private
   * @static
   */
  private static $_instance = null;
 
  /**
    * Constructeur de la classe
    *
    * @param void
    * @return void
    */
  private function __construct()
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
    $this->lettres = ['C','D','E','F','G','A','B'];
    $this->demiTons = [0,2,4,5,7,9,11];

    // Les notes, points et altérations sont placés au niveau F2
    // (1er interligne de la portée). La note F2 sera donc placée
    // au niveau de base : 0. Le placement des notes nécessite de
    // retrancher à leur niveau celui de F2 pour que leur image
    // soit à la bonne hauteur par rapport à la portée.
    $this->decalageGraphique = 0;
    $this->decalageGraphique = $this->getNiveau("F2");
  }
 
  /**
    * Méthode qui crée l'unique instance de la classe
    * si elle n'existe pas encore puis la retourne.
    *
    * @param void
    * @return P_image
    */
  public static function getInstance()
  {
    if(is_null(self::$_instance)) { self::$_instance = new P_image(); }
    return self::$_instance;
  }

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

  public function get_becarre() { return $this->becarre; }
  public function get_bemol() { return $this->bemol; }
  public function get_blanche() { return $this->blanche; }
  public function get_cle_fa() { return $this->cle_fa; }
  public function get_cle_sol() { return $this->cle_sol; }
  public function get_croche() { return $this->croche; }
  public function get_demi_soupir() { return $this->demi_soupir; }
  public function get_demie_pause() { return $this->demie_pause; }
  public function get_diese() { return $this->diese; }
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
    $i = random_int(0, count($this->lettres)-1);
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
    * $var modificateur : décalage à appliquer à la liste des notes
    * car dans ma notation, A,B,C,...,G indique un niveau sur la portée,
    * - en clé de sol, do-ré-mi... = C-D-E... 
    * - en clé de fa,  do-ré-mi... = A-B-C...
    */
  public function getHauteur(string $nomNote, int $modificateur)
  {
    // Ici, la lettre va indiquer le "vrai" nom de la note (C=do, ...).
    $lettre = array_search($nomNote[0],$this->lettres) + $modificateur;
    // $lettre doit être positif (c'est un indice pour le tableau demiTons)
    if ($lettre < 0) $lettre += 7;

    $octave= intval($nomNote[1]);
    $indiceLettre = array_search($lettre, $this->lettres);
    return ($this->demiTons[$indiceLettre] + (12 * $octave));
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
    $lettre = array_search($nomNote[0],$this->lettres);
    // F est la référence, on retranche la position de F
    $F = array_search("F",$this->lettres);
    $lettre = $F - $lettre;

    // L'octave 2 est de niveau 0. (octave 3 = -7, octave 1 = +7)
    $octave= 7 * (2 - intval($nomNote[1]));
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