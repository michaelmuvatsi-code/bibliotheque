<?php
// wishlist.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si l'utilisateur n'est pas connecté, redirection vers la connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: /bibliotheque/session/connexion.php");
    exit;
}

require_once 'includes/db.php';
require_once 'includes/header.php';

$id_lecteur = 1;
$message = '';
$messageType = '';

// Traitement de la suppression d'un livre de la liste de lecture
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id_livre'])) {
    $id_livre_remove = (int)$_POST['remove_id_livre'];

    try {
        $deleteStmt = $pdo->prepare("DELETE FROM Liste_lecture WHERE id_livre = :id_livre AND id_lecteur = :id_lecteur");
        $deleteStmt->execute([
            ':id_livre' => $id_livre_remove,
            ':id_lecteur' => $id_lecteur
        ]);

        if ($deleteStmt->rowCount() > 0) {
            $message = "Le livre a été retiré de votre liste de lecture avec succès.";
            $messageType = "success";
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la suppression du livre de la liste.";
        $messageType = "warning";
    }
}

// Récupération des livres enregistrés dans la liste de lecture avec une jointure
try {
    $sql = "SELECT l.*, ll.date_emprunt 
            FROM Liste_lecture ll 
            JOIN Livres l ON ll.id_livre = l.id 
            WHERE ll.id_lecteur = :id_lecteur 
            ORDER BY ll.date_emprunt DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_lecteur' => $id_lecteur]);
    $wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $wishlist = [];
}
?>

<main class="main-content">
    <div class="container">
        
        <a href="index.php" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Retour à l'accueil</span>
        </a>

        <div class="search-header-box">
            <h1 class="search-header-title">Ma Liste de Lecture</h1>
            <p class="empty-state-text" style="margin: 0; text-align: left;">
                Consultez et gérez les ouvrages que vous avez sauvegardés pour vos lectures futures[cite: 8].
            </p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?>">
                <span class="material-symbols-outlined"><?= $messageType === 'success' ? 'check_circle' : 'info' ?></span>
                <span><?= htmlspecialchars($message) ?></span>
            </div>
        <?php endif; ?>

        <section>
            <?php if (!empty($wishlist)): ?>
                <div>
                    <?php foreach ($wishlist as $item): ?>
                        <article class="wishlist-item">
                            <div class="wishlist-info">
                                <h2 class="book-title"><?= htmlspecialchars($item['titre']) ?></h2>
                                <p class="book-author">Par <?= htmlspecialchars($item['auteur']) ?></p>
                                <span class="wishlist-date">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">calendar_today</span>
                                    Ajouté le <?= date('d/m/Y', strtotime($item['date_emprunt'])) ?>
                                </span>
                            </div>
                            
                            <div class="wishlist-actions">
                                <a href="details.php?id=<?= $item['id'] ?>" class="btn-secondary" style="width: auto; padding: 10px 16px;">
                                    <span>Détails</span>
                                    <span class="material-symbols-outlined">visibility</span>
                                </a>

                                <!-- Formulaire pour retirer le livre de la liste de lecture -->
                                <form method="POST" action="wishlist.php" onsubmit="return confirm('Voulez-vous vraiment retirer ce livre de votre liste ?');">
                                    <input type="hidden" name="remove_id_livre" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn-danger">
                                        <span class="material-symbols-outlined">delete</span>
                                        <span>Retirer</span>
                                    </button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <span class="material-symbols-outlined empty-state-icon">bookmark_border</span>
                    <h3 class="empty-state-title">Votre liste de lecture est vide</h3>
                    <p class="empty-state-text">Vous n'avez pas encore ajouté de livre à votre liste. Parcourez le catalogue pour découvrir des ouvrages inspirants[cite: 8].</p>
                    <a href="results.php" class="btn-primary">
                        <span>Explorer le catalogue</span>
                    </a>
                </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<?php require_once 'includes/footer.php'; ?>