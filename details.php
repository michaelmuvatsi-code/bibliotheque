<?php
// details.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';
require_once 'includes/header.php';

$id_livre = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$livre = null;
$message = '';
$messageType = '';

// Logique de traitement de Récupération des informations du livre
if ($id_livre > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM Livres WHERE id = :id");
        $stmt->bindParam(':id', $id_livre, PDO::PARAM_INT);
        $stmt->execute();
        $livre = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $livre = null;
    }
}

// Traitement de l'ajout à la liste de lecture
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_wishlist']) && $livre) {
    // Vérification stricte si l'utilisateur est connecté
    if (isset($_SESSION['user_id'])) {
        $id_lecteur = (int)$_SESSION['user_id']; 
        $date_emprunt = date('Y-m-d');

        try {
            // Vérification si le livre est déjà dans la liste du lecteur
            $checkStmt = $pdo->prepare("SELECT * FROM Liste_lecture WHERE id_livre = :id_livre AND id_lecteur = :id_lecteur");
            $checkStmt->execute([':id_livre' => $id_livre, ':id_lecteur' => $id_lecteur]);

            if ($checkStmt->rowCount() > 0) {
                $message = "Ce livre est déjà présent dans votre liste de lecture !";
                $messageType = "warning";
            } else {
                $insertStmt = $pdo->prepare("INSERT INTO Liste_lecture (id_livre, id_lecteur, date_emprunt) VALUES (:id_livre, :id_lecteur, :date_emprunt)");
                $insertStmt->execute([
                    ':id_livre' => $id_livre,
                    ':id_lecteur' => $id_lecteur,
                    ':date_emprunt' => $date_emprunt
                ]);
                $message = "Le livre a bien été ajouté à votre liste de lecture !";
                $messageType = "success";
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout à la liste de lecture.";
            $messageType = "warning";
        }
    } else {
        $message = "Vous devez être connecté pour ajouter un livre à votre liste de lecture.";
        $messageType = "warning";
    }
}
?>

<main class="main-content">
    <div class="container">

        <a href="results.php" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Retour aux résultats</span>
        </a>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?>">
                <span class="material-symbols-outlined"><?= $messageType === 'success' ? 'check_circle' : 'info' ?></span>
                <span><?= htmlspecialchars($message) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($livre): ?>
            <!-- Affichage détaillé -->
            <article class="details-card">
                <header class="details-header">
                    <div class="book-meta">
                        <span class="publisher-badge"><?= htmlspecialchars($livre['maison_edition'] ?? 'Édition Non Renseignée') ?></span>
                        <span class="stock-badge"><?= (int)$livre['nombre_exemplaire'] ?> exemplaires disponibles</span>
                    </div>
                    <h1 class="details-title"><?= htmlspecialchars($livre['titre']) ?></h1>
                    <p class="details-author">Auteur : <?= htmlspecialchars($livre['auteur']) ?></p>
                </header>

                <div class="details-info-grid">
                    <div class="info-box">
                        <span class="info-label">Maison d'édition</span>
                        <span class="info-value"><?= htmlspecialchars($livre['maison_edition'] ?? 'Non précisée') ?></span>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Exemplaires</span>
                        <span class="info-value"><?= (int)$livre['nombre_exemplaire'] ?> en stock</span>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Identifiant</span>
                        <span class="info-value">#<?= (int)$livre['id'] ?></span>
                    </div>
                </div>

                <h2 class="details-section-title">Résumé du livre</h2>
                <p class="details-description">
                    <?= nl2br(htmlspecialchars($livre['description'] ?? 'Aucune description disponible pour cet ouvrage.')) ?>
                </p>

                <!-- Action bar conditionnelle selon l'état de connexion -->
                <div class="action-bar">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form method="POST" action="details.php?id=<?= $livre['id'] ?>">
                            <input type="hidden" name="add_to_wishlist" value="1">
                            <button type="submit" class="btn-primary">
                                <span class="material-symbols-outlined">bookmark_add</span>
                                <span>Ajouter à ma liste de lecture</span>
                            </button>
                        </form>
                        <a href="wishlist.php" class="btn-secondary" style="width: auto; padding: 14px 20px;">
                            <span>Voir ma liste de lecture</span>
                        </a>
                    <?php else: ?>
                        <div class="alert alert-info" style="margin: 0; width: 100%;">
                            <span class="material-symbols-outlined">info</span>
                            <span>Vous devez vous <a href="/bibliotheque/session/connexion.php" style="font-weight: 600; text-decoration: underline; color: inherit;">connecter</a> pour ajouter cet ouvrage à votre liste de lecture.</span>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php else: ?>
            <div class="empty-state">
                <span class="material-symbols-outlined empty-state-icon">menu_book</span>
                <h3 class="empty-state-title">Livre introuvable</h3>
                <p class="empty-state-text">L'ouvrage sélectionné n'existe pas ou a été retiré de la base de données.</p>
                <a href="results.php" class="btn-primary">
                    <span>Retour au catalogue</span>
                </a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require_once 'includes/footer.php'; ?>