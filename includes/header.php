<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération du chemin de la page courante pour gérer la surbrillance du menu
$current_page = $_SERVER['SCRIPT_NAME'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioTech - Bibliothèque en ligne</title>
    <!-- Polices et Icônes légères -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <!-- Fichier CSS séparé -->
    <link rel="stylesheet" href="/bibliotheque/css/style.css">
</head>
<body>

    <header class="site-header">
        <div class="container header-container">
            <a href="/bibliotheque/index.php" class="logo">
                <span class="material-symbols-outlined logo-icon">local_library</span>
                <span>BiblioTech</span>
            </a>
            
            <!-- Bouton Burger Mobile -->
            <button class="mobile-menu-toggle" id="mobileMenuBtn" aria-label="Menu">
                <span class="material-symbols-outlined">menu</span>
            </button>

            <nav class="desktop-nav" id="mainNav">
                <a href="/bibliotheque/index.php" class="nav-link <?= (strpos($current_page, 'index.php') !== false && strpos($current_page, '/admin/') === false) ? 'active' : '' ?>">Accueil</a>
                <a href="/bibliotheque/results.php" class="nav-link <?= strpos($current_page, 'results.php') !== false ? 'active' : '' ?>">Recherche</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Liens pour les utilisateurs connectés -->
                    <a href="/bibliotheque/wishlist.php" class="nav-link <?= strpos($current_page, 'wishlist.php') !== false ? 'active' : '' ?>">Ma Liste</a>

                    <?php if (isset($_SESSION['user_role']) && strtolower($_SESSION['user_role']) === 'admin'): ?>
                        <!-- Lien affiché uniquement pour l'admin avec surbrillance si on est dans le dossier admin -->
                        <a href="/bibliotheque/admin/index.php" class="btn-admin-link <?= strpos($current_page, '/admin/') !== false ? 'active' : '' ?>">Gestion Admin</a>
                    <?php endif; ?>

                    <a href="/bibliotheque/session/deconnexion.php" class="nav-link">Déconnexion</a>
                <?php else: ?>
                    <!-- Liens pour les visiteurs non connectés -->
                    <a href="/bibliotheque/session/connexion.php" class="nav-link <?= strpos($current_page, 'connexion.php') !== false ? 'active' : '' ?>">Connexion</a>
                    <a href="/bibliotheque/session/inscription.php" class="btn-admin-link <?= strpos($current_page, 'inscription.php') !== false ? 'active' : '' ?>">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>