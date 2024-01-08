<?php
include("./ui/Head.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

?>
<div class="container">

  <div>
    <?php
    include("./ui/NavBar.php");
    ?>

  </div>

  <head>
    <div id="Head">
      <div>

        <div class="First">
          <h1>Bienvenue chez Setram </h1>
          <h4>Decouvré nos abonnement </h4>
          <a href="#offre">En Cliquant ICI</a>
        </div>

      </div>
    </div>

  </head>

  <div>
    <div class="Offre" id="offre">
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
              <a href="/ProjetWeb/Client/NouvelleDemande.php?prixs=1000">1000DA</a>
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
              <a href="/ProjetWeb/Client/NouvelleDemande.php?prixm=2000">2000DA</a>
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
              <a href="/ProjetWeb/Client/NouvelleDemande.php?prixa=3000">3000DA</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="Contact" id="contact">
    <div class="Map">
      <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12793.427076295784!2d3.098017!3d36.713992!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128e52ccb17e2387%3A0xc8ecfd212a41a49d!2sSetram!5e0!3m2!1sfr!2sdz!4v1704379929201!5m2!1sfr!2sdz" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <div class="message">
      <div>
        <h2>Laissé un message</h2>
        <form action="/ProjetWeb/index.php#contact" method="psot">

          <input type="text" name="EmailV" placeholder="Exemple@gmail.com" id="">
          <textarea name="message" id="" cols="30" rows="10"></textarea>
          <button name="Submit">Envoyé</button>
        </form>
      </div>
    </div>
  </div>

  <div>


    <?php
    include("./ui/Footer.php");
    ?>
  </div>
</div>