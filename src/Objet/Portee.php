<?php
namespace App\Objet;

use App\Objet\P_Elt;
use App\Objet\P_Clef;
use App\Objet\P_constantes;

/**
 * Portee
 *
 */
class Portee
{
    public function __construct(string $nomCle="")
    {
      // Récupérer l'instance du singleton qui fournit les noms de fichiers image.
      $this->cst = new P_constantes();
      $this->elements = array();
      $this->echelle = 1;
      $this->addCle($nomCle);
    }

    // Cette propriété permet d'accéder aux constantes dans P_constantes.
    private $cst;

    /**
     * 
     * @var float
     */
    private $echelle;


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

    public function getCle(): ?P_Clef
    {
        return $this->clef;
    }

    public function getElements(): ?array
    {
        return $this->elements;
    }

    public function getEchelle(): ?float
    {
        return $this->echelle;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setEchelle(float $echelle)
    {
        $this->echelle = $echelle;
    }


    ///////////////////////////////////////////////////////////////////////////////
    //                              Fonctions                                    //
    ///////////////////////////////////////////////////////////////////////////////

    /**
      * Création du premier élément affichable dans la portée : la clé.
      */
    public function addCle(string $nomCle)
    {
      $this->clef = new P_Clef($nomCle);
      if (strlen($nomCle) > 1)
      {
        // La portée a bien une clé. On l'ajoute aux figures à afficher.
        $elt = new P_Elt($this->clef); // Ceci définit la clé du P_Elt.
        // Le P_Elt est lui-même une clé, donc on ajoute cette figure à sa liste :
        $elt->addSigne($nomCle);

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
      * @var alteration : nom de l'altération dans P_constantes (altération de la 1ere note, $nom)
      * @var duree : duree en nombre de croches
      * exemple d'appel pour une blanche en do (grâve) diese :
      *     addNote("C2",$this->cst->get_diese(),4)
      * @var autresNotes : noms des notes supplémentaires superposées (pour faire un accord)
      */
    public function addNote(string $nom, string $alteration, int $duree, $autresNotes = [])
    {
      $elt = new P_Elt($this->clef); // Ceci définit la clé du P_Elt.

      // Ajouter la note et récupérer son niveau :
      $niveau = $elt->addNote($nom, $duree);
      // Ajouter l'altération éventuelle :
      $elt->addAlteration($alteration, $niveau);

      // Ajouter le point éventuel :
      $elt->addPoint($duree, $niveau);

      // Ajouter les barres éventuelles :
      $elt->addBarres($niveau);

      foreach ($autresNotes as $noteAccord)
      {
        $niveau = $elt->addNote($noteAccord, $duree);
        $elt->addPoint($duree, $niveau);
        $elt->addBarres($niveau);
      }

      $this->elements[] = $elt;
    }

    public function addSilence(string $nomImage, int $duree)
    {
      $elt = new P_Elt($this->clef); // Ceci définit la clé du P_Elt.

      $elt->addSigne($nomImage);

      $elt->addPoint($duree, $this->getNiveauSilence($duree));

      $this->elements[] = $elt;
    }

    public function addSigne(string $nomImage)
    {
      $elt = new P_Elt($this->clef); // Ceci définit la clé du P_Elt.

      $elt->addSigne($nomImage);

      $this->elements[] = $elt;
    }

    public function addElt(P_Elt $elt)
    {
      $this->elements[] = $elt;
    }

    private function getNiveauSilence(int $duree)
    {
      // Soupir pointé. Le point doit être au niveau du LA.
      if ($duree == 3) return $this->cst->getNiveau("A2");
      
      // demie pause pointée. Le point doit être au niveau du SI.
      if ($duree == 6) return $this->cst->getNiveau("B2");
      
      return 0;
    }

}
?>