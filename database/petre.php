<?php
class pertes extends Database
{
    public $report_id, $user_id, $reported_date, $AbonnementID, $valide;
    public $connexion;
    public function __construct($report_id, $user_id, $reported_date, $AbonnementID, $valide, $connexion)
    {

        $this->report_id = $report_id;
        $this->user_id = $user_id;
        $this->reported_date = $reported_date;
        $this->AbonnementID = $AbonnementID;
        $this->valide = $valide;
        $this->connexion = $connexion;
    }

    public function createTable()
    {
        $request = "CREATE TABLE IF NOT EXISTS  perte (
        report_id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        AbonnementID INT,
        reported_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        valide BOOLEAN,
        FOREIGN KEY (user_id) REFERENCES users(ClientID)  ON DELETE CASCADE,
        FOREIGN KEY (AbonnementID) REFERENCES AbonnementActif(AbonnementID) ON DELETE CASCADE
    )";

        $stmt = $this->connexion->prepare($request);
        $stmt->execute();
    }

    public function addperte()
    {
        $request = "INSERT INTO `perte` ( `user_id`, `AbonnementID`,`valide`) VALUES (" .
            "'" . $this->user_id . "'," .
            "" . $this->AbonnementID . "," . // Removed the extra comma here
            "'" . $this->valide . "'" . // Removed the extra comma here
            ")";

        $stmt = $this->connexion->prepare($request);
        $stmt->execute();
    }

    public function getperte()
    {
        try {
            $request = "SELECT p.report_id,p.valide, u.Nom, u.Prenom, p.reported_date FROM `perte` p
                        JOIN `users` u ON p.user_id = u.ClientID";

            $stmt = $this->connexion->prepare($request);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the error as needed
            return false;
        }
    }
    public function getperteById()
    {
        try {
            $request = "SELECT report_id FROM `perte` WHERE user_id = :ClientID";
            $stmt = $this->connexion->prepare($request);
            $stmt->bindParam(':ClientID', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the error as needed
            return false;
        }
    }

    public function deleteperte($report_id)
    {
        try {
            $request = "DELETE FROM `perte` WHERE report_id = :report_id";
            $stmt = $this->connexion->prepare($request);
            $stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT);
            $stmt->execute();

            // Check if any row was affected (if the record existed and was deleted)
            if ($stmt->rowCount() > 0) {
                return true; // Deletion successful
            } else {
                return false; // Record with given report_id not found
            }
        } catch (PDOException $e) {
            // Handle the error as needed
            return false; // Deletion failed
        }
    }
    public function GetValide($clientid)
    {
        try {
            $request = "SELECT valide FROM perte WHERE user_id = :Clientid";
            $stmt = $this->connexion->prepare($request);
            $stmt->bindParam(':Clientid', $clientid, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch the result as an associative array
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if $result is false before accessing the array offset
            if ($result !== false && isset($result['valide'])) {
                // Return the associative array with the "valide" key
                return ['valide' => $result['valide']];
            } else {
                // Handle the case where the result is empty or "valide" key is not present
                return ['valide' => false]; // Return a default value or handle the error accordingly
            }
        } catch (PDOException $e) {
            // Handle the error as needed
            return ['valide' => false]; // Return a default value or handle the error accordingly
        }
    }
}
