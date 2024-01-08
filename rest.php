<?php
session_start();

require_once("../ProjetWeb/database/Database.php");
require_once("../ProjetWeb/database/Client.php");

?>

<?php
include("./ui/Head.php");
?>

<style>
  h2 {
    text-align: center;
  }

  .btn {
    border: none;
    cursor: pointer;
    background-color: #0b7285;
    color: #fff;
  }

  p {
    color: #5c940d;
    text-align: center;
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
          Réinitialiser le mot de passe
        </h4>
      </nav>
    </div>
  </div>

  <div class="Login">
    <div class="LoginFromDiv">
      <h2>Password Reset</h2>

      <form method="post" action="rest.php">
        <div>
          <label for="new_password">New Password:</label>
          <input type="password" name="new_password" required>
        </div>

        <div>
          <label for="confirm_password">Confirm Password:</label>
          <input type="password" name="confirm_password" required>
        </div>

        <div>
          <input type="submit" name="submit" class="btn" value="Reset Password">
        </div>
      </form>
      <?php

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['ShowUpdateForm'])) {

          $id = $_POST['ClientID'];
          $email = $_POST['EmailSend'];
          $_SESSION['ClientID'] = $id;
          $_SESSION['email'] = $email;
        }
        if (isset($_POST['submit'])) {
          $newPassword = $_POST['new_password'];
          $confirmPassword = $_POST['confirm_password'];

          if ($newPassword !== $confirmPassword) {
            // Display an error message if passwords do not match
            echo "New password and confirm password do not match. Please try again.";
            exit;
          }
          $user = new users(null, null, null, null, null, null, null, null, null, null, null, $conn);

          // Check if the user with the provided email and username exists
          $userData = $user->searchUserByEmailAndUsername($_SESSION['email']);

          if ($userData) {
            $Client = new users(null, null, null, null, null, null, null, null, null, null, null, $conn);

            $data = $confirmPassword;
            $method = "AES-256-CBC";
            $key = "encryptionKey123";
            $options = 0;
            $iv = '1234567891011121';

            $encryptedData = openssl_encrypt($data, $method, $key, $options, $iv);
            $Client->updatepassword($encryptedData, $_SESSION['email']);
            // Display a success message
            echo " <p>Password reset successful</p>";
            session_destroy();
            exit;
          }
        }
      }
      ?>

    </div>
  </div>


</div>