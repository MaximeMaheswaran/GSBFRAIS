    <!-- Division pour le sommaire -->
    <div id="menuGauche">
      <div id="infosUtil">

        <h2>

        </h2>

      </div>
      <ul id="menuList">
        <li>
          Comptable : <?php echo $_SESSION['prenom'] . "  " . $_SESSION['nom']  ?>
          <br>
        </li>
        <li class="smenu">
          <br>
          <a href="index.php?uc=gererFraisComptable&action=validerFraisComptable" title="Valider fiche de frais ">Valider fiche de frais</a>
        </li>
        <li class="smenu">
          <br>
          <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
        </li>
      </ul>

    </div>