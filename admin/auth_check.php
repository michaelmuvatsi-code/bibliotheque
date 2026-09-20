<?php
// admin/auth_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bloque l'accès si l'utilisateur n'est pas connecté OU n'est pas admin (vérification insensible à la casse aussi)
if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') {
    header("Location: ../session/connexion.php");
    exit;
}