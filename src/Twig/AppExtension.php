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
            new TwigFunction('getStyleClavier', [$this, 'getStyleClavier']),
        ];
    }

    public function getMemberConnected(SessionInterface $session)
    {
        return $session->get('memberConnected')->getUsername();
    }

    /**
     * Retourne le style complet (avec le style='') pour l'élément cité.
     */
    public function getStyleClavier($x, $reduction, $typeElement)
    {
        $txt = "style='left:";
        if ($typeElement == 1)
        {
            // Type touche ou pastille blanche
            $txt .= $x . "rem; width:" . strval(72*$reduction) . "rem; height:" .strval(303*$reduction) . "rem;";
        }
        if ($typeElement == 2)
        {
            // Type touche ou pastille noire
            $txt .= $x . "rem; width:" . strval(30*$reduction) . "rem; height:" .strval(179*$reduction) . "rem;";
        }
        if ($typeElement == 3)
        {
            // Type ton ou demi ton
            $txt .= strval($x-(25*$reduction)) . "rem; top:" . strval(220*$reduction) . "rem; width:" . strval(72*$reduction) . "rem; height:" .strval(72*$reduction) . "rem;";
        }
        $txt .= "'";
        return $txt;
    }

}
?>