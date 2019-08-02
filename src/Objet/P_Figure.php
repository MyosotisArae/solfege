<?php
namespace App\Objet;

use App\Objet\P_image;

/**
 * P_Figure
 *
 * Une figure est uniquement un élément à afficher. Il ne connait que
 * le niveau auquel son image doit être affichée.
 * L'objet complet (une clé, une note, un silence,...) est le P_Elt.
 * Lui seul connait la hauteur de la note, la clé, ...etc.
 *
 */
class P_Figure
{
    /**
     * nomImage est une des constantes présentes dans P_image
     * niveau est l'ordonnée de la figure
     */
    public function __construct(string $nomImage="")
    {
      // Récupérer l'instance du singleton qui fournit les noms de fichiers image.
      $this->cst = P_image::getInstance();
      $this->image = $nomImage;
    }

    // Cette propriété permet d'accéder aux constantes dans P_image.
    private $cst;

    /**
     * Nom du fichier image (sans l'extension : ce seront tous des .gif)
     * Utiliser uniquement les constantes présentes dans la classe P_Image
     * @var string
     */
    private $image;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getNiveau(): ?int
    {
        // Les clés, les silences, les portées vides, sont au "niveau de base", 0.
        // Cette fonction doit être surchargée pour les éléments à placer :
        // notes, points, altérations (classe P_Aplacer)
        return 0;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////////
    //                              Fonctions                                    //
    ///////////////////////////////////////////////////////////////////////////////

}
?>