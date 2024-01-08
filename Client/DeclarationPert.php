<?php
include("../Client/securite.php");
include_once("../database/Client.php");
include_once("../database/petre.php");
include_once("../database/Abonnment.php");
include_once("../database/Database.php");

?>

<style>
  input {
    border: none;
    cursor: pointer;
    padding: 1rem;
    background-color: #1864ab;
    color: #fff;
    font-size: 1.2rem;
  }

  .declatarion {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 1rem;
  }
</style>

<body>
  <div class="container">
    <div class="navBar">
      <?php
      include("Nav.php");
      ?>
    </div>
    <div class="Offre">
      <div>
        <?php
        $ClientId = $_SESSION['ClientID'];
        $Abonnements = (new AbonnementActif(null, null, null, null, null, null, null, null, $Db->connexion))->getAbonnementByClientId($ClientId);
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
          // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          // Get data from the form
          $ClientID =  $_SESSION['ClientID'];
          // $stmt = (new AbonnementActif(null, null, null, null, null, $Db->connexion))->getAbonnementIdByClientId($user_id);
          // $User = $stmt->fetch(PDO::FETCH_ASSOC);
          // $abbnID = $User['AbonnementID'];
          $stmt3 = $conn->prepare("SELECT AbonnementID FROM AbonnementActif WHERE ClientID = :ClientID ");
          $stmt3->execute(['ClientID' => $ClientID]);

          $User = $stmt3->fetch(PDO::FETCH_ASSOC);

          $pertes = new pertes(NULL, $ClientID, NULL,  $User["AbonnementID"], false, $Db->connexion);
          $pertes->createTable();
          $pertes->addperte();
        }
        ?>

      </div>
      <div>

        <?php
        $ClientID =  $_SESSION['ClientID'];
        $pertes = new pertes(NULL, $ClientID, NULL, null, null, $Db->connexion);
        // Fetch and display perte information along with Nom and Prenom in a table
        $perteElements = $pertes->getperteById();
        $valideResult = (new pertes(NULL, $ClientID, NULL, null, null, $Db->connexion))->GetValide($ClientID);

        if ($valideResult["valide"] == 1) {
          echo "<h4>votre Demande a eté accepter veuillez vous presenter a notre burreux pour la recuperé</h4> ";
        } elseif ($perteElements) {
          echo "<h4>vous avez deja déclarer la pert</h4> ";
        } elseif (!$Abonnements) {
          echo "<h4>vous devez demander un abonnement Dabord</h4>";
        } else {
        ?>
          <div class="declatarion">
            <h4>Ajouter Une Declaration :</h4>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              <input type="submit" value="Declaré">
            </form>
          </div>
        <?php
        } ?>
      </div>

    </div>
  </div>
</body>

</html>