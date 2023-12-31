﻿<?php
if (isset($_SESSION)) {
	if ($pdo->verifPersonneId($_SESSION['idVisiteur']) == 2) {
		include("vues/v_sommaireComptable.php");
		$action = $_REQUEST['action'];
		$idVisiteur = $_SESSION['idVisiteur'];
		switch ($action) {
			case 'selectionnerMois': {
					$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
					// Afin de sélectionner par défaut le dernier mois dans la zone de liste
					// on demande toutes les clés, et on prend la première,
					// les mois étant triés décroissants
					$lesCles = array_keys($lesMois);
					$moisASelectionner = $lesCles[0];
					include("vues/v_tabFicheFraisVisiteur.php");
					break;
				}
			case 'voirEtatFrais': {
					$leMois = $_REQUEST['lstMois'];
					$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
					$moisASelectionner = $leMois;
					include("vues/v_listeMois.php");
					$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
					$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
					$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
					$numAnnee = substr($leMois, 0, 4);
					$numMois = substr($leMois, 4, 2);
					$libEtat = $lesInfosFicheFrais['libEtat'];
					$montantValide = $lesInfosFicheFrais['montantValide'];
					$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
					$dateModif = $lesInfosFicheFrais['dateModif'];
					$dateModif = dateAnglaisVersFrancais($dateModif);
					include("vues/v_etat.php");
					break;
				}

			case 'voirEtatFraisComptable': {

					$mois = $_POST['lstMois'];
					$idVisiteur = $_POST['idVisiteur'];
					$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
					$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
					include("vues/v_listeFraisForfaitComptable.php");
					break;
				}
			case 'tabFicheFraisVisiteur': {
					$lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
					include("vues/v_tabFicheFraisVisiteur.php");
					break;
				}
		}
	} else {
		header("Location: index.php");
	}
} else {
	header("Location: index.php");
}
