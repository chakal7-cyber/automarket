<?php
require_once 'config/database.php';
startSession();

$database = new Database();
$db = $database->getConnection();
$stmt = $db->query("SELECT DISTINCT brand FROM vehicles ORDER BY brand");
$brands = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos véhicules - AutoMarket</title>
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
                    <li><a href="vehicules.php" class="active">Véhicules</a></li>
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

    <div class="page-header">
        <div class="container">
            <h1>Nos véhicules d'occasion</h1>
            <p>Découvrez notre sélection de voitures de qualité</p>
        </div>
    </div>

    <section class="filters-section">
        <div class="container">
            <div class="filters-card">
                <h3><i class="fas fa-filter"></i> Filtres</h3>
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="filter-brand">Marque</label>
                        <select id="filter-brand" class="filter-select">
                            <option value="">Toutes les marques</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo htmlspecialchars($brand); ?>"><?php echo htmlspecialchars($brand); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-price">Prix max</label>
                        <select id="filter-price" class="filter-select">
                            <option value="">Tous les prix</option>
                            <option value="0-15000">Moins de 15 000 €</option>
                            <option value="15000-25000">15 000 - 25 000 €</option>
                            <option value="25000-35000">25 000 - 35 000 €</option>
                            <option value="35000-50000">35 000 - 50 000 €</option>
                            <option value="50000-999999">Plus de 50 000 €</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-fuel">Carburant</label>
                        <select id="filter-fuel" class="filter-select">
                            <option value="">Tous</option>
                            <option value="Essence">Essence</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Hybride">Hybride</option>
                            <option value="Électrique">Électrique</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-transmission">Transmission</label>
                        <select id="filter-transmission" class="filter-select">
                            <option value="">Toutes</option>
                            <option value="Manuelle">Manuelle</option>
                            <option value="Automatique">Automatique</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-sort">Trier par</label>
                        <select id="filter-sort" class="filter-select">
                            <option value="price_asc">Prix croissant</option>
                            <option value="price_desc">Prix décroissant</option>
                            <option value="year_desc">Année récente</option>
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <button id="search-btn" class="btn-search"><i class="fas fa-search"></i> Rechercher</button>
                    <button id="reset-btn" class="btn-reset"><i class="fas fa-undo"></i> Réinitialiser</button>
                </div>
            </div>
        </div>
    </section>

    <section class="cars-section">
        <div class="container">
            <div class="section-header">
                <h2>Notre catalogue</h2>
                <p id="results-count" class="results-count">Chargement...</p>
            </div>
            <div id="cars-grid" class="cars-grid"></div>
        </div>
    </section>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 id="modal-title">Demande d'information</h2>
            <form id="modal-form">
                <input type="hidden" id="modal-car-id">
                <div class="form-group">
                    <label for="modal-name">Nom complet *</label>
                    <input type="text" id="modal-name" required>
                </div>
                <div class="form-group">
                    <label for="modal-email">Email *</label>
                    <input type="email" id="modal-email" required>
                </div>
                <div class="form-group">
                    <label for="modal-phone">Téléphone</label>
                    <input type="tel" id="modal-phone">
                </div>
                <div class="form-group">
                    <label for="modal-message">Message</label>
                    <textarea id="modal-message" rows="3"></textarea>
                </div>
                <button type="submit" class="btn-primary">Envoyer</button>
            </form>
        </div>
    </div>

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
        function loadVehicles() {
            const brand = document.getElementById('filter-brand').value;
            const price = document.getElementById('filter-price').value;
            const fuel = document.getElementById('filter-fuel').value;
            const transmission = document.getElementById('filter-transmission').value;
            const sort = document.getElementById('filter-sort').value;
            
            let url = 'api/vehicles.php?';
            if (brand) url += `brand=${encodeURIComponent(brand)}&`;
            if (fuel) url += `fuel=${encodeURIComponent(fuel)}&`;
            if (transmission) url += `transmission=${encodeURIComponent(transmission)}&`;
            if (sort) url += `sort=${sort}&`;
            if (price) {
                const [min, max] = price.split('-');
                url += `min_price=${min}&max_price=${max}`;
            }
            
            fetch(url)
                .then(response => response.json())
                .then(vehicles => {
                    const container = document.getElementById('cars-grid');
                    const resultsCount = document.getElementById('results-count');
                    
                    if (vehicles.length === 0) {
                        container.innerHTML = `<div class="no-results"><i class="fas fa-car"></i><p>Aucun véhicule trouvé.</p></div>`;
                        resultsCount.textContent = '0 véhicule trouvé';
                    } else {
                        container.innerHTML = vehicles.map(car => `
                            <div class="car-card">
                                <div class="car-image">
                                    <img src="${car.image_url}" alt="${car.brand} ${car.model}" loading="lazy">
                                    ${car.featured ? '<span class="car-badge">Coup de cœur</span>' : ''}
                                </div>
                                <div class="car-info">
                                    <h3 class="car-title">${car.brand} ${car.model}</h3>
                                    <div class="car-price">${car.price.toLocaleString()} €</div>
                                    <div class="car-details">
                                        <span><i class="fas fa-calendar"></i> ${car.year}</span>
                                        <span><i class="fas fa-road"></i> ${car.mileage.toLocaleString()} km</span>
                                        <span><i class="fas fa-gas-pump"></i> ${car.fuel}</span>
                                    </div>
                                    <button class="btn-contact" onclick="openContactModal(${car.id})">Demander un devis</button>
                                </div>
                            </div>
                        `).join('');
                        resultsCount.textContent = `${vehicles.length} véhicule${vehicles.length > 1 ? 's' : ''} trouvé${vehicles.length > 1 ? 's' : ''}`;
                    }
                });
        }

        function openContactModal(carId) {
            fetch(`api/vehicles.php?id=${carId}`)
                .then(response => response.json())
                .then(car => {
                    document.getElementById('modal-title').textContent = `${car.brand} ${car.model}`;
                    document.getElementById('modal-car-id').value = carId;
                    document.getElementById('modal').style.display = 'flex';
                });
        }

        document.getElementById('search-btn').addEventListener('click', loadVehicles);
        document.getElementById('reset-btn').addEventListener('click', () => {
            document.getElementById('filter-brand').value = '';
            document.getElementById('filter-price').value = '';
            document.getElementById('filter-fuel').value = '';
            document.getElementById('filter-transmission').value = '';
            document.getElementById('filter-sort').value = 'price_asc';
            loadVehicles();
        });

        document.getElementById('modal-form').addEventListener('submit', (e) => {
            e.preventDefault();
            fetch('api/contact.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    vehicle_id: document.getElementById('modal-car-id').value,
                    name: document.getElementById('modal-name').value,
                    email: document.getElementById('modal-email').value,
                    phone: document.getElementById('modal-phone').value,
                    message: document.getElementById('modal-message').value
                })
            }).then(() => {
                alert('Demande envoyée !');
                document.getElementById('modal').style.display = 'none';
                document.getElementById('modal-form').reset();
            });
        });

        document.querySelector('.close-modal').onclick = () => document.getElementById('modal').style.display = 'none';
        window.onclick = (e) => { if (e.target === document.getElementById('modal')) document.getElementById('modal').style.display = 'none'; };
        
        loadVehicles();
    </script>
    <script src="js/main.js"></script>
</body>
</html>