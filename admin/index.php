<?php
// admin/index.php
require_once 'auth_check.php';
require_once '../includes/db.php';
require_once '../includes/header.php';

$message = '';
$messageType = '';

// Logique de traitement de la suppression (DELETE)
// Je precise que le script de connexion à la DB est dans le dossier includes et est nommé db.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id_delete = (int)$_POST['id_livre'];
    try {
        // Supprime d'abord les références dans la wishlist si nécessaire
        $stmtWish = $pdo->prepare("DELETE FROM Liste_lecture WHERE id_livre = :id");
        $stmtWish->execute([':id' => $id_delete]);

        // Supprime le livre
        $stmt = $pdo->prepare("DELETE FROM Livres WHERE id = :id");
        $stmt->execute([':id' => $id_delete]);

        $message = "Le livre a été supprimé avec succès.";
        $messageType = "success";
    } catch (PDOException $e) {
        $message = "Erreur lors de la suppression du livre.";
        $messageType = "warning";
    }
}

// Lecture des livres (READ)
try {
    $stmt = $pdo->query("SELECT * FROM Livres ORDER BY id DESC");
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $livres = [];
}
?>

<main class="main-content">
    <div class="container">
        
        <div class="section-header">
            <div>
                <h1 class="section-title">Gestion de la Bibliothèque (Backoffice)</h1>
                <p class="results-count">Ajoutez, modifiez ou supprimez les ouvrages de la base de données[cite: 8].</p>
            </div>
            <a href="ajouter.php" class="btn-primary">
                <span class="material-symbols-outlined">add</span>
                <span>Ajouter un livre</span>
            </a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?>">
                <span class="material-symbols-outlined"><?= $messageType === 'success' ? 'check_circle' : 'info' ?></span>
                <span><?= htmlspecialchars($message) ?></span>
            </div>
        <?php endif; ?>

        <div class="admin-table-container">
            <?php if (!empty($livres)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Maison d'édition</th>
                            <th>Stock</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livres as $livre): ?>
                            <tr>
                                <td><?= (int)$livre['id'] ?></td>
                                <td><strong><?= htmlspecialchars($livre['titre']) ?></strong></td>
                                <td><?= htmlspecialchars($livre['auteur']) ?></td>
                                <td><?= htmlspecialchars($livre['maison_edition'] ?? 'N/A') ?></td>
                                <td><span class="stock-badge"><?= (int)$livre['nombre_exemplaire'] ?> ex.</span></td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 8px;">
                                        <a href="modifier.php?id=<?= $livre['id'] ?>" class="btn-secondary" style="padding: 6px 12px; width: auto;">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                        </a>
                                        <form method="POST" action="index.php" onsubmit="return confirm('Voulez-vous vraiment supprimer ce livre ?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_livre" value="<?= $livre['id'] ?>">
                                            <button type="submit" class="btn-danger" style="padding: 6px 12px;">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p class="empty-state-text">Aucun livre présent dans la base de données[cite: 8].</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php require_once '../includes/footer.php'; ?>