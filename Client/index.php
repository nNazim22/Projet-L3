<?php
include("../Client/securite.php");
?>


<body>
  <div class="container">
    <div class="navBar">
      <?php
      include("Nav.php");
      ?>
    </div>
    <div class="Offre">
      <div class="offreContainer">
        <div>
          <h2>Voici Nos Offres</h2>
        </div>
        <div class="AbonnementOffre">
          <div class="Cart">
            <div class="contenueCart">
              <h1>Semain</h1>
              <ul>
                <p class="Paragraph">vous avez le droit de prendre :</p>
                <li>
                  <p>✔ Tram </p>
                </li>
                <li>
                  <p>✖ Metro</p>

                </li>
              </ul>
            </div>
            <div class="rel">
              <a href="NouvelleDemande.php?prixs=1000">1000DA</a>
            </div>
          </div>
          <div class="Cart">
            <div class="contenueCart">
              <h1>Mois</h1>
              <ul>
                <p class="Paragraph">vous avez le droit de prendre :</p>
                <li>
                  <p>✔ Tram </p>

                </li>
                <li>
                  <p>✔ Metro</p>
                </li>
              </ul>
            </div>
            <div class="rel">
              <a href="NouvelleDemande.php?prixm=2000">2000DA</a>
            </div>
          </div>
          <div class="Cart">
            <div class="contenueCart">
              <h1>Année</h1>
              <ul>
                <p class="Paragraph">vous avez le droit de prendre :</p>
                <li>
                  <p>✔ Tram </p>
                </li>
                <li>
                  <p>✔ Metro</p>

                </li>
              </ul>
            </div>
            <div class="rel">
              <a href="NouvelleDemande.php?prixa=3000">3000DA</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    var profileLink = document.getElementById("profileLink");
    var dropdown = document.getElementById("dropdown");

    // Use click event instead of mouseover and mouseout
    profileLink.addEventListener("click", function(e) {
      e.stopPropagation(); // Prevents the document click event from closing the dropdown immediately
      dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
    });

    // Close the dropdown when clicking outside
    document.addEventListener("click", function() {
      dropdown.style.display = "none";
    });

    // Prevent the dropdown from closing when clicking inside it
    dropdown.addEventListener("click", function(e) {
      e.stopPropagation();
    });
  });
</script>