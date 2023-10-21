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
                <td>
                    <form action='index.php?uc=etatFraisComptable&action=tabFicheFraisVisiteur' method='post'>
                        <input type='hidden' name='idVisiteur' value='<?php echo $idVisiteur; ?>'>
                        <input type='hidden' name='nom' value='<?php echo $nom; ?>'>
                        <input type='hidden' name='prenom' value='<?php echo $prenom; ?>'>
                        <input type='submit' id='buttonNoCSS' value='<?php echo $nom . ' ' . $prenom ?>'>
                    </form>
                </td>
                <td> <?php echo $nbJustificatifs; ?> </td>
                <td> <?php echo $montantValide; ?> </td>
                <td> <?php echo $dateModif; ?> </td>
                <td> <?php echo $idEtat; ?> </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <!-- Bouton de soumission du formulaire -->
    <input type="submit" name="valider" value="Valider">
</div>