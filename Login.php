<?php
session_start();

// Check if user is not logged in or not a regular user
if (isset($_SESSION['ClientID'])) {
  header("Location: /ProjetWeb/Client/index.php");
  exit;
} elseif (isset($_SESSION["user_id"])) {
  header("Location: /ProjetWeb/agent/index.php");
} elseif (isset($_SESSION['admin_id'])) {
  header("Location: /ProjetWeb/admin/index.php");
  exit;
}

require_once("../ProjetWeb/database/Database.php");
require_once("../ProjetWeb/database/admin.php");
require_once("../ProjetWeb/database/Agent.php");
require_once("../ProjetWeb/database/Client.php");


$data = "nazim123";
$method = "AES-256-CBC";
$key = "encryptionKey123";
$options = 0;
$iv = '1234567891011121';

$encryptedData = openssl_encrypt($data, $method, $key, $options, $iv);


$Admin = new admin(null, "nazim", "nazim@gmail.com", $encryptedData, "Admin", $Db->connexion);
$Admin->create_table();
$Agent = new Agent(null, null, null, null, null, null, null, null, null, $Db->connexion);

$Agent->create_table();
$Client = new users(null, null, null, null, null, null, null, null, null, null, null, $Db->connexion);

$Client->create_table();

// Vérifiez si la table est vide
$result = $Db->connexion->query("SELECT COUNT(*) FROM administrareurs");
$numRows = $result->fetchColumn();

// Exécutez le code seulement si la table est vide
if ($numRows == 0) {
  // Créez la table
  $Admin->create_table();

  // Ajoutez l'agent
  $Admin->add_Admin();
}

?>


<?php
include("./ui/Head.php");
?>
<div class="container">

  <div>
    <div class="navBar">
      <nav class="navBarLog">
        <a href="/ProjetWeb/index.php">
          <img class="LogoImgLog" src="./ui/Logo.png">

        </a>
        <h4>
          Connecter vous pour profiter de nos Offres
        </h4>
      </nav>
    </div>
  </div>

  <div class="Login_connect">
    <div class="imageSide">
      <img class="img_log" src="./img/img1.jpg" alt="">
    </div>
    <div class="Log_style">
      <div class="LoginFromDiv_v2">
        <form method="post" action="Login.php">
          <div>
            <label for="">Email</label>
            <input type="email" name="email" id="">
          </div>

          <div>
            <label for="">Password</label>
            <input type="password" name="password" id="">
          </div>

          <div>
            <input class="submit" type="submit" name="submit" value="Connect" id="">
          </div>
          <div>
            <a class="Oublier" href="/ProjetWeb/forgot_password.php">Mot de pass Oublier</a>
          </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $Email = $_POST['email'];
          $password = $_POST['password'];

          $stmt = $conn->prepare("SELECT Agent_ID, FirstName, LastName, Email, type, Password FROM Agents WHERE Email = :email");
          $stmt->execute(['email' => $Email]);
          $Agent = $stmt->fetch(PDO::FETCH_ASSOC);

          $stmt2 = $conn->prepare("SELECT id, Nom, Email, Type, Password FROM administrareurs WHERE Email = :email");
          $stmt2->execute(['email' => $Email]);
          $Admin = $stmt2->fetch(PDO::FETCH_ASSOC);

          $stmt3 = $conn->prepare("SELECT ClientID, Nom, Prenom, email, genre, date_de_naissance, type, abonnementActif, numero_tlphn, Password FROM users WHERE email = :email");
          $stmt3->execute(['email' => $Email]);
          $User = $stmt3->fetch(PDO::FETCH_ASSOC);

          if ($Agent) {

            $encryptedData = $Agent['Password'];
            $method = "AES-256-CBC";
            $key = "encryptionKey123";
            $options = 0;
            $iv = '1234567891011121';

            $decryptedData = openssl_decrypt($encryptedData, $method, $key, $options, $iv);

            if ($decryptedData == $password) {
              $_SESSION['user_id'] = $Agent['Agent_ID'];
              $_SESSION['Type'] = $Agent['type'];
              $_SESSION['FirstName'] = $Agent['FirstName'];
              $_SESSION['LastName'] = $Agent['LastName'];
              if ($Agent['Email'] == $Email && $_SESSION['Type'] == "Agent") {
                header("Location: /ProjetWeb/agent/index.php");
              }
              exit;
            } else {
              echo "<p style='color: #c92a2a; text-align: center;'>Invalid username or password</p>";
            }
          } elseif ($Admin) {
            $encryptedData = $Admin['Password'];
            $method = "AES-256-CBC";
            $key = "encryptionKey123";
            $options = 0;
            $iv = '1234567891011121';

            $decryptedData = openssl_decrypt($encryptedData, $method, $key, $options, $iv);

            if ($decryptedData == $password) {
              $_SESSION['admin_id'] = $Admin['id'];
              $_SESSION['Type'] = $Admin['Type'];
              $_SESSION['Nom'] = $Admin['Nom'];
              $_SESSION['Email'] = $Admin['Email'];
              if ($Admin['Email'] == $Email && $_SESSION['Type'] == "Admin") {
                header("Location: /ProjetWeb/admin/index.php");
              }

              exit;
            } else {
              echo "<p style='color: #c92a2a; text-align: center;'>Invalid username or password</p>";
            }
          } elseif ($User) {
            $encryptedData = $User['Password'];
            $method = "AES-256-CBC";
            $key = "encryptionKey123";
            $options = 0;
            $iv = '1234567891011121';

            $decryptedData = openssl_decrypt($encryptedData, $method, $key, $options, $iv);

            if ($decryptedData == $password) {
              $_SESSION['ClientID'] = $User['ClientID'];
              $_SESSION['type'] = $User['type'];
              $_SESSION['Nom'] = $User['Nom'];
              $_SESSION['Prenom'] = $User['Prenom'];
              $_SESSION['email'] = $User['email'];
              $_SESSION['abonnementActif'] = $User['abonnementActif'];
              $_SESSION['date_de_naissance'] = $User['date_de_naissance'];
              $_SESSION['genre'] = $User['genre'];
              $_SESSION['numero_tlphn'] = $User['numero_tlphn'];
              if ($User['email'] == $Email && $_SESSION['type'] == "Client") {
                header("Location: /ProjetWeb/Client/index.php");
              }

              exit;
            } else {
              echo "<p style='color: #c92a2a; text-align: center;'>Invalid username or password</p>";
            }
          }
        }
        ?>

        <div class="inc">
          <a class="LinkInscripton" href="/ProjetWeb/inscription.php">Inscripton</a>
        </div>
      </div>
    </div>
  </div>


</div>