<?php
namespace App\Objet;

use App\Objet\P_Elt;
use App\Objet\P_Clef;
use App\Objet\P_Note;
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

    public function getHauteur(P_note $pNote)
    {
      $elt = new P_Elt($this->clef);
      $niveau = $elt->addNote($pNote->getNom(),1);
      $elt->addAlteration($pNote->getAlteration(), $niveau);
      return $elt->getHauteur();
    }

    /**
     * Donne une note située nbDemiTons en-dessous/au-dessus (négatif/positif)
     * de la note, sans avoir le même nom cette note.
     * Valeur de retour : la note suivie de son altération
     * Exemples : "C3", "A2diese", "G1bemol"
     */
    public function getNoteSelonTonalite(P_Note $pNoteDepart, int $nbDemiTons)
    {
      $txtDebug = "";
      // Calcul de la hauteur de la note de départ
      $hauteurDeDepart = $this->getHauteur($pNoteDepart);

      // Hauteur de la note à trouver
      $hauteurRecherchee = $hauteurDeDepart + $nbDemiTons;
      $txtDebug .= " H(".$pNoteDepart->getNom()." ".$pNoteDepart->getAlteration().")=".$hauteurDeDepart."+".$nbDemiTons."=".$hauteurRecherchee." : ";

      // Pour ne pas chercher au hazard, on va partir de la plus basse note affichable et remonter.
      $pNote = new P_Note();
      $pNote->setOctave(1);
      $lettres = $this->cst->get_lettres();
      $nbLettres = strlen($lettres);
      $alteration = "";
      $indexLettre = 0;
      $continuer = true;
      $i = 0;
      
      while ($continuer)
      {
        $i += 1;
        $changerDoctave = false; // Le mettre à true pour passer à l'octave suivant.
        $passerDesNotes = 0;     // Indique de combien de notes il faut avancer.
        $pNote->setLettre($lettres[$indexLettre]);
        $pNote->setAlteration($alteration);
        $alteration = "";
        $diffHauteur = $hauteurRecherchee - $this->getHauteur($pNote);
        $txtDebug .= " h(".$pNote->getNom()." ".$pNote->getAlteration().")=".$this->getHauteur($pNote)."/".$hauteurRecherchee."=".$diffHauteur."...";

        // On essaie d'abord d'approcher du résultat en sautant plusieurs notes d'un coup.
        if ($diffHauteur > 12) { $changerDoctave = true; }
        else
        {
          if ($diffHauteur > 7) $passerDesNotes = 3; 
          else
          {
            if ($diffHauteur > 1) { $passerDesNotes = 1; }
            else
            {
              // Trouvé ?
              if ($diffHauteur == 0)
              {
                if (($pNote->getNom() == $pNoteDepart->getNom()) && ($nbDemiTons == 0))
                {
                  $passerDesNotes = 1;
                  $alteration = $this->cst->get_bemol();
                }
                else return $pNote;
              }
              // Recherche plus fine
              if ($diffHauteur == 1)
              {
                // Dans les cas où la note n'est ni un mi ni un si (la touche suivante est noire)
                if (strpos("EB",$pNote->getLettre()) === false)
                {
                  // Il y avait un dièse ? Passer à la note suivante.
                  // Dans les autres cas, mettre un dièse.
                  if ($pNote->getAlteration() == $this->cst->get_diese()) { $passerDesNotes = 1; }
                  else if ($pNote->getAlteration() != $this->cst->get_bemol()) { $alteration = $this->cst->get_diese(); }
                }
                // Cas du mi et du si
                else
                {
                  if ($pNote->getAlteration() == $this->cst->get_diese()) { $passerDesNotes = 1; }
                  else { $alteration = $this->cst->get_diese(); }
                }
              }
              else
              {
                if ($diffHauteur == -1)
                {
                  if (strpos("CF",$pNote->getLettre()) === false) $alteration = $this->cst->get_bemol();
                }
              }
            }
          }
        }

        if ($passerDesNotes > 0)
        {
          $txtDebug .= "  diff=".$diffHauteur." => passer ".$passerDesNotes." notes.  ";
          $indexLettre += $passerDesNotes;
          if ($indexLettre >= $nbLettres)
          {
            $indexLettre -= $nbLettres;
            $changerDoctave = true;
          }
        }
        if ($changerDoctave) $pNote->setOctaveSuivant();
        //if ($changerDoctave) print_r(" changement d'octave ");
        // Securité en cas de bug boucle infinie
        if ($i > 50) { $continuer = false; print_r($txtDebug . "  boucle infinie:diff=".$diffHauteur.". "); }
      }
      return $pNote; // Ce code n'est théoriquement jamais atteint.
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

    /**
     * Ajouter une altération sur l'armature.
     */
    public function addAlteration(string $nom, string $alteration)
    {
      $elt = new P_Elt($this->clef); // Ceci définit la clé du P_Elt.

      // Récupérer le niveau de cette note :
      $niveau = $this->cst->getNiveau($nom);
      // Ajouter l'altération :
      $elt->addAlteration($alteration, $niveau);
      $elt->rendreEtroit();

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