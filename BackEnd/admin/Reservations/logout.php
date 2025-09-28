<?php
//fichier de deconnexion
session_start();
session_destroy();
header("Location: http://localhost/PFF/PartiePratique/BackEnd/admin/login.php");
exit();
?>