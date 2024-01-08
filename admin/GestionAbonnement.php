<?php
include("../admin/Secu.php");

require_once("../database/Agent.php");
require_once("../database/report.php");
require_once("../database/GesAbonnement.php");
require_once("../database/Abonnment.php");
include_once("../database/Database.php");
include_once("../database/Client.php");



?>
<style>
  .pages {
    display: grid;
    grid-template-columns: 25% 1fr;
    background-color: #e9ecef;
  }
</style>

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
    <div class=" zzz">



      <div class="LoginFromDiv">
        <?php
        if (isset($_POST['search'])) {
          // User submitted the search form
          $nom = $_POST['nom'];
          $prenom = $_POST['prenom'];

          // Search for the client based on nom and prenom
          $client = new users(null, null, null, null, null, null, null, null, null, null, null, $Db->connexion);
          $clientData = $client->searchUserByNomPrenom($nom, $prenom);

          if ($clientData) {
            // Display the form with retrieved data
        ?>
            <form action="GestionAbonnement.php" method="post" enctype="multipart/form-data">
              <label for="nom">Nom:</label>
              <input type="text" name="nom" value="<?php echo $clientData['Nom']; ?>" required>

              <label for="prenom">Prenom:</label>
              <input type="text" name="prenom" value="<?php echo $clientData['Prenom']; ?>" required>

              <input type="hidden" name="ClientID" value="<?php echo $clientData['ClientID']; ?>">

              <div>
                <label for="type">Type:</label>
                <select name="type" id="">
                  <option value="Semain">semain</option>
                  <option value="Mois">Mois</option>
                  <option value="Année">Année</option>
                </select>
              </div>
              <div>
                <label for="PDFCart">Cart nationnal:</label>
                <input type="file" name="PDFCart" required>
              </div>

              <label for="user_type">Select user type:</label>
              <select id="user_type" name="user_type" onchange="showUploadInput()">
                <option value="major">Major</option>
                <option value="senior">Senior</option>
                <option value="student">Student</option>

              </select>

              <div id="pdf_upload" style="display:none;">
                <label for="pdf_file">Upload JPG:</label>
                <input type="file" id="pdf_file" name="pdf_file">
              </div>


              <script>
                function showUploadInput() {
                  var userType = document.getElementById("user_type").value;
                  var pdfUploadDiv = document.getElementById("pdf_upload");

                  if (userType === "student") {
                    pdfUploadDiv.style.display = "block";
                  } else {
                    pdfUploadDiv.style.display = "none";
                  }
                }
              </script>

              <input type="submit" value="Submit" name="Submit">
            </form>
          <?php
          } else {
            echo "Client not found.";
          }
        } else {
          // Display the search form
          ?>
          <form action="" method="post">
            <label for="nom">Nom:</label>
            <input type="text" name="nom" required>

            <label for="prenom">Prenom:</label>
            <input type="text" name="prenom" required>

            <input type="submit" name="search" value="Search">
          </form>
        <?php
        }

        ?>

      </div>

      <div class="Table">


        <?php


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Submit'])) {
          $ClientID = $_POST['ClientID'];

          $Abonnement = (new GesAbonnement(null, null, null, null, null, null, null, null, $Db->connexion))->getAbonnementByClientId($ClientID);

          $type = $_POST['type'];
          $typeclient = $_POST['user_type'];


          if ($type == "Semain") {
            $prix = 1000;
          } elseif ($type == "Mois") {
            $prix = 2000;
          } elseif ($type == "Année") {
            $prix = 3000;
          }
          if ($typeclient == "senior") {
            $prix = $prix * 0.20;
          } elseif ($typeclient == "student") {
            $prix = $prix * 0.30;
          }
          // File upload handling
          if (isset($_FILES['PDFCart']) && $_FILES['PDFCart']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['PDFCart']['tmp_name'];
            $PDF = file_get_contents($tmp_name);
          } else {
            $PDF = null;
          }
          if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['pdf_file']['tmp_name'];
            $PDFStudent = file_get_contents($tmp_name);
          } else {
            $PDFStudent = null;
          }

          $Abonnement = new AbonnementActif(null, $ClientID, True, $type, $PDF, $PDFStudent, $typeclient, $prix, $Db->connexion);

          // Insert the abonnement into the database
          $Abonnement->create_table();
          $Abonnement->addAbonnement();
          header("Location: {$_SERVER['PHP_SELF']}");
          exit();
        }

        ?>
        <?php
        $Abonnements = (new GesAbonnement(null, null, null, null, null, null, null, null, $Db->connexion))->getAbonnement();

        if (!empty($Abonnements)) :
        ?>
          <h2>Abonnement Details</h2>
          <table border='1'>
            <tr>
              <th>AbonnID</th>
              <th>Nom</th>
              <th>Prenom</th>
              <th>Email</th>
              <th>Numero telephone</th>
              <th>Date de Naissance</th>
              <th>Genre</th>
              <th>PDF</th>
              <th>certif</th>
              <th>Photo</th>
              <th>Type Client</th>
              <th>Actions</th>
            </tr>
            <?php foreach ($Abonnements as $Abonnement) : ?>
              <?php $clientInfo = (new GesAbonnement(null, null, null, null, null, null, null, null, $Db->connexion))->getClientInfo($Abonnement['ClientID']); ?>
              <?php if ($clientInfo) : ?>
                <tr>
                  <td><?= $Abonnement['NAbonnID'] ?></td>
                  <td><?= $clientInfo['Nom'] ?></td>
                  <td><?= $clientInfo['Prenom'] ?></td>
                  <td><?= $clientInfo['email'] ?></td>
                  <td><?= $clientInfo['numero_tlphn'] ?></td>
                  <td><?= $clientInfo['date_de_naissance'] ?></td>
                  <td><?= $clientInfo['genre'] ?></td>

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
                    <?= $Abonnement['prix'] ?>

                  </td>
                  <td>
                    <!-- Display update form when the "Update" button is clicked -->
                    <form style="display:inline;" action="GestionAbonnement.php" method="post">
                      <input type="hidden" name="updateID" value="<?= $Abonnement['NAbonnID'] ?>">
                      <input type="hidden" name="type" value="<?= $Abonnement['type'] ?>">
                      <input type="hidden" name="ClientID" value="<?= $Abonnement['ClientID'] ?>">
                      <input type="hidden" name="deleteID" value="<?= $Abonnement['NAbonnID'] ?>">

                      <button type="submit" name="ShowUpdateForm">confirme</button>
                    </form>

                    <!-- Add delete button -->
                    <form style="display:inline;" action="GestionAbonnement.php" method="post">
                      <input type="hidden" name="deleteID" value="<?= $Abonnement['NAbonnID'] ?>">
                      <button type="submit" name="DeleteSubmit">Delete</button>
                    </form>

                    <?php
                    if (isset($_POST['DeleteSubmit']) && $_POST['deleteID'] == $Abonnement['NAbonnID']) {
                    ?>

                      <form style="display:inline;" action="GestionAbonnement.php" method="post">
                        <textarea name="rapport" id="" cols="5" rows="5 "></textarea>
                        <input type="hidden" name="deleteID" value="<?= $Abonnement['NAbonnID'] ?>">
                        <button type="submit" name="ConfirmDeleteSubmit">Confirm Delete</button>
                      </form>

                    <?php
                    }
                    if (isset($_POST['ConfirmDeleteSubmit']) && $_POST['deleteID'] == $Abonnement['NAbonnID']) {
                      $rapport = $_POST['rapport'];
                      $reportdel = new refus(null, $rapport, $Abonnement['ClientID'], $Db->connexion);
                      $reportdel->report_table();
                      $reportdel->addreport();
                      $deleteAbonnement = $_POST['deleteID'];

                      $Abonnement = new GesAbonnement($deleteAbonnement, null, null, null, null, null, null, null, $Db->connexion);
                      $Abonnement->deleteAbonnement();
                    }

                    ?>
                  </td>
                </tr>

                <?php if (isset($_POST['ShowUpdateForm']) && $_POST['updateID'] == $Abonnement['NAbonnID']) {
                  $ClientID = $_POST['ClientID'];
                  $type = $_POST['type'];
                  $PDF = $Abonnement['PDFCart'];
                  $deleteAbonnement = $_POST['deleteID'];

                  $typeclient = $Abonnement['TypeClient'];
                  $PDFStudent = $Abonnement['PDFStudent'];
                  $prix = $Abonnement['prix'];

                  $Abonnement = new AbonnementActif(null, $ClientID, True, $type, $PDF, $PDFStudent, $typeclient, $prix, $Db->connexion);

                  // Insert the abonnement into the database
                  $Abonnement->create_table();
                  $Abonnement->addAbonnement();
                  $Abonnement2 = new GesAbonnement($deleteAbonnement, null, null, null, null, null, null, null, $Db->connexion);
                  $Abonnement2->deleteAbonnement();
                };
                ?>
              <?php endif; ?>
            <?php endforeach; ?>
          </table>
        <?php endif; ?>


      </div>

    </div>


</body>

</html>