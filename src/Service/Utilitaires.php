<?php
namespace App\Service;

class Utilitaires
{
    public function estRenseignePOST($NomDeVariablePostee)
    {
    	if (!isset($_POST)) return false;
    	if (!isset($_POST[$NomDeVariablePostee])) return false;
    	return $this->estNonNul($_POST[$NomDeVariablePostee]);
    }

    public function estRenseigneGET($NomDeVariablePostee)
    {
    	if (!isset($_GET)) return false;
    	if (!isset($_GET[$NomDeVariablePostee])) return false;
    	return $this->estNonNul($_GET[$NomDeVariablePostee]);
    }

    public function estNonNul($variable)
    {
    	if (is_null($variable)) return false;
    	$var = $this->suprimerEspacesApostropheEtGuillemets(strval($variable));
    	if ($var == '0') return false;
    	if (strlen($var) == 0) return false;
    	return true;
    }

    function suprimerEspacesApostropheEtGuillemets($chaine)
    {
    	$resultat = trim($chaine);
    	$resultat = str_replace("'","&#39;",$resultat);
    	$resultat = str_replace('"',"&quot;",$resultat);
    	return $resultat;
    }
}
?>