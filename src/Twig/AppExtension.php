<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Service\Utilitaires;

class AppExtension extends AbstractExtension
{
    public function  __construct(Utilitaires $util)
    {
      $this->util = $util;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('duree', [$this, 'duree']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('ongletActif', [$this, 'getOngletActif']),
        ];
    }

    public function getOngletActif(string $idOnglet)
    {
      if (!$this->util->estRenseigneSESSION("ongletActif"))
      {
        $_SESSION["ongletActif"] = "A";
      }
      if ($_SESSION["ongletActif"] == $idOnglet) return " active";
      return "";
    }
    
    /**
      * Filtre pour afficher correctement une duree, initialement donnee en minutes
      */
    public function duree(int $enMinutes)
    {
      $resultat = "";
      if ($enMinutes < 60) { $resultat = strval($enMinutes)." mn"; }
      else
      {
        $nbHeures = 0;
        $nbminutes = $enMinutes;
        while ($nbminutes >= 60)
        {
          $nbminutes -= 60;
          $nbHeures += 1;
        }
        $resultat = strval($nbHeures) . "h";
        if ($nbminutes > 0) $resultat .= " ".strval($nbminutes)."mn";
      }

      // Commentaire sur le fait que cette duree ne soit qu'une estimation :
      $listeDeCommentaires = array
      (
        array("avant" => " approximative : ", "apres" => ""),
        array("avant" => " estimée à ", "apres" => " environ"),
        array("avant" => " : ", "apres" => ", à un chouïa près"),
        array("avant" => " : ", "apres" => " (mais ça dépend surtout de vous)"),
        array("avant" => " : à peu près ", "apres" => ""),
        array("avant" => " : ", "apres" => " (mais c'est variable)"),
        array("avant" => " : ", "apres" => ", enfin la plupart du temps"),
        array("avant" => " : ", "apres" => ", quoique, des fois ..."),
        array("avant" => ", à la louche, disons ", "apres" => ""),
        array("avant" => " : environ ", "apres" => ", après, bin ... vous savez ce que c'est !"),
        array("avant" => " : ", "apres" => " (mais, bon, en même temps ...)"),
        array("avant" => " moyenne constatée : ", "apres" => ""),
        array("avant" => " : ", "apres" => ", avec une marge d'erreur de 10% (ou 15)"),
        array("avant" => " : ", "apres" => ", plus ou moins"),
        array("avant" => " : ", "apres" => ", à vue de nez")
      );
      // Pour éviter que les durées min et max aient le même commentaire, on stocke dans la session le dernier numéro, et on l'évite.
      $dernier = -1;
      if ($this->util->estRenseigneSESSION("dernierCommentaireDuree")) { $dernier = $_SESSION["dernierCommentaireDuree"]; }
      $indice = $dernier;
      while ($dernier == $indice) { $indice = random_int(0,count($listeDeCommentaires)-1); }
      $_SESSION["dernierCommentaireDuree"] = $indice;
      $resultat = $listeDeCommentaires[$indice]["avant"] . $resultat . $listeDeCommentaires[$indice]["apres"];

      return $resultat;
    }

}
?>