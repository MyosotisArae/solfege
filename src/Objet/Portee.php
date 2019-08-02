<?php
namespace App\Objet;

use App\Objet\P_Elt;
use App\Objet\P_Clef;
use App\Objet\P_image;

/**
 * Portee
 *
 */
class Portee
{
    public function __construct(string $nomCle="")
    {
      // Récupérer l'instance du singleton qui fournit les noms de fichiers image.
      $this->cst = P_image::getInstance();
      $this->elements = array();
      $this->addCle(nomCle);
    }

    // Cette propriété permet d'accéder aux constantes dans P_image.
    private $cst;

    /**
     * @var P_Clef
     */
    private $clef; // P_Clef utilisée pour cette portée, null s'il n'y a pas besoin de clé.

    /**
     * @var array
     */
    private $elements; // Liste de P_Elt

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getElements(): ?array
    {
        return $this->elements;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Fonctions                                    //
    ///////////////////////////////////////////////////////////////////////////////

    /**
      * Création du premier élément affichable dans la portée : la clé.
      */
    public function addCle(string $nomCle)
    {
      $this->clef = $this->cst->getCle(nomCle);
      if ($this->clef != null)
      {
        // La portée a bien une clé. On l'ajoute aux figures à afficher.
        $elt = new P_Elt($this->clef); // Ceci définit la clé du P_Elt.
        // Le P_Elt est lui-même une clé, donc on ajoute cette figure à sa liste :
        $elt->addFigure($this->clef);
        $this->elements[] = $elt;
      }
    }

    /**
      * Ajoute une figure de type "note" à la portée, avec les signes qui
      * l'accompagnent éventuellement.
      * @var nom : les noms des notes sont donnés par rapport à leur position
      *            sur la portée. Seule la clé permettra d'associer un nom a
      *            cette note. La première note affichable sera A1. La note
      *            située dans le premier interligne (celui du bas) sera F2.
      *            (Ce sera ma convention personnelle. Aucune idée si ça
      *            correspond à la hauteur théorique de F2. De plus, dans ma
      *            notation, "C" est un do en clé de sol, mais un mi en clé
      *            de fa. Moi, ça m'arrange comme ça. N'en parlez à personne.)
      * @var alteration : nom de l'altération dans P_image
      * @var duree : duree en nombre de croches
      * exemple d'appel pour une blanche en do (grâve) diese :
      *     addNote("C2",$this->cst->get_diese(),4)
      */
    public function addNote(string $nom, string $alteration, int $duree)
    {
      $elt = new P_Elt($this->clef); // Ceci définit la clé du P_Elt.

      // Ajouter la note et récupérer son niveau :
      $niveau = $elt->addNote($nom);

      // Ajouter l'altération éventuelle :
      $elt->addAlteration($alteration, $niveau);

      // Ajouter le point éventuel :
      $elt->addPoint($duree, $niveau);
    }

}
?>