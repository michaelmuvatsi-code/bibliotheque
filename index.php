<?php
// index.php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Récupération des livres récents
try {
    $stmt = $pdo->query("SELECT * FROM Livres ORDER BY id DESC LIMIT 6");
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $livres = [];
}
?>

<main class="main-content">
    <div class="container">
        
        <!-- Section Héro / Bienvenue & Instructions -->
        <section class="hero-card">
            <span class="welcome-badge">
                <span class="material-symbols-outlined" style="font-size: 18px;">auto_stories</span>
                Espace de Lecture en Ligne
            </span>
            <h1 class="hero-title">Bienvenue sur votre Bibliothèque Numérique</h1>
            <p class="hero-subtitle">
                Explorez notre catalogue interactif, trouvez rapidement vos ouvrages favoris et organisez facilement vos lectures personnelles.
            </p>

            <!-- Formulaire de recherche par titre ou auteur -->
            <form action="results.php" method="GET" class="search-form">
                <div class="search-group">
                    <div class="search-input-wrapper">
                        <span class="material-symbols-outlined search-icon">search</span>
                        <input type="text" name="query" required placeholder="Rechercher un livre par son titre ou son auteur..." class="search-input">
                    </div>
                    <button type="submit" class="btn-primary">
                        <span>Rechercher</span>
                        <span class="material-symbols-outlined" style="font-size: 18px;">arrow_forward</span>
                    </button>
                </div>
            </form>

            <!-- Instructions sur la façon d'utiliser le site -->
            <div class="instructions-container">
                <h2 class="instructions-title">Comment utiliser le site ?</h2>
                <div class="instructions-grid">
                    <div class="instruction-item">
                        <div class="step-number">1</div>
                        <div class="step-text">
                            <h3>Recherchez</h3>
                            <p>Saisissez le titre d'un ouvrage ou le nom d'un auteur dans la barre de recherche.</p>
                        </div>
                    </div>
                    <div class="instruction-item">
                        <div class="step-number">2</div>
                        <div class="step-text">
                            <h3>Consultez</h3>
                            <p>Accédez aux détails complets, au résumé et au nombre d'exemplaires disponibles.</p>
                        </div>
                    </div>
                    <div class="instruction-item">
                        <div class="step-number">3</div>
                        <div class="step-text">
                            <h3>Sauvegardez</h3>
                            <p>Ajoutez le livre à votre liste de lecture pour garder une trace de vos souhaits.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Derniers livres de la collection -->
        <section>
            <div class="section-header">
                <h2 class="section-title">Nouveautés au catalogue</h2>
                <a href="results.php" class="link-all">
                    <span>Tout le catalogue</span>
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                </a>
            </div>

            <div class="books-grid">
                <?php if (!empty($livres)): ?>
                    <?php foreach ($livres as $livre): ?>
                        <article class="book-card">
                            <div>
                                <div class="book-meta">
                                    <span class="publisher-badge"><?= htmlspecialchars($livre['maison_edition'] ?? 'Édition N/A') ?></span>
                                    <span class="stock-badge"><?= (int)$livre['nombre_exemplaire'] ?> ex. dispo</span>
                                </div>
                                <h3 class="book-title"><?= htmlspecialchars($livre['titre']) ?></h3>
                                <p class="book-author">Par <?= htmlspecialchars($livre['auteur']) ?></p>
                                <p class="book-desc"><?= htmlspecialchars($livre['description'] ?? 'Aucune description disponible.') ?></p>
                            </div>
                            <a href="details.php?id=<?= $livre['id'] ?>" class="btn-secondary">
                                <span>Voir les détails</span>
                                <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
                            </a>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; background-color: white; padding: 24px; border-radius: 12px; border: 1px solid var(--border-color); text-align: center; color: var(--text-muted);">
                        Aucun livre n'est actuellement disponible dans la base de données.
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </div>
</main>

<?php require_once 'includes/footer.php'; ?>