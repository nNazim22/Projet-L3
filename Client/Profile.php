<?php
include("../Client/securite.php");

require_once("../database/Database.php");
require_once("../database/Client.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Check if the form was submitted for updating
  if (isset($_POST['updateProfile'])) {
    // Handle the update logic here
    $updatedNom = $_POST['updatedNom'];
    $updatedPrenom = $_POST['updatedPrenom'];
    $updatedEmail = $_POST['updatedEmail'];
    $updatedNumeroTlphn = $_POST['updatedNumeroTlphn'];
    $updatedGenre = $_POST['updatedGenre'];
    $updatedDateNaissance = $_POST['updatedDateNaissance'];
    $updatedDateNaissance = $_POST['updatedDateNaissance'];
    $updatedPassword = $_POST['updatedPassword'];

    $_SESSION['Nom'] = $updatedNom;
    $_SESSION['Prenom'] = $updatedPrenom;
    $_SESSION['email'] = $updatedEmail;
    $_SESSION['numero_tlphn'] = $updatedNumeroTlphn;
    $_SESSION['genre'] = $updatedGenre;
    $_SESSION['date_de_naissance'] = $updatedDateNaissance;
    $_SESSION['updatedPassword'] = $updatedPassword;


    $data = $updatedPassword;
    $method = "AES-256-CBC";
    $key = "encryptionKey123";
    $options = 0;
    $iv = '1234567891011121';

    $encryptedData = openssl_encrypt($data, $method, $key, $options, $iv);

    try {
      // $updateStatement = $conn->prepare("UPDATE users SET Nom = :updatedNom, Prenom = :updatedPrenom, email = :updatedEmail, numero_tlphn = :updatedNumeroTlphn, genre = :updatedGenre, date_de_naissance = :updatedDateNaissance, Password= :updatedPassword WHERE ClientID = :clientID");
      $updateStatement = $conn->prepare("UPDATE users SET Nom = :updatedNom, Prenom = :updatedPrenom, email = :updatedEmail, numero_tlphn = :updatedNumeroTlphn, genre = :updatedGenre, date_de_naissance = :updatedDateNaissance, Password= :updatedPassword, profile_picture = :profile_picture WHERE ClientID = :clientID");

      $updateStatement->bindParam(':updatedNom', $updatedNom, PDO::PARAM_STR);
      $updateStatement->bindParam(':updatedPrenom', $updatedPrenom, PDO::PARAM_STR);
      $updateStatement->bindParam(':updatedEmail', $updatedEmail, PDO::PARAM_STR);
      $updateStatement->bindParam(':updatedNumeroTlphn', $updatedNumeroTlphn, PDO::PARAM_STR);
      $updateStatement->bindParam(':updatedGenre', $updatedGenre, PDO::PARAM_STR);
      $updateStatement->bindParam(':updatedDateNaissance', $updatedDateNaissance, PDO::PARAM_STR);
      $updateStatement->bindParam(':updatedPassword', $encryptedData, PDO::PARAM_STR);
      $updateStatement->bindParam(':clientID', $_SESSION['ClientID'], PDO::PARAM_INT);
      if (!empty($_FILES['profile_picture']['tmp_name'])) {
        $profilePicture = file_get_contents($_FILES['profile_picture']['tmp_name']);
        $updateStatement->bindParam(':profile_picture', $profilePicture, PDO::PARAM_LOB);
      }
      $updateStatement->execute();

      // Redirect back to the profile page after updating
      header("Location: /ProjetWeb/Client/Profile.php");
      exit;
    } catch (PDOException $e) {
      die("Error updating user in the database: " . $e->getMessage());
    }
  }
}

?>

<body>
  <div class="container">

    <div class="navBar">
      <?php
      include("Nav.php");
      ?>
    </div>

    <?php
    // Fetch the user details based on the session's ClientID
    $currentUser = (new users(null, null, null, null, null, null, null, null, null, null, null, $Db->connexion))->getuser($_SESSION['ClientID']);
    ?>
    <div class="Profile">
      <div class="Profile_container">
        <h1>Profile</h1>
        <div class="information">
          <div class="photo">
            <div class="photoStyle">
              <?php if (!empty($currentUser['profile_picture'])) : ?>
                <img class="img" src="data:image/jpeg;base64,<?= base64_encode($currentUser['profile_picture']) ?>" alt="Profile Picture">
              <?php endif; ?>
            </div>
          </div>
          <div class="info">
            <div class="dakhel">
              <div>
                <p class="InfoP">Nom : </p>
                <p><?php echo $_SESSION['Nom']; ?></p>
              </div>
              <div>
                <p class="InfoP">Prenom :</p>
                <p><?php echo $_SESSION['Prenom']; ?></p>
              </div>
            </div>

            <div class="dakhel">
              <div>
                <p class="InfoP">Email :</p>
                <p><?php echo $_SESSION['email']; ?></p>
              </div>
              <div>
                <p class="InfoP">Numero de télephone :</p>
                <p><?php echo $_SESSION['numero_tlphn']; ?></p>
              </div>
            </div>

            <div class="dakhel">
              <div>
                <p class="InfoP">Genre :</p>
                <p><?php echo $_SESSION['genre']; ?></p>
              </div>

              <div>
                <p class="InfoP">Date de naissance :</p>
                <p><?php echo $_SESSION['date_de_naissance']; ?></p>
              </div>
            </div>
          </div>
        </div>
        <form method="post" id="updateForm" style="display: none;" action="/ProjetWeb/Client/Profile.php" enctype="multipart/form-data">
          <div class="Form">
            <div>
              <label for="updatedNom">Nom :</label>
              <input type="text" name="updatedNom" value="<?php echo $_SESSION['Nom']; ?>" required>
            </div>
            <div>
              <label for="updatedPrenom">Prenom :</label>
              <input type="text" name="updatedPrenom" value="<?php echo $_SESSION['Prenom']; ?>" required>
            </div>
            <div>
              <label for="updatedEmail">Email :</label>
              <input type="email" name="updatedEmail" value="<?php echo $_SESSION['email']; ?>" required>
            </div>
            <div>
              <label for="updatedNumeroTlphn">Numero de téléphone :</label>
              <input type="tel" name="updatedNumeroTlphn" value="<?php echo $_SESSION['numero_tlphn']; ?>" required>
            </div>
            <div>
              <label for="updatedGenre">Genre :</label>
              <select name="updatedGenre" required>
                <option value="Homme" <?php echo ($_SESSION['genre'] === 'Homme') ? 'selected' : ''; ?>>Homme</option>
                <option value="Femme" <?php echo ($_SESSION['genre'] === 'Femme') ? 'selected' : ''; ?>>Femme</option>
              </select>
            </div>
            <div>
              <label for="updatedDateNaissance">Date de naissance :</label>
              <input type="date" name="updatedDateNaissance" value="<?php echo $_SESSION['date_de_naissance']; ?>" required>
            </div>
            <div>
              <label for="updatedPassword">Password :</label>
              <input type="password" name="updatedPassword" placeholder="Enter your password" required>
            </div>

            <div>
              <label for="profile_picture">Profile Picture:</label>
              <input type="file" id="profile_picture" name="profile_picture">
            </div>

          </div>
          <div>
            <button type="submit" name="updateProfile">Confirmer</button>
          </div>
        </form>

        <button id="showUpdateForm">Modifier</button>
      </div>

    </div>
  </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Wait for the DOM to be fully loaded before executing JavaScript

      // Get the elements
      var showUpdateFormButton = document.getElementById('showUpdateForm');
      var updateForm = document.getElementById('updateForm');

      // Set up event listener for the button
      showUpdateFormButton.addEventListener('click', function() {
        // Hide the button and show the form
        showUpdateFormButton.style.display = 'none';
        updateForm.style.display = 'block';
      });
    });
  </script>
</body>