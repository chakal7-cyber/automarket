<?php
require_once 'config/database.php';
startSession();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos services - AutoMarket</title>
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
                    <li><a href="services.php" class="active">Services</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="a-propos.php">À propos</a></li>
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
            <h1>Nos services</h1>
            <p>Des prestations sur mesure pour votre véhicule</p>
        </div>
    </div>

    <section class="services-showcase">
        <div class="container">
            <div class="service-showcase-item">
                <div class="service-icon-large"><i class="fas fa-chart-line"></i></div>
                <div class="service-text">
                    <h2>Financement sur mesure</h2>
                    <p>Nous vous accompagnons dans le financement de votre véhicule avec des solutions adaptées à votre budget.</p>
                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Crédit auto jusqu'à 84 mois</li>
                        <li><i class="fas fa-check"></i> Taux à partir de 3.9%</li>
                        <li><i class="fas fa-check"></i> Simulation gratuite et sans engagement</li>
                    </ul>
                    <a href="contact.php" class="btn-primary">Demander une simulation</a>
                </div>
            </div>

            <div class="service-showcase-item reverse">
                <div class="service-icon-large"><i class="fas fa-exchange-alt"></i></div>
                <div class="service-text">
                    <h2>Reprise de votre ancien véhicule</h2>
                    <p>Confiez-nous votre ancien véhicule et bénéficiez d'une estimation juste et rapide.</p>
                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Estimation gratuite en 24h</li>
                        <li><i class="fas fa-check"></i> Reprise immédiate</li>
                        <li><i class="fas fa-check"></i> Déduction du montant sur votre achat</li>
                    </ul>
                    <a href="contact.php" class="btn-primary">Faire estimer mon véhicule</a>
                </div>
            </div>

            <div class="service-showcase-item">
                <div class="service-icon-large"><i class="fas fa-tools"></i></div>
                <div class="service-text">
                    <h2>Entretien et garantie</h2>
                    <p>Bénéficiez d'un suivi complet de votre véhicule avec notre service après-vente.</p>
                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Garantie 12 mois minimum</li>
                        <li><i class="fas fa-check"></i> Extension de garantie jusqu'à 36 mois</li>
                        <li><i class="fas fa-check"></i> Assistance 24h/24, 7j/7</li>
                    </ul>
                    <a href="contact.php" class="btn-primary">En savoir plus</a>
                </div>
            </div>

            <div class="service-showcase-item reverse">
                <div class="service-icon-large"><i class="fas fa-truck"></i></div>
                <div class="service-text">
                    <h2>Livraison à domicile</h2>
                    <p>Nous livrons votre véhicule directement chez vous, en toute sécurité.</p>
                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Livraison gratuite dans un rayon de 50km</li>
                        <li><i class="fas fa-check"></i> Transport sécurisé</li>
                        <li><i class="fas fa-check"></i> Livraison possible dans toute la France</li>
                    </ul>
                    <a href="contact.php" class="btn-primary">Organiser ma livraison</a>
                </div>
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