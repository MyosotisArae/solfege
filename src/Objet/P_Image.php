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

  public function get_point(int $niveau)
  {
    if ($niveau % 2 == 0) return $this->point_interligne;
    return $this->point_ligne;
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
    return ($this->demiTons($lettre) + (12 * $octave));
  }

  /**
    * Niveau "graphique" (bien qu'on ne soit pas encore à des coordonnées en pixels
    * mais juste à une numérotation de la portée, où C0=0, D0=1) de la note avant
    * réajustement (car en fait, ceci est seulement le niveau stocké dans l'élément
    * car l'ordonnée 0 sera celle de F2, vu que c'est là que sont les notes dans
    * les fichiers image, donc ce niveau devra être réajusté au moment de l'affichage,
    * mais cela sera fait uniquement à la lecture du niveau, dans P_Elt.getNiveau() )
    * c'est à dire sa hauteur sur la portée. Les niveaux pairs sont ceux des notes qui
    * sont sur une ligne, les notes dans les interlignes ayant des niveaux impairs.
    */
  public function getNiveau(string $nomNote)
  {
    // Ici, la lettre va indiquer un niveau sur la portée (E2 = 1ere ligne).
    $lettre = array_search($nomNote[0],$this->lettres);
    $octave= intval($nomNote[1]);
    return ($lettre + (8 * $octave));
  }

  /**
    * Retourne l'image correspondant à une note de cette durée.
    * @var duree : exprimée en nombre de croches
    */
  public function getImageNote(int $duree)
  {
    switch ($duree)
    {
      case 1  : return get_croche();
      case 2  :
      case 3  : return get_noire();
      case 4  :
      case 6  : return get_blanche();
      default : return get_ronde();
    }
  }
}
?>