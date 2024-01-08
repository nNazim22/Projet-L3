<?php
include("../agent/Secu.php");
?>


<body>

  <div class="Presnetation">
    <?php
    echo "Welcome, " . $_SESSION['FirstName'] . "!"; // Utilisez le nom d'utilisateur depuis la session

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
      <h2>Vous etez connecter en tanque admin</h2>
    </div>
  </div>
</body>

</html>