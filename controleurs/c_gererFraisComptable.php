<?php
include("vues/v_sommaireComptable.php");
$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date("d/m/Y"));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = $_REQUEST['action'];
switch ($action) {
	case 'validerFraisComptable': {
			$ligne = $pdo->getFicheFraisCL();
			include("vues/v_validerFrais.php");
			break;
		}
}
