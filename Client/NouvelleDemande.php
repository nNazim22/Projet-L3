<?php
include("../Client/securite.php");
require_once("../database/Abonnment.php");
require_once("../database/GesAbonnement.php");
require_once("../database/report.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./NvStyle.css">
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div class="navBar">
      <?php
      include("Nav.php");
      ?>
    </div>
    <div class="de">
      <?php
      $ClientId = $_SESSION['ClientID'];
      $Abonnements = (new AbonnementActif(null, null, null, null, null, null, null, null, $Db->connexion))->getAbonnementByClientId($ClientId);
      $NvAbonnements = (new GesAbonnement(null, null, null, null, null, null, null, null, $Db->connexion))->getAbonnementByClientId($ClientId);
      $Raport = (new refus(null, null, null, $Db->connexion))->getreport($ClientId);

      if (!empty($Abonnements)) {
      ?>
        <h2 style="margin-top:5rem;">Abonnement Actif</h2>
        <div class="AbonnementInfo">
          <?php foreach ($Abonnements as $Abonnement) { ?>
            <?php $clientInfo = (new AbonnementActif(null, null, null, null, null, null, null, null, $Db->connexion))->getClientInfo($Abonnement['ClientID']); ?>
            <?php if ($clientInfo) : ?>
              <div class="PhotoIdentité">
                <?php if (!empty($clientInfo['profile_picture'])) : ?>
                  <img src="data:image/jpeg;base64,<?= base64_encode($clientInfo['profile_picture']) ?>" alt="Profile Picture">
                <?php endif; ?>
              </div>

              <div class=" InfoPersonnel">

                <h2 style="text-align: center;">Votre Cart</h2>
                <div class="CartNumber">
                  <?php
                  if (empty($Raport)) {
                    echo "Abonnement accordé";
                  }
                  ?>
                  <div>
                    N° Cart :
                  </div>
                  <div>
                    <?= $Abonnement['AbonnementID'] ?>
                  </div>
                </div>
                <ul>
                  <li>
                    <div>
                      Nom:
                    </div>
                    <div>
                      <?= $clientInfo['Nom'] ?>
                    </div>
                  </li>
                  <li>
                    <div>
                      Type :
                    </div>
                    <div>
                      <?= $Abonnement['type'] ?>
                    </div>
                  </li>
                  <li>
                    <div>Prenom :</div>
                    <div>
                      <?= $clientInfo['Prenom'] ?>
                    </div>
                  </li>
                  <li>
                    <div>Genre :</div>
                    <div>
                      <?= $clientInfo['genre'] ?>
                    </div>
                  </li>
                  <li class="DateN">
                    <div>
                      Date de Naissance :
                    </div>
                    <div>
                      <?= $clientInfo['date_de_naissance'] ?>
                    </div>
                  </li>
                  <li class="DateN">
                    <div>
                      Client :
                    </div>
                    <div>
                      <?= $Abonnement['TypeClient'] ?>
                    </div>
                  </li>

                </ul>

              </div>



        </div>
      <?php endif; ?>
    <?php }; ?>
    <div>
    <?php } elseif (empty($Abonnements) && empty($NvAbonnements)) {


        if (!empty($Raport)) {
          echo "<p>Abonnement Refusé</p>";
          echo "<p>Le motif est : " . $Raport['report'] . "</p>";
        }
    ?>

      <div class="demande">

        <form action="NouvelleDemande.php" method="post" class="Addabnc" enctype="multipart/form-data">
          <div>
            <label for="type">Type:</label>
            <select name="type" id="">
              <option value="<?php echo isset($_GET["prixs"]) ? $_GET["prixs"] : 'Semain'; ?>">semain</option>
              <option value="<?php echo isset($_GET["prixm"]) ? $_GET["prixm"] : 'Mois'; ?>">Mois</option>
              <option value="<?php echo isset($_GET["prixa"]) ? $_GET["prixa"] : 'Année'; ?>">Année</option>
            </select>
          </div>
          <div>
            <label for="PDFCart">Cart nationnal(JPG):</label>
            <input class="cartNa" type="file" name="PDFCart" required>
          </div>
          <input type="hidden" name="ClientID">

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

          <input type="submit" value="Submit" class="btn" name="Submit">
        </form>
      <?php }; ?>
      <?php
      if (!empty($NvAbonnements)) {
        echo "<p>Votre demande est en cour de traitement ...</p>";
      } elseif (empty($Abonnements) && !empty($NvAbonnements)) { ?>
        <p>No active subscriptions for the specified client.</p>
      <?php }; ?>

      </div>
    </div>


    </div>
  </div>



  <?php


  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Submit'])) {
    // Fetch form data
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
      $prix = $prix - $prix * 0.20;
    } elseif ($typeclient == "student") {
      $prix = $prix - $prix * 0.30;
    }
    // isset($_GET["prixa"]) ? $_GET["prixa"] : 3000

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

    // Create an abonnement object
    $Abonnement = new GesAbonnement(null, $ClientId, True, $type, $PDF, $PDFStudent, $typeclient, $prix, $Db->connexion);
    // Insert the abonnement into the database
    $Abonnement->create_table();
    $Abonnement->addAbonnement();

    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
  }


  ?>
  </div>

</body>

</html>