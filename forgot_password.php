<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./mail/src/Exception.php";
require "./mail/src/PHPMailer.php";
require "./mail/src/SMTP.php";

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

            <form method="post" action="forgot_password.php">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>
                </div>

                <div>
                    <input type="submit" name="submit" class="btn" value="Envoyer">
                </div>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $email = $_POST['email'];

                $user = new users(null, null, null, null, null, null, null, null, null, null, null, $conn);
                // Check if the user with the provided email and username exists
                $userData = $user->searchUserByEmailAndUsername($email);
                $message = "
                <html>
                <head>
                <title>HTML email</title>
                </head>
            
                <style>
                .LoginFromDiv_v2 form input {
                    margin-top: 8px;
                    /* border: 1px solid black; */
                    /* width: 15rem; */
                    padding: 0.7rem;
                  
                    outline: none;
                  }
                  .LoginFromDiv_v2 form {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    gap: 1rem;
                    padding-bottom: 1rem;
                    border-bottom: 1px solid #07464f;
                  }
                  button {
                    background-color: #0b7285;
                    border: none;
                    font-size: 1rem;
                    color: white;
                  }
                </style>
                <body>
                <form style='display:inline;' action='http://localhost/ProjetWeb/rest.php' method='post'>
                <input type='hidden' name='ClientID' value='" . $userData['ClientID'] . "'>
                <input type='hidden' name='EmailSend' value='" . $email . "'>
                <button type='submit' name='ShowUpdateForm'>Update</button>
                </form>
                </body>
                </html>
                ";

                if ($userData) {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = "houmelnazim2002@gmail.com";
                    $mail->Password = "pmpi ibdm midk hifq";
                    $mail->SMTPSecure = "ssl";
                    $mail->Port = 465;

                    $mail->setFrom("houmelnazim2002@gmail.com");

                    $mail->addAddress($email);

                    $mail->isHTML(True);

                    $mail->Subject = "test";
                    $mail->Body = $message;
                    $mail->send();

                    echo "<p>Un mail a été envoyer dans votre boîte pour Réinitialiser votre mot de passe</p>";
                }
            }
            ?>

        </div>
    </div>


</div>