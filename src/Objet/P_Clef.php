<?php
namespace App\Objet;

use App\Objet\P_Figure;

/**
 * P_Clef
 *
 */
class P_Clef extends P_Figure
{
    public function __construct(string $nomImage="cle_sol")
    {
      parent::__construct($nomImage);
      $this->setModificateur();
    }

    /**
     * Modificateur de niveau : nombre de niveaux au-dessus/en-dessous de
     * la même figure dans cette clé et en clé de sol.
     * Ex : en clé de sol, A2 = la, mais en clé de fa, A2 = do, et il faut
     * descendre au niveau F2 pour avoir le la.
     * @var int
     */
    private $modificateur;

    ///////////////////////////////////////////////////////////////////////////////
    //                              Getteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    // Nombre de niveaux de différence par rapport à la clé de sol.
    public function getModificateur(): ?int
    {
        return $this->modificateur;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Setteurs                                     //
    ///////////////////////////////////////////////////////////////////////////////

    public function setModificateur()
    {
        if ($this->image == $this->cst->get_cle_sol())
        {
          $this->modificateur = 0;
          return; 
        }
        if ($this->image == $this->cst->get_cle_fa())
        {
          // Une même note (un sol par exemple) aura 2 niveaux de moins en clé de fa (sol=E2)
          // qu'en clé de sol (sol=G2) sur la portée.
          $this->modificateur = 2;
          return; 
        }
        $this->modificateur = 0;
    }

    ///////////////////////////////////////////////////////////////////////////////
    //                              Fonctions                                    //
    ///////////////////////////////////////////////////////////////////////////////

}
?>