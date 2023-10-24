<?php

if (isset($_SESSION)) {
	if ($pdo->verifPersonneId($_SESSION['idVisiteur']) == 2) {
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
			case 'validerMajFicheFrais': {
					$mois = $_POST['lstMois'];
					$idVisiteurUse = $_POST['idVisiteurUse'];
					$lesFrais = $_REQUEST['lesFrais'];
					if (lesQteFraisValides($lesFrais)) {
						$pdo->validerFicheFrais($idVisiteurUse, $mois, $lesFrais);
						ajouterMsg("Fiche Frais valide");
						include("vues/v_msg.php");
					} else {
						ajouterErreur("Les valeurs des frais doivent être numériques");
						include("vues/v_erreurs.php");
					}
					break;
				}
			case 'AttenteFraisComptable' : {
				$ligne = $pdo->getFicheFraisVA();
				include('vues/v_listeFraisAttenteDePaiement.php');
				break;
			}
			case 'RembourserFraisComptable' : {
				$ligne = $pdo->getFicheFraisRB();
				include('vues/v_listeFraisRembourser.php');
				break;
			}

		}
	} else {
		header("Location: index.php");
	}
} else {
	header("Location: index.php");
}