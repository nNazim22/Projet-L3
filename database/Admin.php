<?php
require_once("../ProjetWeb/database/Database.php");

class Admin extends Database
{
  public $ID, $Nom, $Email, $Mdp, $type;
  public $connexion;
  public function __construct($ID, $Nom, $Email, $Mdp, $type, $connexion)
  {
    $this->ID = $ID;
    $this->Nom = $Nom;
    $this->Email = $Email;
    $this->Mdp = $Mdp;
    $this->type = $type;
    $this->connexion = $connexion;
  }
  public function create_table()
  {
    $Request = "CREATE TABLE IF NOT EXISTS `administrareurs` (
      `ID` int AUTO_INCREMENT PRIMARY KEY,
      `Nom` varchar(10),
      `Email` varchar(50),
      `Password` varchar(50),
      `type` varchar(50)
      
    )";

    $x = $this->connexion->prepare($Request);
    $e = $x->execute();
  }
  public function add_Admin()
  {
    $request = "INSERT INTO `administrareurs` ( `Nom`, `Email`,`Password`,`type`) VALUES (" .
      "'" . $this->Nom . "'," .
      "'" . $this->Email . "'," .
      "'" . $this->Mdp . "'," .
      "'" . $this->type . "'" .
      ")";

    $x = $this->connexion->prepare($request);
    $e = $x->execute();
  }
}
