<?php
require_once 'config/database.php';
startSession();

// Rediriger si déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (!empty($email) && !empty($password)) {
        $database = new Database();
        $db = $database->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Définir toutes les variables de session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_firstname'] = $user['first_name'];
            $_SESSION['user_lastname'] = $user['last_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_phone'] = $user['phone'] ?? '';
            
            if ($remember) {
                setcookie('user_email', $email, time() + 30 * 24 * 3600, '/');
            }
            
            header('Location: index.php');
            exit();
        } else {
            $error = 'Email ou mot de passe incorrect';
        }
    } else {
        $error = 'Veuillez remplir tous les champs';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - AutoMarket</title>
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
            <h2>Connexion</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_COOKIE['user_email'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Se souvenir de moi
                    </label>
                </div>
                <button type="submit" class="btn-primary btn-block">Se connecter</button>
            </form>
            <p class="auth-links">
                Pas encore de compte ? <a href="register.php">S'inscrire</a>
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
            max-width: 400px;
            width: 100%;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .auth-card h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--primary-dark);
        }
        .btn-block {
            width: 100%;
            padding: 1rem;
        }
        .auth-links {
            text-align: center;
            margin-top: 1rem;
        }
        .alert {
            padding: 0.8rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .checkbox {
            margin: 1rem 0;
        }
    </style>
</body>
</html>