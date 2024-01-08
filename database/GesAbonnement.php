<?php
include_once("Database.php");
class GesAbonnement extends Database
{
  public $NAbonnID, $ClientID, $abonnementActif, $type, $PDFCart, $personne, $PDFStudent, $prix;
  public $connexion;
  public $profile_picture;

  public function __construct($NAbonnID, $ClientID, $abonnementActif, $type, $PDFCart, $PDFStudent, $personne, $prix, $connexion)
  {
    $this->NAbonnID = $NAbonnID;
    $this->ClientID = $ClientID;
    $this->abonnementActif = $abonnementActif;
    $this->type = $type;
    $this->PDFCart = $PDFCart;
    $this->PDFStudent = $PDFStudent;
    $this->personne = $personne;
    $this->prix = $prix;
    $this->connexion = $connexion;
  }

  public function create_table()
  {
    $Request = "CREATE TABLE IF NOT EXISTS NvAbonnement (
          NAbonnID int AUTO_INCREMENT PRIMARY KEY,
          ClientID int,
          `type` varchar(50),
          `abonnementActif` BOOLEAN,
          PDFCart LONGBLOB,
          PDFStudent LONGBLOB,
          TypeClient varchar(50),
          prix int ,
          FOREIGN KEY (`ClientID`) REFERENCES `users`(`ClientID`) ON DELETE CASCADE
      );";
    $x = $this->connexion->prepare($Request);
    $e = $x->execute();
  }


  public function addAbonnement()
  {
    $request = "INSERT INTO `NvAbonnement` (`ClientID`, `type`, `abonnementActif`, `PDFCart`, `PDFStudent`, `TypeClient`,`prix`) VALUES (:ClientID, :type, :abonnementActif, :PDFCart, :PDFStudent, :TypeClient,:prix)";

    $stmt = $this->connexion->prepare($request);

    // Assuming $this->PDFCart and $this->PDFStudent contain the binary data of the PDF files
    $stmt->bindParam(':ClientID', $this->ClientID, PDO::PARAM_INT);
    $stmt->bindParam(':type', $this->type, PDO::PARAM_STR);
    $stmt->bindParam(':abonnementActif', $this->abonnementActif, PDO::PARAM_BOOL);
    $stmt->bindValue(':PDFCart', $this->PDFCart, PDO::PARAM_LOB);
    $stmt->bindValue(':PDFStudent', $this->PDFStudent, PDO::PARAM_LOB);
    $stmt->bindValue(':TypeClient', $this->personne, PDO::PARAM_LOB);
    $stmt->bindValue(':prix', $this->prix, PDO::PARAM_LOB);

    $stmt->execute();
  }

  public function getClientInfo($clientID)
  {
    $query = "SELECT * FROM users WHERE ClientID = :ClientID";
    $stmt = $this->connexion->prepare($query);
    $stmt->bindParam(':ClientID', $clientID, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result !== false) {
      return $result;
    } else {
      return false;
    }
  }
  public function getAbonnementByClientId($clientID)
  {
    try {
      $request = "SELECT * FROM `NvAbonnement` WHERE ClientID = :ClientID";
      $stmt = $this->connexion->prepare($request);
      $stmt->bindParam(':ClientID', $clientID, PDO::PARAM_INT);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // Handle the error as needed
      return false;
    }
  }

  public function updateAbonnement()
  {
    try {
      // Check if the profile picture is set
      $PDFCartUpdate = '';
      if (!empty($this->profile_picture)) {
        $PDFCartUpdate = ', `PDFCart` = :updatedPDFCart';
      }

      $request = "UPDATE `NvAbonnement` SET
            `type` = :updatedType,
            `abonnementActif` = :updatedAbonnementActif
            $PDFCartUpdate
            WHERE `NAbonnID` = :updateNAbonnID";

      $stmt = $this->connexion->prepare($request);
      $stmt->bindParam(':updateNAbonnID', $this->NAbonnID, PDO::PARAM_INT);
      $stmt->bindParam(':updatedType', $this->type, PDO::PARAM_STR);
      $stmt->bindParam(':updatedAbonnementActif', $this->abonnementActif, PDO::PARAM_BOOL);

      // Bind PDFCart parameter if set
      if (!empty($this->profile_picture)) {
        $stmt->bindParam(':updatedPDFCart', $this->profile_picture, PDO::PARAM_LOB);
      }

      return $stmt->execute();
    } catch (PDOException $e) {
      die("Error updating abonnement: " . $e->getMessage());
    }
  }



  public function getAbonnement()
  {
    try {
      $request = "SELECT * FROM `NvAbonnement`";
      $stmt = $this->connexion->prepare($request);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // Handle the error as needed
      return false;
    }
  }

  public function deleteAbonnement()
  {
    $request = "DELETE FROM `NvAbonnement` WHERE `NAbonnID` = :deleteNAbonnID";
    $stmt = $this->connexion->prepare($request);
    $stmt->bindParam(':deleteNAbonnID', $this->NAbonnID, PDO::PARAM_INT);
    return $stmt->execute();
  }

  public function getPDFData()
  {
    try {
      $request = "SELECT `PDFCart` FROM `NvAbonnement` WHERE `NAbonnID` = :NAbonnID";
      $stmt = $this->connexion->prepare($request);
      $stmt->bindParam(':NAbonnID', $this->NAbonnID, PDO::PARAM_INT);
      $stmt->execute();

      // Fetch the PDF data as binary
      $pdfData = $stmt->fetch(PDO::FETCH_COLUMN);

      return $pdfData;
    } catch (PDOException $e) {
      // Handle the error as needed
      return false;
    }
  }
  public function getPDFCart($NAbonnID)
  {
    $request = "SELECT PDFCart FROM NvAbonnement WHERE NAbonnID = :NAbonnID";
    $stmt = $this->connexion->prepare($request);
    $stmt->bindParam(':NAbonnID', $NAbonnID, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result) ? $result['PDFCart'] : null;
  }
}
