<?php
namespace App\Objet;

use App\Objet\P_Figure;
use App\Objet\P_Aplacer;
use App\Objet\P_Clef;
use App\Objet\P_image;

/**
 * P_Elt
 *
 * Cette classe décrit entièrement un objet (note, silence, clé, ...) qui
 * peut être représenté sur une portée.
 * Il regroupe plusieurs objets graphiques P_Figure (ex : portée+dièse+FA),
 * ces figures devant toutes être affichées à la même  abscisse, mais pas
 * forcément à la même ordonnée (une portée est en 0, mais pas un mi), donc
 * chaque figure doit savoir à quel niveau elle s'affiche.
 * P_Elt connait aussi les caractéristiques non graphiques de l'objet global
 * qu'il représente : hauteur pour une note, durée, ...etc.
 */
class P_Elt
{
    public function __construct(P_Clef $clef)
    {
      // Récupérer l'instance du singleton qui fournit les noms de fichiers image.
      $this->cst = P_image::getInstance();
      // Liste des P_Figure à afficher pour cet élément.
      $this->figures = array();
      $this->clef = $clef;

      // Lors de la création d'un P_Elt, on précise d'abord la clé, puis
      // l'altération et la note ("F2" par exemple), et . Chacune des 3
      // influe sur la hauteur (nombre de tons). Le niveau (position
      // verticale de la figure) est déterminé uniquement grâce au nom de
      // la note. Dans un P_Elt, il y a toujours une portée est, affichée
      // au niveau 0, mais la note, l'altération et le point utilisent ce
      // niveau, qui va être calculé au fil des ajouts de figures.
      // Ca commence par la clé :
      $this->hauteur = $clef->getModificateur();

      $this->duree = 0;
    }

    // Cette propriété permet d'accéder aux constantes dans P_image.
    private $cst;

    // La hauteur permettra donc compter les tons entre deux notes.
    private $hauteur;

    // Durée en croches
    private $duree;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getFigures(): ?array
    {
        return $this->figures;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Fonctions                                    //
    ///////////////////////////////////////////////////////////////////////////////

    /**
      * La portée doit appeler cette fonction en premier afin de pouvoir
      * appliquer le niveau sur les autres figures (altération, point)
      * @var nomNote : lettre + chiffre (ex: "F2")
      * RAPPEL : ce nom fournit la position de la note, pas sa valeur.
      * ex: E1 est la note située sur la première ligne (ligne du bas),
      * et sera nommée mi en clé de sol, mais sol en clé de fa.
      *
      * @var duree : exprimée en nombre de croches
      *
      * @return niveau
      */
    public function addNote(string $nomNote, int $duree)
    {
      $niveau += $this->cst->getNiveau($nomNote);
      $this->duree = $duree;
      $this->hauteur += $this->cst->getHauteur($nomNote, $this->clef->getModificateur());
      $n = new P_Aplacer($this->cst->getImageNote($duree), $niveau);
      $this->figures[] = $n;
      
      return $niveau;
    }

    public function addAlteration(string $nomImage, int $niveau)
    {
        if (count($nomImage) < 1) return; // Aucune altération
        $a = new P_Aplacer($nomImage, $niveau);
        $this->figures[] = $a;
        $this->hauteur  += $this->getModificateurAlteration();
    }
    
    public function getModificateurAlteration(string $nomImage): ?int
    {
        if ($nomImage == $this->cst->get_bemol()) return -1;
        if ($nomImage == $this->cst->get_diese()) return 1;
        return 0;
    }

    /**
      * Ajoute un point après la figure si cela est nécessaire.
      */
    public function addPoint(int $duree, int $niveau)
    {
      // Durées nécessitant un point : noire pointée (3), blanche pointée (6)
      if (($duree ==3) or ($duree ==6))
      {
        // Le nom de l'image n'est pas le même si le point est dans un interligne
        // ou sur une ligne (c'est à dire juste un peu au-dessus de la ligne).
        // C'est la classe P_Image qui va calculer quelle image utiliser.
        $p = new P_Aplacer($this->cst->getPoint($niveau), $niveau);
        $this->figures[] = $p;
      }
    }
}
?>