<?php
// deconnexion.php
session_start();
session_unset();
session_destroy();

// Redirection vers l'accueil
header("Location: /bibliotheque/index.php");
exit;