<?php
require_once("include/fct.inc.php");
require_once ("include/class.pdogsb.inc.php");
include("vues/v_entete.php") ;
session_start();
// maxime, besoin du mois pour update tous les fichesfrais
$mois = getMois(date("d/m/Y"));

$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
$pdo -> allUpdatdeLigneFrais($mois);

if(!isset($_REQUEST['uc']) || !$estConnecte){
     $_REQUEST['uc'] = 'connexion';
}	 
$uc = $_REQUEST['uc'];
switch($uc){
	case 'connexion':{
		include("controleurs/c_connexion.php");break;
	}
	case 'gererFrais' :{
		include("controleurs/c_gererFrais.php");break;
	}
	case 'etatFrais' :{
		include("controleurs/c_etatFrais.php");break; 
	}
	case 'gererFraisComptable' :{
		include("controleurs/c_gererFraisComptable.php");break; 

	}	case 'etatFraisComptable' :{
		include("controleurs/c_etatFraisComptable.php");break; 
	}
}
include("vues/v_pied.php") ;
?>

