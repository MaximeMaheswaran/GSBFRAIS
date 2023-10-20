<form method="POST" action="index.php?uc=gererFrais&action=validerMajFraisForfait">
    <div class="corpsForm">

        <fieldset>
            <legend>Eléments forfaitisés
            </legend>
            <?php
            foreach ($lesFraisForfait as $unFrais) {
                $idFrais = $unFrais['idFrais']; //maxime F en maj oublier
                $libelle = $unFrais['libelle'];
                $quantite = $unFrais['quantite'];
            ?>
                <p>
                    <label for="idFrais"><?php echo $libelle ?></label>
                    <input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]" size="10" maxlength="5" value="<?php echo $quantite ?>">
                </p>

            <?php
            }
            ?>
        </fieldset>
    </div>
    <fieldset>
        <legend>Eléments non forfaitisés
        </legend>
        <table class="listeLegere">
            <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class="montant">Montant</th>
                <th class="action">&nbsp;</th>
            </tr>

            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = $unFraisHorsForfait['libelle'];
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id'];
            ?>
                <tr>
                    <td> <?php echo $date ?></td>
                    <td><?php echo $libelle ?></td>
                    <td><?php echo $montant ?></td>
                    <td>
                        <form action="index.php?uc=gererFraisComptable&action=confirmerFrais" method="post">.
                            <input type="hidden" name="idVisiteur" value="<?php echo $idVisiteur ?>">
                            <input type="submit" value="Confirmer cet élement">
                        </form>
                        <br> <br>
                        <form action="index.php?uc=gererFrais&action=supprimerFrais" method="post">
                            <input type="hidden" name="idVisiteur" value="<?php echo $idVisiteur ?>">
                            <input type="submit" value="Supprimer cet élement">
                        </form>
                    </td>
                </tr>
            <?php

            }
            ?>

        </table>
    </fieldset>
    <div class="piedForm">
        <p>
            <input id="ok" type="submit" value="Valider" size="20" />
            <input id="annuler" type="reset" value="Effacer" size="20" />
        </p>
    </div>
</form>