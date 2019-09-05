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
          new TwigFilter('retourChariot', [$this, 'retourChariot']),
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
      return $resultat;
    }

    /**
     * Filtre pour transformer tous les doubles espaces en retour chariot.
     */
    public function retourChariot(string $txt)
    {
      return str_replace("  ","<br>",$txt);
    }
}
?>