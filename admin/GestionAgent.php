<?php
include("../admin/Secu.php");
require_once("../database/Agent.php");

?>

<body>
  <style>
    table {
      margin-top: 5rem;
      /* width: 50%; */
    }
  </style>
  <div class="Presnetation">
    <?php
    echo "Welcome, " . $_SESSION['Nom'] . "!"; // Utilisez le nom d'utilisateur depuis la session

    ?>
  </div>



  <?php



  $c = $Db->connect_to_db();



  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Submit'])) {
    // ajouter dakhel la table (base de donneés)
    $FirstName = $_POST['Nom'];
    $LastName = $_POST['Prenom'];
    $date = $_POST['date'];
    $Email = $_POST['Email'];
    $Genre = $_POST['radio'];
    $Numero = $_POST['Numero'];
    $password = $_POST['password'];
    // ($Agent_ID, $FirstName, $LastName, $Email, $Num, $Genre, $Mdp, $connexion)

    $data = $password;
    $method = "AES-256-CBC";
    $key = "encryptionKey123";
    $options = 0;
    $iv = '1234567891011121';

    $encryptedData = openssl_encrypt($data, $method, $key, $options, $iv);
    $Agent = new Agent(null, $FirstName, $LastName, $date, $Email, $Numero, $Genre, $encryptedData, "Agent", $Db->connexion);
    $Agent->create_table();
    $Agent->add_Agent();
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['UpdateSubmit'])) {
    $updateID = $_POST['updateID'];
    $updatedNom = $_POST['updatedNom'];
    $updatedPrenom = $_POST['updatedPrenom'];
    $updateddate_de_naissance = $_POST['updateddate_de_naissance'];
    $updatedNumeroTlphne = $_POST['updatedNumeroTlphne'];
    $updatedemail = $_POST['updatedemail'];
    $updatedGenre = $_POST['updatedGenre'];

    // Assuming you have a method in your Agent class to handle updates
    $Agent = new Agent($updateID, $updatedNom, $updatedPrenom, $updateddate_de_naissance, $updatedemail, $updatedNumeroTlphne, $updatedGenre, null, "Agent", $Db->connexion);
    $Agent->updateAgent();
  }

  // Check if the Delete button is clicked
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['DeleteSubmit'])) {
    $deleteID = $_POST['deleteID'];

    // Assuming you have a method in your Agent class to handle deletions
    $Agent = new Agent($deleteID, null, null, null, null, null, null, null, null, $Db->connexion);
    $Agent->deleteAgent();
  }

  ?>

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
          <form action="GestionAgent.php" method="post">
            <div>
              <label for="">Nom :</label>
              <input type="text" name="Nom" id="">

              <label for="">Prenom :</label>
              <input type="text" name="Prenom" id="">
            </div>
            <div>
              <label for="">Email :</label>
              <input type="email" name="Email" id="">

              <label for="">Date de naissaince :</label>
              <input type="date" name="date" id="">
            </div>
            <div>
              <label for="">Numero de telephone :</label>
              <input type="number" name="Numero" id="">

              <label for="">Genre :</label>
              <div class="check">
                <label class="container_chek">H
                  <input type="radio" checked="checked" name="radio" value="Homme">
                  <span class="checkmark"></span>
                </label>
                <label class="container_chek">F
                  <input type="radio" name="radio" value="Femme">
                  <span class="checkmark"></span>
                </label>
              </div>
            </div>

            <div>
              <label for="">Password :</label>
              <input type="password" name="password" id="">
              <div>
                <input class="submit" type="submit" name="Submit" value="Ajouter" id="">
              </div>
            </div>



          </form>
        </div>

        <div class="Table">


          <?php
          $Agents = (new Agent(null, null, null, null, null, null, null, null, null, $Db->connexion))->getAgent();

          if (!empty($Agents)) :
          ?>
            <h2>Agent Details</h2>
            <table border='1'>
              <tr>
                <th>AgentID</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Email</th>
                <th>Numero telephone</th>
                <th>date de ....</th>
                <th>Genre</th>
                <th>Actions</th>
              </tr>
              <?php foreach ($Agents as $Agent) : ?>
                <tr>
                  <td><?= $Agent['Agent_ID'] ?></td>
                  <td><?= $Agent['FirstName'] ?></td>
                  <td><?= $Agent['LastName'] ?></td>
                  <td><?= $Agent['Email'] ?></td>
                  <td><?= $Agent['NumeroTlphne'] ?></td>
                  <td><?= $Agent['dateDeNaissance'] ?></td>
                  <td><?= $Agent['Genre'] ?></td>
                  <td>
                    <!-- Display update form when the "Update" button is clicked -->
                    <form style="display:inline;" action="GestionAgent.php" method="post">
                      <input type="hidden" name="updateID" value="<?= $Agent['Agent_ID'] ?>">
                      <button type="submit" name="ShowUpdateForm">Update</button>
                    </form>
                    <!-- Add delete button -->
                    <form style="display:inline;" action="GestionAgent.php" method="post">
                      <input type="hidden" name="deleteID" value="<?= $Agent['Agent_ID'] ?>">
                      <button type="submit" name="DeleteSubmit">Delete</button>
                    </form>
                  </td>
                </tr>
                <?php if (isset($_POST['ShowUpdateForm']) && $_POST['updateID'] == $Agent['Agent_ID']) : ?>
                  <!-- Display update form for the selected agent -->
                  <tr>
                    <form action="GestionAgent.php" method="post">
                      <input type="hidden" name="updateID" value="<?= $Agent['Agent_ID'] ?>">
                      <td></td>
                      <td><input type="text" name="updatedNom" value="<?= $Agent['FirstName'] ?>" required></td>
                      <td><input type="text" name="updatedPrenom" value="<?= $Agent['LastName'] ?>" required></td>
                      <td><input type="date" name="updateddate_de_naissance" value="<?= $Agent['dateDeNaissance'] ?>" required></td>
                      <td><input type="number" name="updatedNumeroTlphne" value="<?= $Agent['NumeroTlphne'] ?>" required></td>
                      <td><input type="email" name="updatedemail" value="<?= $Agent['Email'] ?>" required></td>
                      <!-- Assuming you have a way to get genre IDs dynamically, you can use a select dropdown -->
                      <td>
                        <select name="updatedGenre" required>
                          <option value="<?= $Agent['Genre'] ?>">Homme</option>
                          <option value="<?= $Agent['Genre'] ?>">Femme</option>
                        </select>
                      </td>
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