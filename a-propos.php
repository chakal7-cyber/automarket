<?php
require_once 'config/database.php';
startSession();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - AutoMarket</title>
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
            
            <div class="nav-menu">
                <ul class="nav-links">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="vehicules.php">Véhicules</a></li>
                    <li><a href="location.php">Location</a></li>
                    <li><a href="services.php">Services</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="a-propos.php" class="active">À propos</a></li>
                </ul>
                <div class="nav-auth">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-menu">
                            <span>Bonjour <?php echo htmlspecialchars($_SESSION['user_firstname'] ?? $_SESSION['user_email'] ?? 'Utilisateur'); ?></span>
                            <a href="logout.php" class="btn-logout">Déconnexion</a>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn-login">Connexion</a>
                        <a href="register.php" class="btn-register">Inscription</a>
                    <?php endif; ?>
                </div>
                <button class="mobile-menu-btn" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>

    <div class="page-header">
        <div class="container">
            <h1>À propos d'AutoMarket</h1>
            <p>Découvrez notre histoire et nos valeurs</p>
        </div>
    </div>

    <section class="our-story">
        <div class="container">
            <div class="story-content">
                <div class="story-text">
                    <span class="section-tag">Notre histoire</span>
                    <h2>15 ans d'excellence automobile</h2>
                    <p>Fondé en 2010 par des passionnés d'automobile, AutoMarket est devenu une référence incontournable dans le domaine de la vente et location de véhicules d'occasion.</p>
                    <p>Notre mission : proposer une sélection unique de véhicules de qualité, soigneusement choisis et rigoureusement contrôlés.</p>
                    <p>Aujourd'hui, c'est plus de 1000 véhicules vendus et des milliers de clients satisfaits qui nous font confiance.</p>
                    <div class="story-stats">
                        <div class="stat-item"><span class="stat-number">15</span><span>Années</span></div>
                        <div class="stat-item"><span class="stat-number">1000+</span><span>Véhicules vendus</span></div>
                        <div class="stat-item"><span class="stat-number">98%</span><span>Satisfaction</span></div>
                        <div class="stat-item"><span class="stat-number">50+</span><span>Véhicules en stock</span></div>
                    </div>
                </div>
                <div class="story-image">
                    <img src="https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=600" alt="Showroom AutoMarket">
                </div>
            </div>
        </div>
    </section>

    <section class="our-values">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Nos valeurs</span>
                <h2>Ce qui nous anime</h2>
            </div>
            <div class="values-grid">
                <div class="value-card"><i class="fas fa-heart"></i><h3>Passion</h3><p>La passion de l'automobile est au cœur de notre métier.</p></div>
                <div class="value-card"><i class="fas fa-handshake"></i><h3>Confiance</h3><p>La transparence et l'honnêteté sont nos piliers.</p></div>
                <div class="value-card"><i class="fas fa-shield-alt"></i><h3>Excellence</h3><p>Une qualité irréprochable, des véhicules rigoureusement contrôlés.</p></div>
                <div class="value-card"><i class="fas fa-users"></i><h3>Proximité</h3><p>Un accompagnement personnalisé, à l'écoute de vos besoins.</p></div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>AutoMarket</h3>
                    <p>Votre spécialiste de la vente de véhicules d'occasion depuis 2010.</p>
                </div>
                <div class="footer-section">
                    <h3>Horaires</h3>
                    <ul>
                        <li>Lundi - Vendredi: 9h - 19h</li>
                        <li>Samedi: 10h - 18h</li>
                        <li>Dimanche: Fermé</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Liens rapides</h3>
                    <ul>
                        <li><a href="vehicules.php">Nos véhicules</a></li>
                        <li><a href="location.php">Location</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Suivez-nous</h3>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2024 AutoMarket - Tous droits réservés</p>
            </div>
        </div>
    </footer>
</body>
</html>