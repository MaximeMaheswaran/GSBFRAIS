<?php
if (isset($_SESSION)) {
	if ($pdo->verifPersonneId($_SESSION['idVisiteur']) == 1) {
		include("vues/v_sommaire.php");
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
					include("vues/v_listeMois.php");
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
					include("vues/v_etatFrais.php");
					break;
				}
			case 'validerPaiementFrais': {
					if(isset($_POST['id'])) {
						$idVisiteur = $_POST['id'];
						$mois = $_POST['mois'];
						$pdo ->validerPaiementFicheFrais($idVisiteur, $mois);
					}
					$ligne = $pdo->getFicheFraisVA($idVisiteur);
					include("vues/v_listeFraisAttenteDePaiementVisiteur.php");
					break;
				}
		}
	} else {
		header("Location: index.php");
	}
} else {
	header("Location: index.php");
}
