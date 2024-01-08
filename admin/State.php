<?php
include_once("../admin/Secu.php");
require_once("../database/Abonnment.php"); // Adjusted the include statement

$abonnementObj = new AbonnementActif(null, null, null, null, null, null, null, null, $Db->connexion);
$dataAverageAbonnement = $abonnementObj->getAverageAbonnementByType();

$dataPointsAverageAbonnement = array();

foreach ($dataAverageAbonnement as $entry) {
  $dataPointsAverageAbonnement[] = array(
    "label" => "Type " . $entry['type'],
    "y" => floatval($entry['averageAbonnement']) // Ensure that 'averageAbonnement' is converted to float
  );
}

$query = "SELECT TypeClient, COUNT(AbonnementID) as numAbonnements FROM `AbonnementActif` GROUP BY TypeClient";
$stmt = $Db->connexion->prepare($query); // Updated the connection object to use $Db->connexion
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dataPointsNumAbonnements = array();
foreach ($results as $row) {
  $dataPointsNumAbonnements[] = array(
    "label" => $row['TypeClient'],
    "y" => intval($row['numAbonnements']) // Ensure that 'numAbonnements' is converted to int
  );
}
?>
<style>
  .graph {
    width: 100%;
    margin-top: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #chartContainer,
  #chartContainer2 {
    width: 100%;
    height: 70vh;
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
    <div class="page">
      <div></div>
      <div class="graph">
        <div id="chartContainer"></div>
        <div id="chartContainer2"></div>
        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
        <script>
          window.onload = function() {
            var chart1 = new CanvasJS.Chart("chartContainer", {
              animationEnabled: true,
              exportEnabled: true,
              title: {
                text: "Average Abonnement per Type"
              },
              subtitles: [{
                text: "Abonnement data"
              }],
              data: [{
                type: "pie",
                showInLegend: true,
                legendText: "{label}",
                indexLabelFontSize: 16,
                indexLabel: "{label} - #percent%",
                yValueFormatString: "#0.##",
                dataPoints: <?php echo json_encode($dataPointsAverageAbonnement, JSON_NUMERIC_CHECK); ?>
              }]
            });
            chart1.render();

            var chart2 = new CanvasJS.Chart("chartContainer2", {
              animationEnabled: true,
              exportEnabled: true,
              title: {
                text: "Number of Abonnements per Type"
              },
              subtitles: [{
                text: "Abonnement data"
              }],
              data: [{
                type: "pie",
                showInLegend: true,
                legendText: "{label}",
                indexLabelFontSize: 16,
                indexLabel: "{label} - #percent%",
                yValueFormatString: "#0.##",
                dataPoints: <?php echo json_encode($dataPointsNumAbonnements, JSON_NUMERIC_CHECK); ?>
              }]
            });
            chart2.render();
          }
        </script>
      </div>
    </div>
  </div>
</body>

</html>