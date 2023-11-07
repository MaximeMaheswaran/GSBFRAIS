<div id="contenu">
    <h2>Liste de fiches de frais a payer</h2>
    <!-- Tableau de fiches de frais -->
    <table border="1">
        <tr>
            <th>Visiteur</th>
            <th>Justificatifs</th>
            <th>Montant</th>
            <th>Date de modification</th>
            <th>Etat</th>
            <th></th>
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
                <td>
                    <?php echo $nom . ' ' . $prenom ?>
                </td>
                <td>
                    <?php echo $nbJustificatifs; ?>
                </td>
                <td>
                    <?php echo $montantValide; ?>
                </td>
                <td>
                    <?php echo $dateModif; ?>
                </td>
                <td>
                    <?php echo "En attente de Paiement"; ?>
                </td>
                <td>
                    <form action="index.php?uc=etatFrais&action=validerPaiementFrais" method="post">
                        <input type="hidden" name="id" value="<?php echo $idVisiteur ?>">
                        <input type="hidden" name="mois" value="<?php echo $mois ?>">
                        <input type="submit" value="Valider le paiement">
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>

</div>