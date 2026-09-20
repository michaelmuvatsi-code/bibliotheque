<?php
// admin/modifier.php
require_once 'auth_check.php';
require_once '../includes/db.php';
require_once '../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$messageType = '';

// Ce bloc permet la récupération des données existantes
try {
    $stmt = $pdo->prepare("SELECT * FROM Livres WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $livre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$livre) {
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    header("Location: index.php");
    exit;
}

// Mise à jour (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $maison_edition = trim($_POST['maison_edition'] ?? '');
    $nombre_exemplaire = (int)($_POST['nombre_exemplaire'] ?? 0);

    if (!empty($titre) && !empty($auteur)) {
        try {
            $updateStmt = $pdo->prepare("UPDATE Livres SET titre = :titre, auteur = :auteur, description = :description, maison_edition = :maison_edition, nombre_exemplaire = :nombre_exemplaire WHERE id = :id");
            $updateStmt->execute([
                ':titre' => $titre,
                ':auteur' => $auteur,
                ':description' => $description,
                ':maison_edition' => $maison_edition,
                ':nombre_exemplaire' => $nombre_exemplaire,
                ':id' => $id
            ]);

            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $message = "Erreur lors de la mise à jour.";
            $messageType = "warning";
        }
    } else {
        $message = "Le titre et l'auteur sont obligatoires.";
        $messageType = "warning";
    }
}
?>

<main class="main-content">
    <div class="container">
        
        <a href="index.php" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span>
            <span>Retour à la gestion</span>
        </a>

        <div class="form-card">
            <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 24px;">Modifier le livre #<?= (int)$livre['id'] ?></h1>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <span class="material-symbols-outlined">info</span>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="modifier.php?id=<?= $livre['id'] ?>">
                <div class="form-group">
                    <label class="form-label">Titre du livre *</label>
                    <input type="text" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Auteur *</label>
                    <input type="text" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Maison d'édition</label>
                    <input type="text" name="maison_edition" value="<?= htmlspecialchars($livre['maison_edition'] ?? '') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Nombre d'exemplaires *</label>
                    <input type="number" name="nombre_exemplaire" min="0" value="<?= (int)$livre['nombre_exemplaire'] ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Description / Résumé</label>
                    <textarea name="description" class="form-control"><?= htmlspecialchars($livre['description'] ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn-secondary" style="width: auto; padding: 12px 20px;">Annuler</a>
                    <button type="submit" class="btn-primary">
                        <span class="material-symbols-outlined">update</span>
                        <span>Mettre à jour</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<?php require_once '../includes/footer.php'; ?>