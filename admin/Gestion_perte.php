<?php
include("../admin/Secu.php");

require_once("../database/Agent.php");
require_once("../database/GesAbonnement.php");
require_once("../database/Abonnment.php");
include_once("../database/Database.php");
include_once("../database/Client.php");


?>

<style>
    .pageP {
        padding-top: 5rem;
    }

    table {
        width: 100%;
    }
</style>

<body>
    <div class="Presnetation">
        <?php
        echo "Welcome, " . $_SESSION['Nom'] . "!"; // Utilisez le nom d'utilisateur depuis la session

        ?>
    </div>
    <div class="container">
        <div class="left">

            <div class="navBar">
                <?php
                include("nav.php");
                ?>
            </div>

        </div>
        <div class="page pageP">
            <div></div>
            <div>
                <div class="Table">
                    <?php
                    include_once("../database/Client.php");
                    include_once("../database/petre.php");

                    $pertes = new pertes(NULL, null, NULL, null, null, $Db->connexion);

                    // Fetch and display perte information along with Nom and Prenom in a table
                    $perteElements = $pertes->getperte();


                    if ($perteElements) {
                        echo "<table border='1'>";
                        echo "<tr><th>Report ID</th><th>Nom</th><th>Prenom</th><th>Reported Date</th><th>Validé ou NON</th></tr>";

                        foreach ($perteElements as $perte) {
                            echo "<tr>";
                            echo "<td>" . $perte['report_id'] . "</td>";
                            echo "<td>" . $perte['Nom'] . "</td>";
                            echo "<td>" . $perte['Prenom'] . "</td>";
                            echo "<td>" . $perte['reported_date'] . "</td>";
                            echo "<td>" . ($perte['valide'] == 1 ? "Validé" : "Non Validé") . "</td>"; // Corrected line
                            echo "<td><form method='post' action='Gestion_perte.php'>"; // Form for each row
                            echo "<input type='hidden' name='report_id' value='" . $perte['report_id'] . "'>"; // Hidden field to store report_id
                            echo "<input type='submit' value='Delete' name='Deletepert'>";
                            echo "<input type='submit' value='confirm' name='confirm'>";
                            echo "</form></td>";
                            echo "</tr>";
                        }


                        if (isset($_POST['Deletepert'])) {
                            $report_id_to_delete = $_POST['report_id'];
                            $success = $pertes->deleteperte($report_id_to_delete);

                            if ($success) {
                                echo "Record deleted successfully!";
                                // You may want to refresh the page or update the table after deletion
                            } else {
                                echo "Failed to delete record!";
                            }
                        }
                        if (isset($_POST['confirm'])) {
                            $report = $_POST['report_id'];
                            $confirmé = True;

                            $updateStatement = $conn->prepare("UPDATE perte SET valide = :confirm WHERE report_id = :report_id");

                            $updateStatement->bindParam(':confirm', $confirmé, PDO::PARAM_STR);
                            $updateStatement->bindParam(':report_id', $report, PDO::PARAM_STR);

                            $updateStatement->execute();
                        }
                        echo "</table>";
                    } else {
                        echo "Aucune declaration";
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>


</body>

</html>