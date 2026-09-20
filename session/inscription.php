<?php
// inscription.php
require_once '../includes/db.php';
require_once '../includes/header.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['mot_de_passe'] ?? '';

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($pass)) {
        $passHash = password_hash($pass, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO Lecteurs (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :pass, 'lecteur')");
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':pass' => $passHash
            ]);

            $message = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            $messageType = "success";
        } catch (PDOException $e) {
            if ($e->getCode() == '23000' || (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062)) {
                $message = "Cet email est déjà utilisé.";
            } else {
                $message = "Erreur de base de données : " . htmlspecialchars($e->getMessage());
            }
            $messageType = "warning";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
        $messageType = "warning";
    }
}
?>

<main class="main-content">
    <div class="container">
        <div class="form-card">
            <h1 class="search-header-title">Créer un compte</h1>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <span class="material-symbols-outlined">info</span>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="inscription.php">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="nom" required class="form-control" placeholder="Ex: Muvatsi" autocomplete="family-name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prénom *</label>
                        <input type="text" name="prenom" required class="form-control" placeholder="Ex: Michael" autocomplete="given-name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Adresse Email *</label>
                    <input type="email" name="email" required class="form-control" placeholder="exemple@email.com" autocomplete="email">
                </div>

                <div class="form-group">
                    <label class="form-label">Mot de passe *</label>
                    <input type="password" name="mot_de_passe" required minlength="6" class="form-control" placeholder="••••••••" autocomplete="new-password" oninvalid="this.setCustomValidity('Le mot de passe doit avoir au moins 6 caractères.')" oninput="this.setCustomValidity('')">
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px;">
                    <span>S'inscrire</span>
                </button>
            </form>

            <p style="margin-top: 16px; font-size: 0.9rem; text-align: center;">
                Déjà inscrit ? <a href="connexion.php" style="color: var(--primary); font-weight: 600;">Se connecter</a>
            </p>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>