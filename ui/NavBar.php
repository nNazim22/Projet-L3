<div class="navBar">
  <nav class="nav">
    <img class="LogoImg" src="./ui/Logo.png">
    <ul class=" navList">
      <li><a href="/ProjetWeb/index.php#Head">Home</a></li>
      <li><a href="/ProjetWeb/index.php#offre">Offre</a></li>
      <li><a href="/ProjetWeb/index.php#contact">Contactez nous</a></li>
      <li><a href="/ProjetWeb/Login.php"><?php
                                          session_start();
                                          if (isset($_SESSION['ClientID']) || isset($_SESSION['user_id'])) {
                                            echo "Profile";
                                          } else {
                                            echo "Se connecter";
                                          }

                                          ?></a></li>
    </ul>
  </nav>
</div>