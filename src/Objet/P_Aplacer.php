<?php
namespace App\Objet;

use App\Objet\P_Figure;
use App\Objet\P_image;

/**
 * Aplacer
 *
 */
class P_Aplacer extends P_Figure
{
    /**
     * nomImage est une des constantes présentes dans P_image
     * niveau est l'ordonnée de la figure
     */
    public function __construct(string $nomImage = "", int $niveau = 0)
    {
      parent::__construct($nomImage);
      $this->niveau = $niveau;
    }

    /**
      * Position verticale de la figure :
      * 0 est la position standard (celle des clés, de la portée, des silences)
      * Les notes et leurs point et altération éventuels ont une hauteur variable
      * que calculera la classe P_Aplacer. La valeur de niveau est un entier pair
      * pour les notes sur les lignes (0,2,4,6,8 en partant du bas), et on ajoute
      * 1 pour les notes sur les interlignes. Les notes situées sous la ligne du
      * bas ont donc un niveau négatif.
      * Le niveau 0 correspond à F2, car c'est la position de toutes les notes,
      * altérations et points dans les fichiers image (premier interligne).
      * @var int
      */
    protected $niveau;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getNiveau(): ?int
    {
      // Le niveau ne sert qu'au niveau graphique. Or l'image des notes, des
      // des altérations et des points les représente dans le premier interligne,
      // en F2. Il faut donc que F2 corresponde à 0. C'est pourquoi on soustrait
      // la constante decalageGraphique (=niveau de F2) au niveau.
      return ($this->niveau - $this->cst->get_decalageGraphique());
    }

}
?>