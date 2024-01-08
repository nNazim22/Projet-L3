<?php
include("../admin/Secu.php");

require_once("../database/Agent.php");
require_once("../database/Abonnment.php");
include_once("../database/Database.php");
include_once("../database/Client.php");
?>


<body>
  <div class="Presnetation">
    <?php
    echo "Welcome, " . $_SESSION['Nom'] . "!"; // Utilisez le nom d'utilisateur depuis la session

    ?>
  </div>
  <div class="container">
    <div class="left">

      <div class="navBar">
        <?php
        include("nav.php");
        ?>
      </div>

    </div>
    <div class="page">
      <div></div>

      <div class="bleT">
        <div class="Table">

          <?php
          $Abonnements = (new AbonnementActif(null, null, null, null, null, null, null, null, $Db->connexion))->getAbonnement();

          if (!empty($Abonnements)) :
          ?>
            <table border='1' class="tt">
              <h2 style="margin-top:5rem;">Abonnement Actif</h2>
              <tr>
                <th>AbonnID</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Email</th>
                <th>Numero telephone</th>
                <th>Date de Naissance</th>
                <th>Genre</th>
                <th>Type Abonnement</th>
                <th>Prix</th>
                <th>PDF</th>
                <th>Photo</th>
                <th>certif</th>
                <th>Type client</th>
                <th>Actions</th>
              </tr>
              <?php foreach ($Abonnements as $Abonnement) : ?>
                <?php $clientInfo = (new AbonnementActif(null, null, null, null, null, null, null, null, $Db->connexion))->getClientInfo($Abonnement['ClientID']); ?>
                <?php if ($clientInfo) : ?>
                  <tr>
                    <td><?= $Abonnement['AbonnementID'] ?></td>
                    <td><?= $clientInfo['Nom'] ?></td>
                    <td><?= $clientInfo['Prenom'] ?></td>
                    <td><?= $clientInfo['email'] ?></td>
                    <td><?= $clientInfo['numero_tlphn'] ?></td>
                    <td><?= $clientInfo['date_de_naissance'] ?></td>
                    <td><?= $clientInfo['genre'] ?></td>
                    <td><?= $Abonnement['type'] ?></td>
                    <td><?= $Abonnement['prix'] ?></td>

                    <td>
                      <?php if (!empty($Abonnement['PDFCart'])) : ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($Abonnement['PDFCart']) ?>" alt="Profile Picture" style="width: 50px; height: 50px;">
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if (!empty($Abonnement['PDFStudent'])) : ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($Abonnement['PDFStudent']) ?>" alt="Profile Picture" style="width: 50px; height: 50px;">
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if (!empty($clientInfo['profile_picture'])) : ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($clientInfo['profile_picture']) ?>" alt="Profile Picture" style="width: 50px; height: 50px;">
                      <?php endif; ?>
                    </td>
                    <td>
                      <?= $Abonnement['TypeClient'] ?>
                    </td>
                    <td>
                      <!-- Add delete button -->
                      <form style="display:inline;" action="Abonnment.php" method="post">
                        <input type="hidden" name="deleteID" value="<?= $Abonnement['AbonnementID'] ?>">
                        <button type="submit" name="DeleteSubmit">Delete</button>
                      </form>
                    </td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </table>
          <?php endif; ?>

          <?php
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['DeleteSubmit'])) {

              $deleteClientID = $_POST['deleteID'];

              // Assuming you have a method in your Agent class to handle deletions
              $UserHandler = new AbonnementActif($deleteClientID, null, null, null, null, null, null, null, $Db->connexion);
              $UserHandler->deleteAboonement();
            }
          }
          ?>


        </div>
      </div>
    </div>
  </div>


</body>

</html>