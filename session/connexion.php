<?php
// session/connexion.php
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['mot_de_passe'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM Lecteurs WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($pass, $user['mot_de_passe'])) {
        // Enregistrement des informations en session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_role'] = $user['role'];

        // Redirections vers la page Admin
        if (strtolower($user['role']) === 'admin') {
            header("Location: /bibliotheque/admin/index.php");
        } else {
            header("Location: /bibliotheque/index.php");
        }
        exit;
    } else {
        $message = "Identifiants incorrects.";
    }
}
?>

<main class="main-content">
    <div class="container">
        <div class="form-card">
            <h1 class="search-header-title">Connexion</h1>

            <?php if (!empty($message)): ?>
                <div class="alert alert-warning">
                    <span class="material-symbols-outlined">warning</span>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="connexion.php">
                <div class="form-group">
                    <label class="form-label">Adresse Email *</label>
                    <input type="email" name="email" required class="form-control" placeholder="exemple@email.com" autocomplete="email">
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe *</label>
                    <input type="password" name="mot_de_passe" required class="form-control" placeholder="******" autocomplete="current-password">
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px;">Se connecter</button>
            </form>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>