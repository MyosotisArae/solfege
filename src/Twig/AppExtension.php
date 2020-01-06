<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Service\Utilitaires;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

    public function getFunctions()
    {
        return [
            new TwigFunction('getUserName', [$this, 'getMemberConnected']),
        ];
    }

    public function getMemberConnected(SessionInterface $session)
    {
        return $session->get('memberConnected')->getUsername();
    }

}
?>