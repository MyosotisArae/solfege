<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Service\Utilitaires;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
          new TwigFilter('retourChariot', [$this, 'retourChariot']),
        ];
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