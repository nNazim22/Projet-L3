<nav class="nav">
  <a href="/ProjetWeb/index.php">
    <img class="LogoImgLog" src="../ui/Logo.png">
  </a>
  <ul>
    <li><a href="/ProjetWeb/Client/NouvelleDemande.php"">nouvelle demande</a></li>
          <li><a href=" /ProjetWeb/Client/DeclarationPert.php"">Declaration de pert</a></li>
    <div id="profileLinkContainer">
      <li> <a href="#" id="profileLink"><?php echo $_SESSION['Nom']; ?></a>
        <div id="dropdown">
          <ul>
            <li><a href="/ProjetWeb/Client/Profile.php">Profile</a></li>
            <li><a href="/ProjetWeb/database/Logout.php">Logout</a></li>
          </ul>
        </div>
      </li>
    </div>
  </ul>
</nav>