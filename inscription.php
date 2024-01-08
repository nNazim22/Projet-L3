<?php
include("./ui/Head.php");
?>

<style>
  .LoginFromDiv {
    width: 50%;
  }

  .LoginFromDiv form {

    display: grid;
    grid-template-columns: repeat(2, 1fr);
  }
</style>
<div class="container">

  <div>

    <div class="navBar">
      <nav class="navBarLog">
        <a href="/ProjetWeb/index.php">
          <img class="LogoImgLog" src="./ui/Logo.png">

        </a>
        <h4>
          Inscrivez vous pour profiter de nos Offres
        </h4>
      </nav>
    </div>
  </div>

  <div class="Login">
    <div class="LoginFromDiv">
      <form action="inscription.php" method="post">
        <div>
          <label for="">Nom :</label>
          <input type="text" name="Nom" id="">

          <label for="">Prenom :</label>
          <input type="text" name="Prenom" id="">

          <label for="date_de_naissance">Date de naissance:</label>
          <input type="date" id="date_de_naissance" name="date_de_naissance" required>
        </div>
        <div>
          <label for="">Email :</label>
          <input type="email" name="email" id="">

          <label for="">numero de telephone :</label>
          <input type="number" name="numero_tlphn" id="">

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
        </div>

        <div>
          <input class="submit" type="submit" name="Submit" value="Inscripton" id="">
        </div>
        <div>
          <a class="Oublier" href="#">Mot de pass Oublier</a>
        </div>
      </form>

      <div class="inc">
        <a class="LinkInscripton" href="/ProjetWeb/Login.php">Se connecter</a>
      </div>
    </div>
  </div>


</div>

<?php

include("./database/Client.php");

$c = $Db->connect_to_db();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['Submit'])) {
    // Add User
    $Nom = $_POST['Nom'];
    $Prenom = $_POST['Prenom'];
    $date_de_naissance = $_POST['date_de_naissance'];
    $numero_tlphn = $_POST['numero_tlphn'];
    $email = $_POST['email'];
    $Genre = $_POST['radio'];
    $password = $_POST['password'];


    $data = $password;
    $method = "AES-256-CBC";
    $key = "encryptionKey123";
    $options = 0;
    $iv = '1234567891011121';

    $encryptedData = openssl_encrypt($data, $method, $key, $options, $iv);

    echo "Data: " . $data . "\n";
    echo "Encrypted Data: " . $encryptedData;
    //               ($ClientID, $Nom, $Prenom, $genre, $date_de_naissance, $Numero_Tlphn, $email, $Mdp, $type, $connexion)
    $user = new users(null, $Nom, $Prenom, $Genre, $date_de_naissance, $numero_tlphn, $email, $encryptedData, "Client", false, null, $Db->connexion);
    $user->create_table();
    $user->adduser();

    $stmt3 = $c->prepare("SELECT ClientID, Nom,Prenom,  email, genre,date_de_naissance ,type,abonnementActif,numero_tlphn,Password FROM users WHERE email = :email ");
    $stmt3->execute(['email' => $email]);

    $User = $stmt3->fetch(PDO::FETCH_ASSOC);
    session_start();
    if ($User) {
      $encryptedData = $User['Password'];
      $method = "AES-256-CBC";
      $key = "encryptionKey123";
      $options = 0;
      $iv = '1234567891011121';

      $decryptedData = openssl_decrypt($encryptedData, $method, $key, $options, $iv);


      echo "Encrypted Data: " . $encryptedData . "\n";
      echo "Decrypted Data: " . $decryptedData;

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
        if ($User['email'] == $email && $_SESSION['type'] == "Client") {
          header("Location: /ProjetWeb/Client/index.php");
        }

        exit;
      }
    }
  }
}

?>