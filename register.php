<?php
require_once 'config/database.php';
startSession();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;
    
    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs obligatoires';
    } elseif ($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Cet email est déjà utilisé';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password, phone, newsletter) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$firstname, $lastname, $email, $hashed_password, $phone, $newsletter])) {
                $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                header('refresh:2;url=login.php');
            } else {
                $error = 'Erreur lors de l\'inscription';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - AutoMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <div class="logo">
                <a href="index.php">
                    <div class="logo-wrapper">
                        <i class="fas fa-car logo-icon"></i>
                        <div class="logo-text">
                            <h1>Auto<span>Market</span></h1>
                            <p>Votre confiance, notre priorité</p>
                        </div>
                    </div>
                </a>
            </div>
        </nav>
    </header>

    <div class="auth-container">
        <div class="auth-card">
            <h2>Inscription</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">Prénom *</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Nom *</label>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Mot de passe *</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmer *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group checkbox">
                    <label>
                        <input type="checkbox" name="newsletter"> Recevoir la newsletter
                    </label>
                </div>
                <button type="submit" class="btn-primary btn-block">S'inscrire</button>
            </form>
            <p class="auth-links">
                Déjà inscrit ? <a href="login.php">Se connecter</a>
            </p>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="copyright">
                <p>&copy; 2024 AutoMarket - Tous droits réservés</p>
            </div>
        </div>
    </footer>

    <style>
        .auth-container {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
        }
        .auth-card {
            max-width: 500px;
            width: 100%;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>