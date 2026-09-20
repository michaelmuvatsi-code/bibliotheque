<?php
// results.php
require_once 'includes/db.php';
require_once 'includes/header.php';

$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
$livres = [];

if (!empty($searchQuery)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM Livres WHERE titre LIKE :query OR auteur LIKE :query ORDER BY id DESC");
        $searchTerm = '%' . $searchQuery . '%';
        $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $livres = [];
    }
} else {
    try {
        $stmt = $pdo->query("SELECT * FROM Livres ORDER BY id DESC");
        $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $livres = [];
    }
}
?>

<main class="main-content">
    <div class="container">
        
        <a href="index.php" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Retour à l'accueil</span>
        </a>

        <div class="search-header-box">
            <h1 class="search-header-title">
                <?= !empty($searchQuery) ? 'Résultats pour « ' . htmlspecialchars($searchQuery) . ' »' : 'Tous les livres du catalogue' ?>
            </h1>

            <form action="results.php" method="GET" class="search-form search-form-full">
                <div class="search-group">
                    <div class="search-input-wrapper">
                        <span class="material-symbols-outlined search-icon">search</span>
                        <input type="text" name="query" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Rechercher par titre ou auteur..." class="search-input">
                    </div>
                    <button type="submit" class="btn-primary">
                        <span>Mettre à jour</span>
                    </button>
                </div>
            </form>
        </div>

        <section>
            <div class="section-header">
                <span class="results-count"><?= count($livres) ?> livre(s) trouvé(s)</span>
            </div>

            <?php if (!empty($livres)): ?>
                <div class="books-grid">
                    <?php foreach ($livres as $livre): ?>
                        <article class="book-card">
                            <div>
                                <div class="book-meta">
                                    <span class="publisher-badge"><?= htmlspecialchars($livre['maison_edition'] ?? 'Édition N/A') ?></span>
                                    <span class="stock-badge"><?= (int)$livre['nombre_exemplaire'] ?> ex. dispo</span>
                                </div>
                                <h2 class="book-title"><?= htmlspecialchars($livre['titre']) ?></h2>
                                <p class="book-author">Par <?= htmlspecialchars($livre['auteur']) ?></p>
                                <p class="book-desc"><?= htmlspecialchars($livre['description'] ?? 'Aucune description disponible.') ?></p>
                            </div>
                            <a href="details.php?id=<?= $livre['id'] ?>" class="btn-secondary">
                                <span>Voir les détails</span>
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <span class="material-symbols-outlined empty-state-icon">search_off</span>
                    <h3 class="empty-state-title">Aucun livre trouvé</h3>
                    <p class="empty-state-text">Nous n'avons trouvé aucun ouvrage correspondant à votre recherche. Vérifiez l'orthographe ou essayez avec un autre mot-clé[cite: 8].</p>
                    <a href="results.php" class="btn-primary">
                        <span>Voir tout le catalogue</span>
                    </a>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php require_once 'includes/footer.php'; ?>