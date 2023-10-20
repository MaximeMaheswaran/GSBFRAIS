<div id="contenu">
    <?php
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $idVisiteur = $_POST['idVisiteur'];
    ?>
    <h2>Fiche frais de <?php echo $nom . ' '  . $prenom ?></h2>
    <h3>Mois à sélectionner : </h3>
    <form action="index.php?uc=etatFraisComptable&action=voirEtatFraisComptable" method="post">
        <input type="hidden" name="idVisiteur" value="<?php echo $idVisiteur ?>">
        <input type="hidden" name="nom" value="<?php echo $nom ?>">
        <input type="hidden" name="prenom" value="<?php echo $prenom ?>">
        <div class="corpsForm">

            <p>

                <label for="lstMois" accesskey="n">Mois : </label>
                <select id="lstMois" name="lstMois">
                    <?php
                    foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee =  $unMois['numAnnee'];
                        $numMois =  $unMois['numMois'];
                        if ($mois == $moisASelectionner) {
                    ?>
                            <option selected value="<?php echo $mois ?>"><?php echo  $numMois . "/" . $numAnnee ?> </option>
                        <?php
                        } else { ?>
                            <option value="<?php echo $mois ?>"><?php echo  $numMois . "/" . $numAnnee ?> </option>
                    <?php
                        }
                    }

                    ?>

                </select>
            </p>
        </div>
        <div class="piedForm">
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
                <input id="annuler" type="reset" value="Effacer" size="20" />
            </p>
        </div>

    </form>