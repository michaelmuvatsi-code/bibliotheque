<?php
// admin/ajouter.php
require_once 'auth_check.php';
require_once '../includes/db.php';
require_once '../includes/header.php';

$message = '';
$messageType = '';

// Logique Logique de traitement d'ajout et validation du cote client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $maison_edition = trim($_POST['maison_edition'] ?? '');
    $nombre_exemplaire = (int)($_POST['nombre_exemplaire'] ?? 0);

    if (!empty($titre) && !empty($auteur)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO Livres (titre, auteur, description, maison_edition, nombre_exemplaire) VALUES (:titre, :auteur, :description, :maison_edition, :nombre_exemplaire)");
            $stmt->execute([
                ':titre' => $titre,
                ':auteur' => $auteur,
                ':description' => $description,
                ':maison_edition' => $maison_edition,
                ':nombre_exemplaire' => $nombre_exemplaire
            ]);

            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout du livre : " . $e->getMessage();
            $messageType = "warning";
        }
    } else {
        $message = "Veuillez remplir au moins le titre et l'auteur.";
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
            <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 24px;">Ajouter un nouveau livre</h1>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <span class="material-symbols-outlined">info</span>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="ajouter.php">
                <div class="form-group">
                    <label class="form-label">Titre du livre *</label>
                    <input type="text" name="titre" required class="form-control" placeholder="ex: Le Petit Prince">
                </div>

                <div class="form-group">
                    <label class="form-label">Auteur *</label>
                    <input type="text" name="auteur" required class="form-control" placeholder="ex: Antoine de Saint-Exupéry">
                </div>

                <div class="form-group">
                    <label class="form-label">Maison d'édition</label>
                    <input type="text" name="maison_edition" class="form-control" placeholder="ex: Gallimard">
                </div>

                <div class="form-group">
                    <label class="form-label">Nombre d'exemplaires *</label>
                    <input type="number" name="nombre_exemplaire" min="0" value="1" required class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Description / Résumé</label>
                    <textarea name="description" class="form-control" placeholder="Résumé complet de l'ouvrage..."></textarea>
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn-secondary" style="width: auto; padding: 12px 20px;">Annuler</a>
                    <button type="submit" class="btn-primary">
                        <span class="material-symbols-outlined">save</span>
                        <span>Enregistrer le livre</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<?php require_once '../includes/footer.php'; ?>