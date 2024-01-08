<?php
include_once("Database.php");
class users extends Database
{
  public $ClientID, $Nom, $Prenom, $genre, $date_de_naissance, $Numero_Tlphn, $email, $Mdp, $type, $abonnementActif;
  public $connexion;
  public $profile_picture;

  public function __construct($ClientID, $Nom, $Prenom, $genre, $date_de_naissance, $Numero_Tlphn, $email, $Mdp, $type, $abonnementActif, $profile_picture, $connexion)
  {
    $this->ClientID = $ClientID;
    $this->Nom = $Nom;
    $this->Prenom = $Prenom;
    $this->genre = $genre;
    $this->date_de_naissance = $date_de_naissance;
    $this->Numero_Tlphn = $Numero_Tlphn;
    $this->email = $email;
    $this->Mdp = $Mdp;
    $this->type = $type;
    $this->abonnementActif = $abonnementActif;
    $this->profile_picture = $profile_picture;
    $this->connexion = $connexion;
  }

  public function create_table()
  {
    $Request = "CREATE TABLE IF NOT EXISTS users ( 
        ClientID int AUTO_INCREMENT PRIMARY KEY,
        Nom varchar(50),
        Prenom varchar(50),
        genre varchar(50),
        date_de_naissance date,
        numero_tlphn int,
        email varchar(50),
        `Password` varchar(50),
        `type` varchar(50),
        `abonnementActif` BOOLEAN,
        profile_picture BLOB
    )";
    $x = $this->connexion->prepare($Request);
    $e = $x->execute();
  }

  public function adduser()
  {
    $request = "INSERT INTO `users` (`Nom`, `Prenom`, `genre`, `date_de_naissance`, `numero_tlphn`, `email`, `Password`, `type`, `abonnementActif`, `profile_picture`) VALUES (" .
      "'" . $this->Nom . "'," .
      "'" . $this->Prenom . "'," .
      "'" . $this->genre . "'," .
      "'" . $this->date_de_naissance . "'," .
      "'" . $this->Numero_Tlphn . "'," .
      "'" . $this->email . "'," .
      "'" . $this->Mdp . "'," .
      "'" . $this->type . "'," .
      "'" . $this->abonnementActif . "'," .
      ":profile_picture" . // Placeholder for the picture data
      ")";

    $stmt = $this->connexion->prepare($request);
    $stmt->bindParam(':profile_picture', $this->profile_picture, PDO::PARAM_LOB); // Use PDO::PARAM_LOB for BLOB data
    $stmt->execute();
  }
  public function updateUser()
  {
    try {
      // Check if the profile picture is set
      $profilePictureUpdate = '';
      if (!empty($this->profile_picture)) {
        $profilePictureUpdate = ', `profile_picture` = :updatedProfilePicture';
      }

      $request = "UPDATE `users` SET
            `Nom` = :updatedNom,
            `Prenom` = :updatedPrenom,
            `date_de_naissance` = :updateddate_de_naissance,
            `numero_tlphn` = :updatednumero_tlphn,
            `email` = :updatedemail,
            `genre` = :updatedgenre
            $profilePictureUpdate
            WHERE `ClientID` = :updateClientID";

      $stmt = $this->connexion->prepare($request);
      $stmt->bindParam(':updateClientID', $this->ClientID, PDO::PARAM_INT);
      $stmt->bindParam(':updatedNom', $this->Nom, PDO::PARAM_STR);
      $stmt->bindParam(':updatedPrenom', $this->Prenom, PDO::PARAM_STR);
      $stmt->bindParam(':updateddate_de_naissance', $this->date_de_naissance, PDO::PARAM_STR);
      $stmt->bindParam(':updatednumero_tlphn', $this->Numero_Tlphn, PDO::PARAM_INT);
      $stmt->bindParam(':updatedemail', $this->email, PDO::PARAM_STR);
      $stmt->bindParam(':updatedgenre', $this->genre, PDO::PARAM_STR);

      // Bind profile_picture parameter if set
      if (!empty($this->profile_picture)) {
        $stmt->bindParam(':updatedProfilePicture', $this->profile_picture, PDO::PARAM_LOB);
      }

      return $stmt->execute();
    } catch (PDOException $e) {
      die("Error updating user: " . $e->getMessage());
    }
  }



  public function getusers()
  {
    try {
      $request = "SELECT * FROM `users`";
      $stmt = $this->connexion->prepare($request);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // Handle the error as needed
      return false;
    }
  }

  public function getuser($ClientID)
  {
    try {
      $request = "SELECT * FROM `users` WHERE `ClientID` = :clientID";
      $stmt = $this->connexion->prepare($request);
      $stmt->bindParam(':clientID', $ClientID, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // Handle the error as needed
      return false;
    }
  }

  public function deleteUser()
  {
    $request = "DELETE FROM `users` WHERE `ClientID` = :deleteClientID";
    $stmt = $this->connexion->prepare($request);
    $stmt->bindParam(':deleteClientID', $this->ClientID, PDO::PARAM_INT);
    return $stmt->execute();
  }

  public function searchUserByNomPrenom($nom, $prenom)
  {
    try {
      $request = "SELECT * FROM `users` WHERE `Nom` = :nom AND `Prenom` = :prenom";
      $stmt = $this->connexion->prepare($request);
      $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
      $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
      $stmt->execute();

      // Fetch the first matching result
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // Handle the error as needed
      die("Error searching user: " . $e->getMessage());
    }
  }
  /************************************ */
  public function getperte()
  {
    try {
      $request = "SELECT p.report_id, u.Nom, u.Prenom, p.reported_date FROM `perte` p
                    JOIN `users` u ON p.user_id = u.ClientID";

      $stmt = $this->connexion->prepare($request);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // Handle the error as needed
      return false;
    }
  }
  /******************************** */
  public function searchUserByEmailAndUsername($email)
  {
    try {
      $request = "SELECT `ClientID` FROM `users` WHERE `email` = :email";
      $stmt = $this->connexion->prepare($request);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->execute();

      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // Handle the error as needed
      die("Error searching user: " . $e->getMessage());
    }
  }

  public function updatepassword($newPassword, $email)
  {
    try {
      $request = "UPDATE `users` SET `Password` = :newPassword WHERE `email` = :email";
      $stmt = $this->connexion->prepare($request);
      $stmt->bindParam(':newPassword', $newPassword, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      // Additional logic as needed
    } catch (PDOException $e) {
      die("Error updating password: " . $e->getMessage());
    }
  }
}
