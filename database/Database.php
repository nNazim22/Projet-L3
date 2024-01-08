<?php


class Database
{
  public $connexion, $db_name;

  public function __construct($db_name)
  {
    $this->db_name = $db_name;
  }

  public function get_connexion()
  {
    return $this->connexion;
  }

  // connection au serveur
  public function connexion_to_server()
  {
    try {
      $this->connexion = new PDO("mysql:host=mysql-nazim.alwaysdata.net", "nazim", "<H@UMEL>");
      $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "Connexion réussie au serveur.<br>";
    } catch (PDOException $e) {
      die("Erreur de connexion au serveur : " . $e->getMessage());
    }
  }

  // connection à la base de données
  public function connect_to_db()
  {
    try {
      $this->connexion = new PDO("mysql:host=mysql-nazim.alwaysdata.net;dbname=" . $this->db_name, "nazim", "<H@UMEL>");
      $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    return $this->connexion;
  }


  // création de la base de données
  public function create_db()
  {
    try {
      $request = "CREATE DATABASE IF NOT EXISTS `" . $this->db_name . "`";
      $x = $this->connexion->prepare($request);
      $e = $x->execute();
    } catch (PDOException $e) {
      die("Erreur lors de la création de la base de données : " . $e->getMessage());
    }
  }
}



$Db = new Database("nazim_setram");
$Db->connexion_to_server();
$Db->create_db();
$conn = $Db->connect_to_db();
