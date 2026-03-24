<?php
require_once 'config/database.php';
startSession();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoMarket - Accueil</title>
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
                    <li><a href="index.php" class="active">Accueil</a></li>
                    <li><a href="vehicules.php">Véhicules</a></li>
                    <li><a href="location.php">Location</a></li>
                    <li><a href="services.php">Services</a></li>
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

    <section class="hero" style="background-image: url('https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=1600'); background-size: cover; background-position: center; min-height: 500px;">
    <div class="hero-content container">
        <h1>Trouvez la voiture de vos rêves</h1>
        <p>Plus de 100 véhicules sélectionnés avec soin et garantis</p>
        <a href="vehicules.php" class="btn-primary">Découvrir nos offres</a>
    </div>
</section>
    <section class="featured-section">
        <div class="container">
            <div class="section-header">
                <h2>Véhicules à la une</h2>
                <p>Découvrez nos meilleures offres du moment</p>
            </div>
            <div id="featured-cars" class="cars-grid"></div>
            <div class="text-center" style="margin-top: 2rem;">
                <a href="vehicules.php" class="btn-primary">Voir tous nos véhicules</a>
            </div>
        </div>
    </section>

    <section class="why-choose-us">
        <div class="container">
            <div class="section-header">
                <h2>Pourquoi choisir AutoMarket ?</h2>
                <p>Notre engagement pour votre satisfaction</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>Véhicules garantis</h3>
                    <p>Tous nos véhicules sont garantis 12 mois minimum</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Prix compétitifs</h3>
                    <p>Les meilleurs prix du marché</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-headset"></i>
                    <h3>Service client</h3>
                    <p>Une équipe à votre écoute 6j/7</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-file-signature"></i>
                    <h3>Papiers en règle</h3>
                    <p>Toutes les formalités administratives prises en charge</p>
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
                        <li><a href="services.php">Nos services</a></li>
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

    <script>
        fetch('api/vehicles.php?featured=1')
            .then(response => response.json())
            .then(vehicles => {
                const container = document.getElementById('featured-cars');
                if (container && vehicles.length) {
                    container.innerHTML = vehicles.slice(0, 4).map(car => `
                        <div class="car-card">
                            <div class="car-image">
                                <img src="${car.image_url}" alt="${car.brand} ${car.model}" loading="lazy">
                                <span class="car-badge">Coup de cœur</span>
                            </div>
                            <div class="car-info">
                                <h3 class="car-title">${car.brand} ${car.model}</h3>
                                <div class="car-price">${car.price.toLocaleString()} €</div>
                                <div class="car-details">
                                    <span><i class="fas fa-calendar"></i> ${car.year}</span>
                                    <span><i class="fas fa-road"></i> ${car.mileage.toLocaleString()} km</span>
                                    <span><i class="fas fa-gas-pump"></i> ${car.fuel}</span>
                                </div>
                                <a href="vehicules.php?id=${car.id}" class="btn-contact">Voir les détails</a>
                            </div>
                        </div>
                    `).join('');
                }
            });
    </script>
    <script src="js/main.js"></script>
</body>
</html>