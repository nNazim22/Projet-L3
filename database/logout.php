<?php
session_start();
session_destroy();
header("Location: /ProjetWeb/index.php");
exit;
