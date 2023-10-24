<div id="contenu">
    <h2>Liste de fiches de frais à valider <?php echo $numMois . "-" . $numAnnee ?></h2>
    <!-- Tableau de fiches de frais -->
    <table border="1">
        <tr>
            <th>Visiteur</th>
            <th>Justificatifs</th>
            <th>Montant</th>
            <th>Date de modification</th>
            <th>Etat</th>
        </tr>
        <?php
        foreach ($ligne as $uneLigne) {
            $idVisiteur = $uneLigne['idVisiteur'];
            $nom = $uneLigne['nom'];
            $prenom = $uneLigne['prenom'];
            $mois = $uneLigne['mois'];
            $nbJustificatifs = $uneLigne['nbJustificatifs'];
            $montantValide = $uneLigne['montantValide'];
            $dateModif = $uneLigne['dateModif'];
            $idEtat = $uneLigne['idEtat'];
        ?>
            <tr>
                <td><?php echo $nom . ' ' . $prenom ?></td>
                <td> <?php echo $nbJustificatifs; ?> </td>
                <td> <?php echo $montantValide; ?> </td>
                <td> <?php echo $dateModif; ?> </td>
                <td> <?php echo "En attente de Paiement"; ?> </td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>