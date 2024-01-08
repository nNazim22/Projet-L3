<?php
include_once("Database.php");

class refus extends database
{



  public $report_ID, $userID, $abnmID, $report;
  public $connexion;
  public function __construct($report_ID, $report, $userID, $connexion)
  {
    $this->report_ID = $report_ID;
    $this->report = $report;
    $this->userID = $userID;
    $this->connexion = $connexion;
  }
  public function report_table()
  {
    try {
      $Request = "CREATE TABLE IF NOT EXISTS report ( 
              report_ID int AUTO_INCREMENT PRIMARY KEY,
              report varchar(1500),
              userID int,
              FOREIGN KEY (`userID`) REFERENCES `users`(`ClientID`) ON DELETE CASCADE
          )";

      $stmt = $this->connexion->prepare($Request);
      $stmt->execute();
    } catch (PDOException $e) {
      echo "Error creating report table: " . $e->getMessage();
    }
  }


  public function addreport()
  {
    $request = "INSERT INTO `report` (`report`, `userID`) VALUES (" .
      "'" . $this->report . "'," .
      "'" . $this->userID . "'" .
      ")";

    $stmt = $this->connexion->prepare($request);
    $stmt->execute();
  }
  public function getreport($id)
  {
    try {
      $request = "SELECT * FROM report WHERE userID = :id";

      $stmt = $this->connexion->prepare($request);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();

      // Fetch and return the result
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "Error getting report: " . $e->getMessage();
      return false;
    }
  }
}
