<?php

/** 
 * ClASse d'accès aux données. 
 
 * Utilise les services de la clASse PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la clASse
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb
{
	private static $serveur = 'mysql:host=172.16.203.110';
	private static $bdd = 'dbname=gsbfrais';
	private static $user = 'sio';
	private static $mdp = 'max';
	private static $monPdo;
	private static $monPdoGsb = null;
	/**
	 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
	 * pour toutes les méthodes de la clASse
	 */
	private function __construct()
	{
		PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct()
	{
		PdoGsb::$monPdo = null;
	}
	/**
	 * Fonction statique qui crée l'unique instance de la clASse
		   
	 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
		   
	 * @return l'unique objet de la clASse PdoGsb
	 */
	public static function getPdoGsb()
	{
		if (PdoGsb::$monPdoGsb == null) {
			PdoGsb::$monPdoGsb = new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;
	}
	/**
	 * Retourne les informations d'un Visiteur
		   
	 * @param $login 
	 * @param $mdp
	 * @return l'id, le nom et le prénom sous la forme d'un tableau ASsociatif 
	 */
	public function getInfosVisiteur($login, $mdp)
	{
		$mdp = md5($mdp); // maxime, ajouter
		$req = "SELECT Visiteur.id AS id, Visiteur.nom AS nom, Visiteur.prenom AS prenom FROM Visiteur 
		WHERE Visiteur.login='$login' AND Visiteur.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

	/**
	 * Retourne si l'utlisateur est un visiteur ou un comptable ou le login/mot de passe est incorrect
	 * 
	 * @param $login
	 * @param $mdp
	 * @return compteur 
	 * @author Maxence
	 * */
	public function verifPersonne($login, $mdp)
	{
		$compteur = 0;
		$mdp = md5($mdp);
		$req = "SELECT COUNT(*) AS nbVisiteur FROM Visiteur WHERE login= '" . $login . "' AND mdp= '" . $mdp . "';";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		if ($ligne['nbVisiteur'] != 0) {
			$req = "SELECT COUNT(*) AS nbVisiteur FROM Visiteur WHERE login= '" . $login . "' AND mdp= '" . $mdp . "' AND comptable = 1;";
			$rs = PdoGsb::$monPdo->query($req);
			$ligne = $rs->fetch();
			if ($ligne['nbVisiteur'] != 0) {
				$compteur = 2;
			} else {
				$compteur = 1;
			}
		}
		return $compteur;
	}



	//maxime, function qui modifie dans la bASe de donnee le mdp en mdp chiffrer
	/**
	 * Chiffre le mot de pASse d'un Visiteur ou comptable
	 * @param $mdp
	 * @param $id
	 * @return $rs le resultat de la requete c'est a dire rien d'exploitable
	 * @author Maimexco <email>
	 */
	public function setMdpMd5()
	{

		$req = "UPDATE Visiteur SET mdp = md5(mdp)";
		$rs = PdoGsb::$monPdo->exec($req);
	}


	/**
	 * Retourne sous forme d'un tableau ASsociatif toutes les lignes de frais hors forfait
	 * concernées par les deux arguments
		   
	 * La boucle foreach ne peut être utilisée ici car on procède
	 * à une modification de la structure itérée - transformation du champ date-
		   
	 * @param $idVisiteur 
	 * @param $mois sous la forme aaaamm
	 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau ASsociatif 
	 */
	public function getLesFraisHorsForfait($idVisiteur, $mois)
	{
		$req = "SELECT * FROM LigneFraisHorsForfait WHERE LigneFraisHorsForfait.idVisiteur ='" . $idVisiteur . "' AND LigneFraisHorsForfait.mois = '" . $mois . "' ;";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = COUNT($lesLignes);
		for ($i = 0; $i < $nbLignes; $i++) {
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
		}
		return $lesLignes;
	}
	/**
	 * Retourne le nombre de justificatif d'un Visiteur pour un mois donné
		   
	 * @param $idVisiteur 
	 * @param $mois sous la forme aaaamm
	 * @return le nombre entier de justificatifs 
	 */
	public function getNbjustificatifs($idVisiteur, $mois)
	{
		$req = "SELECT FicheFrais.nbjustificatifs AS nb FROM FicheFrais WHERE FicheFrais.idVisiteur = '" . $idVisiteur . "' AND FicheFrais.mois = '" . $mois . "';";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
	/**
	 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
	 * concernées par les deux arguments
		   
	 * @param $idVisiteur 
	 * @param $mois sous la forme aaaamm
	 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
	 */
	public function getLesFraisForfait($idVisiteur, $mois)
	{
		$req = "SELECT FraisForfait.id AS idFrais, FraisForfait.libelle AS libelle, 
		LigneFraisForfait.quantite AS quantite FROM LigneFraisForfait INNER JOIN FraisForfait 
		ON FraisForfait.id = LigneFraisForfait.idFraisForfait
		WHERE LigneFraisForfait.idVisiteur ='" . $idVisiteur . "' AND LigneFraisForfait.mois='" . $mois . "' 
		order by LigneFraisForfait.idFraisForfait";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
	/**
	 * Retourne tous les id de la table FraisForfait
		   
	 * @return un tableau associatif 
	 */
	public function getLesIdFrais()
	{
		$req = "SELECT id AS idFrais FROM FraisForfait order by FraisForfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
	/**
	 * Met à jour la table LigneFraisForfait
		   
	 * Met à jour la table LigneFraisForfait pour un Visiteur et
	 * un mois donné en enregistrant les nouveaux montants
		   
	 * @param $idVisiteur 
	 * @param $mois sous la forme aaaamm
	 * @param $lesFrais tableau ASsociatif de clé idFrais et de valeur la quantité pour ce frais
	 * @return un tableau ASsociatif 
	 */
	public function majFraisForfait($idVisiteur, $mois, $lesFrais)
	{
		$lesCles = array_keys($lesFrais);
		foreach ($lesCles as $unIdFrais) {
			$qte = $lesFrais[$unIdFrais];
			$req = "UPDATE LigneFraisForfait SET LigneFraisForfait.quantite = $qte
			WHERE LigneFraisForfait.idVisiteur = '" . $idVisiteur . "' AND LigneFraisForfait.mois = '" . $mois . "' AND LigneFraisForfait.idFraisForfait = '" . $unIdFrais . "';";
			PdoGsb::$monPdo->exec($req);
		}
	}
	/**
	 * met à jour le nombre de justificatifs de la table FicheFrais
	 * pour le mois et le Visiteur concerné
		   
	 * @param $idVisiteur 
	 * @param $mois sous la forme aaaamm
	 */
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)
	{
		$req = "UPDATE FicheFrais SET nbjustificatifs = $nbJustificatifs 
		WHERE FicheFrais.idVisiteur = '" . $idVisiteur . "' AND FicheFrais.mois = '" . $mois . "';";
		PdoGsb::$monPdo->exec($req);
	}
	/**
	 * Teste si un Visiteur possède une fiche de frais pour le mois pASsé en argument
		   
	 * @param $idVisiteur 
	 * @param $mois sous la forme aaaamm
	 * @return vrai ou faux 
	 */
	public function estPremierFraisMois($idVisiteur, $mois)
	{
		$ok = false;
		$req = "SELECT COUNT(*) AS nbLignesFrais FROM FicheFrais WHERE FicheFrais.mois = '" . $mois . "' AND FicheFrais.idVisiteur = '" . $idVisiteur . "';";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if ($laLigne['nbLignesFrais'] == 0) {
			$ok = true;
		}
		return $ok;
	}
	/**
	 * Retourne le dernier mois en cours d'un Visiteur
		   
	 * @param $idVisiteur 
	 * @return le mois sous la forme aaaamm
	 */
	public function dernierMoisSaisi($idVisiteur)
	{
		$req = "SELECT max(mois) AS dernierMois FROM FicheFrais WHERE FicheFrais.idVisiteur = '" . $idVisiteur . "';";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}


	/** Pcd pour cloturé tout les fichesfrais du mois precedent des visteurs 
	 * 
	 * @param $mois
	 * 
	 */

	function allUpdatdeLigneFrais($mois)
	{
		$req = "SELECT * FROM Visiteur ;";
		$res = PdoGsb::$monPdo->query($req);
		$allVisiteurs = $res->fetchAll();
		foreach ($allVisiteurs as $Visiteur) {
			$idVisiteur = $Visiteur['id'];
			if ($this->estPremierFraisMois($idVisiteur, $mois)) {
				$dernierMois = $this->dernierMoisSaisi($idVisiteur);
				if ($dernierMois == null) {
					echo "";
				} else {
					$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
					if ($laDerniereFiche['idEtat'] == 'CR') {
						$this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
					}
				}
			}
		}
	}


	public function validerFicheFrais($id, $mois, $dernierMois, $idVisiteur, $idEtat, $idEtatFicheFrais, )
	{

	}



	/**
	 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un Visiteur et un mois donnés
		   
	 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
	 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
	 * @param $idVisiteur 
	 * @param $mois sous la forme aaaamm
	 */
	public function creeNouvellesLignesFrais($idVisiteur, $mois)
	{
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
		if ($laDerniereFiche['idEtat'] == 'CR') {
			$this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
		}
		$req = "insert into FicheFrais(idVisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('" . $idVisiteur . "','" . $mois . "',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach ($lesIdFrais as $uneLigneIdFrais) {
			$unIdFrais = $uneLigneIdFrais['idFrais']; // maxime, F en maj oublier
			$req = "insert into LigneFraisForfait(idVisiteur,mois,idFraisForfait,quantite) 
			values('" . $idVisiteur . "','" . $mois . "','" . $unIdFrais . "',0)";
			PdoGsb::$monPdo->exec($req);
		}
	}
	/**
	 * Crée un nouveau frais hors forfait pour un Visiteur un mois donné
	 * à partir des informations fournies en paramètre
		   
	 * @param $idVisiteur 
	 * @param $mois sous la forme aaaamm
	 * @param $libelle : le libelle du frais
	 * @param $date : la date du frais au format français jj//mm/aaaa
	 * @param $montant : le montant
	 */
	public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant)
	{
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into LigneFraisHorsForfait values(null,'" . $idVisiteur . "','" . $mois . "','$libelle','$dateFr','$montant')"; //maxime, remplacer au debut de value sa " " par null
		PdoGsb::$monPdo->exec($req);
	}
	/**
	 * Supprime le frais hors forfait dont l'id est pASsé en argument
		   
	 * @param $idFrais 
	 */
	public function supprimerFraisHorsForfait($idFrais)
	{
		$req = "delete FROM LigneFraisHorsForfait WHERE LigneFraisHorsForfait.id =$idFrais ";
		PdoGsb::$monPdo->exec($req);
	}
	/**
	 * Retourne les mois pour lesquel un Visiteur a une fiche de frais
		   
	 * @param $idVisiteur 
	 * @return un tableau ASsociatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
	 */
	public function getLesMoisDisponibles($idVisiteur)
	{
		$req = "SELECT FicheFrais.mois AS mois FROM FicheFrais WHERE FicheFrais.idVisiteur ='" . $idVisiteur . "' 
		order by FicheFrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois = array();
		$laLigne = $res->fetch();
		while ($laLigne != null) {
			$mois = $laLigne['mois'];
			$numAnnee = substr($mois, 0, 4);
			$numMois = substr($mois, 4, 2);
			$lesMois["$mois"] = array(
				"mois" => "$mois",
				"numAnnee" => "$numAnnee",
				"numMois" => "$numMois"
			);
			$laLigne = $res->fetch();
		}
		return $lesMois;
	}
	/**
	 * Retourne les informations d'une fiche de frais d'un Visiteur pour un mois donné
		   
	 * @param $idVisiteur 
	 * @param $mois sous la forme aaaamm
	 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
	 */
	public function getLesInfosFicheFrais($idVisiteur, $mois)
	{
		$req = "SELECT FicheFrais.idEtat AS idEtat, FicheFrais.dateModif AS dateModif, FicheFrais.nbJustificatifs AS nbJustificatifs, 
			FicheFrais.montantValide AS montantValide, Etat.libelle AS libEtat FROM  FicheFrais INNER JOIN Etat on FicheFrais.idEtat = Etat.id 
			WHERE FicheFrais.idVisiteur ='" . $idVisiteur . "' AND FicheFrais.mois = '" . $mois . "'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
	/**
	 * Modifie l'état et la date de modification d'une fiche de frais
		   
	 * Modifie le champ idEtat et met la date de modif à aujourd'hui
	 * @param $idVisiteur 
	 * @param $mois sous la forme aaaamm
	 */

	public function majEtatFicheFrais($idVisiteur, $mois, $etat)
	{
		$req = "UPDATE FicheFrais SET idEtat = '" . $etat . "', dateModif = now() 
		WHERE FicheFrais.idVisiteur ='" . $idVisiteur . "' AND FicheFrais.mois = '" . $mois . "'";
		echo $req;
		PdoGsb::$monPdo->exec($req);
	}
}
