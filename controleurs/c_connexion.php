<?php
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'demandeConnexion';
}

$action = $_REQUEST['action'];

switch ($action) {
    case 'demandeConnexion':
        include("vues/v_connexion.php");
        break;

    case 'valideConnexion':
        $login = $_REQUEST['login'];
        $mdp = $_REQUEST['mdp'];
        $verifPersonne = $pdo->verifPersonne($login, $mdp);

        if ($verifPersonne == 0) {
            ajouterErreur("Login ou mot de passe incorrect");
            include("vues/v_erreurs.php");
            include("vues/v_connexion.php");
        } else {
            $visiteur = $pdo->getInfosVisiteur($login, $mdp);
            $id = $visiteur['id'];
            $nom = $visiteur['nom'];
            $prenom = $visiteur['prenom'];
            connecter($id, $nom, $prenom);

            if ($verifPersonne == 1) {
                include("vues/v_sommaire.php");
            } else {
                include("vues/v_sommaireComptable.php");
            }
        }
        break;

    default:
        include("vues/v_connexion.php");
        break;
}
?>
