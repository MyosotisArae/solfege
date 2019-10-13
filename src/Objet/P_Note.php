<?php
namespace App\Objet;

/**
 * P_Note
 *
 * Ceci est juste l'objet utilisé par les méthodes getHauteur et
 * getNoteSelonTonalite de la classe Portee.
 *
 */
class P_Note
{
    /**
     * nomImage est une des constantes présentes dans P_constantes
     * niveau est l'ordonnée de la figure
     */
    public function __construct()
    {
        $this->nom = "";
        $this->alteration = "";
    }

    /**
     * Début du nom de la note  : une lettre entre A et G
     * @var string
     */
    protected $lettre;

    /**
     * Octave  : un chiffre pour l'octave.
     * @var int
     */
    protected $octave;

    /**
     * Altération de la note  : diese, bemol, becarre ou vide
     * @var string
     */
    protected $alteration;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function getNom(): ?string
    {
        return $this->lettre.$this->octave;
    }

    public function getLettre(): ?string
    {
        return $this->lettre;
    }

    public function getOctave(): ?int
    {
        return $this->octave;
    }

    public function getAlteration(): ?string
    {
        return $this->alteration;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setLettre(string $l)
    {
        $this->lettre = $l;
        return $this;
    }

    public function setOctave(int $o)
    {
        $this->octave = $o;
        return $this;
    }

    public function setOctaveSuivant()
    {
        $this->octave += 1;
        return $this;
    }

    public function setAlteration(string $a)
    {
        $this->alteration = $a;
        return $this;
    }

}
?>