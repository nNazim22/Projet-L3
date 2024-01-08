<?php
include("../agent/Secu.php");
?>

<body>

  <div class="Presnetation">

    <?php
    echo "Welcome, " . $_SESSION['FirstName'] . "!"; // Utilisez le nom d'utilisateur depuis la session

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
      <div>

        <div class="LoginFromDiv">
          <form action="GestionClient.php" method="post" enctype="multipart/form-data">
            <!-- Remove the ID field -->
            <div>
              <label for="Nom">Nom:</label>
              <input type="text" id="Nom" name="Nom" required>
            </div>

            <div>
              <label for="Prenom">Prenom:</label>
              <input type="text" id="Prenom" name="Prenom" required>
            </div>

            <div>
              <label for="date_de_naissance">Date de naissance:</label>
              <input type="date" id="date_de_naissance" name="date_de_naissance" required>
            </div>

            <div>
              <label for="numero_tlphn">Numero telephone :</label>
              <input type="number" id="numero_tlphn" name="numero_tlphn" required>
            </div>

            <div>
              <label for="email">email:</label>
              <input type="email" id="email" name="email" required>
            </div>
            <!-- Assuming you have a way to get department IDs dynamically, you can use a select dropdown -->
            <div>
              <label for="genre"> genre :</label>
              <select name="genre">
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
              </select>
            </div>
            <div>
              <label for="profile_picture">Profile Picture:</label>
              <input type="file" id="profile_picture" name="profile_picture">
            </div>

            <div>
              <label for="email">password</label>
              <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="Submit">Submit</button>
          </form>
        </div>


        <div class="Table">
          <?php
          include_once("../database/Client.php");
          $c = $Db->connect_to_db();

          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['Submit'])) {
              // Add User
              $Nom = $_POST['Nom'];
              $Prenom = $_POST['Prenom'];
              $date_de_naissance = $_POST['date_de_naissance'];
              $numero_tlphn = $_POST['numero_tlphn'];
              $email = $_POST['email'];
              $genre = $_POST['genre'];
              $password = $_POST['password'];

              // Handle profile picture upload
              if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['profile_picture']['tmp_name'];
                $profile_picture = file_get_contents($tmp_name);
              } else {
                $profile_picture = null;
              }

              // Create user instance and add to the database
              $user = new users(null, $Nom, $Prenom, $genre, $date_de_naissance, $numero_tlphn, $email, $password, "Client", false, null, $Db->connexion);
              $user->create_table();
              $user->profile_picture = $profile_picture; // Set the profile picture property
              $user->adduser();
            }
          }


          if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['UpdateSubmit'])) {
              $updateClientID = $_POST['updateClientID'];
              $updatedNom = $_POST['updatedNom'];
              $updatedPrenom = $_POST['updatedPrenom'];
              $updatednumero_tlphn = $_POST['updatednmrtlfn'];
              $updateddate_de_naissance = $_POST['updateddate_de_naissance'];
              $updatedemail = $_POST['updatedemail'];
              $updatedgenre = $_POST['updatedgenre'];

              $Agent = new users($updateClientID, $updatedNom, $updatedPrenom, $updatedgenre, $updateddate_de_naissance, $updatednumero_tlphn, $updatedemail, null, "Client", false, null, $Db->connexion);
              $Agent->updateUser();
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['DeleteSubmit'])) {

              $deleteClientID = $_POST['deleteClientID'];

              // Assuming you have a method in your Agent class to handle deletions
              $UserHandler = new users($deleteClientID, null, null, null, null, null, null, null, null, null, null, $Db->connexion);
              $success = $UserHandler->deleteUser();
            }
          }

          ?>
          <?php
          $users = (new users(null, null, null, null, null, null, null, null, null, null, null, $Db->connexion))->getusers();

          if (!empty($users)) : ?>
            <h2>user Details</h2>
            <table>
              <tr>
                <th>ClientID</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Date de naissance</th>
                <th>email</th>
                <th>numero telephone</th>
                <th>genre</th>
                <th>Photo</th>
              </tr>


              <?php foreach ($users as $user) : ?>
                <tr>
                  <td><?= $user['ClientID'] ?></td>
                  <td><?= $user['Nom'] ?></td>
                  <td><?= $user['Prenom'] ?></td>
                  <td><?= $user['date_de_naissance'] ?></td>
                  <td><?= $user['email'] ?></td>
                  <td><?= $user['numero_tlphn'] ?></td>
                  <td><?= $user['genre'] ?></td>
                  <td>
                    <?php if (!empty($user['profile_picture'])) : ?>
                      <img src="data:image/jpeg;base64,<?= base64_encode($user['profile_picture']) ?>" alt="Profile Picture" style="width: 50px; height: 50px;">
                    <?php endif; ?>
                  </td>
                  <td>
                    <!-- Display update form when the "Update" button is clicked -->
                    <form style="display:inline;" action="GestionClient.php" method="post">
                      <input type="hidden" name="updateClientID" value="<?= $user['ClientID'] ?>">
                      <button type="submit" name="ShowUpdateForm">Update</button>
                    </form>
                    <!-- Add delete button -->
                    <form style="display:inline;" action="GestionClient.php" method="post">
                      <input type="hidden" name="deleteClientID" value="<?= $user['ClientID'] ?>">
                      <button type="submit" name="DeleteSubmit">Delete</button>
                    </form>
                  </td>
                </tr>

                <?php if (isset($_POST['ShowUpdateForm']) && $_POST['updateClientID'] == $user['ClientID']) : ?>

                  <!-- Display update form for the selected user -->
                  <tr>
                    <form action="GestionClient.php" method="post">
                      <td><input type="hidden" name="updateClientID" value="<?= $user['ClientID'] ?>"></td>
                      <td><input type="text" name="updatedNom" value="<?= $user['Nom'] ?>" required></td>
                      <td><input type="text" name="updatedPrenom" value="<?= $user['Prenom'] ?>" required></td>
                      <td><input type="date" name="updateddate_de_naissance" value="<?= $user['date_de_naissance'] ?>" required></td>
                      <td><input type="email" name="updatedemail" value="<?= $user['email'] ?>" required></td>
                      <td><input type="number" name="updatednmrtlfn" value="<?= $user['numero_tlphn'] ?>" required></td>
                      <td>
                        <select name="updatedgenre" required>
                          <option value="Homme" <?= ($user['genre'] === 'Homme') ? 'selected' : '' ?>>Homme</option>
                          <option value="Femme" <?= ($user['genre'] === 'Femme') ? 'selected' : '' ?>>Femme</option>
                        </select>

                      </td>
                      <!-- Assuming you have a way to get department IDs dynamically, you can use a select dropdown -->
                      <td colspan="2"><button type="submit" name="UpdateSubmit">Confirm Update</button></td>
                    </form>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>

</body>

</html>