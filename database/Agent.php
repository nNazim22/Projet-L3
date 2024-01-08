<?php
include_once("Database.php");

class Agent extends Database
{
  public $Agent_ID, $FirstName, $LastName, $date, $Email, $Num, $Genre, $Mdp, $type;
  public $connexion;
  public function __construct($Agent_ID, $FirstName, $LastName, $date, $Email, $Num, $Genre, $Mdp, $type, $connexion)
  {
    $this->Agent_ID = $Agent_ID;
    $this->FirstName = $FirstName;
    $this->LastName = $LastName;
    $this->Email = $Email;
    $this->date = $date;
    $this->Num = $Num;
    $this->Genre = $Genre;
    $this->Mdp = $Mdp;
    $this->type = $type;
    $this->connexion = $connexion;
  }
  public function create_table()
  {
    $Request = "CREATE TABLE IF NOT EXISTS `Agents` (
      `Agent_ID` int AUTO_INCREMENT PRIMARY KEY,
      `FirstName` varchar(10),
      `LastName` varchar(10),
      `dateDeNaissance` date,
      `Email` varchar(50),
      `NumeroTlphne` int,
      `Genre` varchar(50),
      `Password` varchar(50),
      `type` varchar(50)
      
    )";

    $x = $this->connexion->prepare($Request);
    $e = $x->execute();
  }


  public function add_Agent()
  {
    $request = "INSERT INTO `Agents` ( `FirstName`, `LastName`,`dateDeNaissance`, `Email`,`NumeroTlphne`,`Genre`,`Password`,`type`) VALUES (" .
      "'" . $this->FirstName . "'," .
      "'" . $this->LastName . "'," .
      "'" . $this->date . "'," .
      "'" . $this->Email . "'," .
      "" . $this->Num . "," .
      "'" . $this->Genre . "'," .
      "'" . $this->Mdp . "'," .
      "'" . $this->type . "'" .
      ")";

    $x = $this->connexion->prepare($request);
    $e = $x->execute();
  }
  public function deleteAgent()
  {
    $request = "DELETE FROM `Agents` WHERE `Agent_ID` = :deleteID";
    $stmt = $this->connexion->prepare($request);
    $stmt->bindParam(':deleteID', $this->Agent_ID, PDO::PARAM_INT);
    return $stmt->execute();
  }
  public function updateAgent()
  {
    $request = "UPDATE `Agents` SET
            `FirstName` = :updatedNom,
            `LastName` = :updatedPrenom,
            `dateDeNaissance` = :updateddate_de_naissance,
            `NumeroTlphne` = :updatedNumeroTlphne,
            `Email` = :updatedemail,
            `Genre` = :updatedGenre
            WHERE `Agent_ID` = :updateID";

    $stmt = $this->connexion->prepare($request);
    $stmt->bindParam(':updateID', $this->Agent_ID, PDO::PARAM_INT);
    $stmt->bindParam(':updatedNom', $this->FirstName, PDO::PARAM_STR);
    $stmt->bindParam(':updatedPrenom', $this->LastName, PDO::PARAM_STR);
    $stmt->bindParam(':updateddate_de_naissance', $this->date, PDO::PARAM_STR);
    $stmt->bindParam(':updatedNumeroTlphne', $this->Num, PDO::PARAM_INT);
    $stmt->bindParam(':updatedemail', $this->Email, PDO::PARAM_STR);
    $stmt->bindParam(':updatedGenre', $this->Genre, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function getAgent()
  {
    try {
      $request = "SELECT * FROM `Agents`";
      $stmt = $this->connexion->prepare($request);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // Handle the error as needed
      return false;
    }
  }
}
