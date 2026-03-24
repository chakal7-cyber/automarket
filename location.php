<?php
require_once 'config/database.php';
startSession();

$database = new Database();
$db = $database->getConnection();

// Récupérer les marques pour le filtre
$stmt = $db->query("SELECT DISTINCT brand FROM rental_vehicles ORDER BY brand");
$brands = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location de voitures - AutoMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Styles pour une modale compacte */
        .modal-content.modal-large {
            max-width: 450px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            padding: 1rem;
        }
        
        .form-group {
            margin-bottom: 0.6rem;
        }
        
        .form-group label {
            font-size: 0.8rem;
            margin-bottom: 0.2rem;
            display: block;
        }
        
        .form-group input,
        .form-group textarea {
            padding: 0.5rem;
            font-size: 0.85rem;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }
        
        .price-summary {
            background: #f8f9fa;
            padding: 0.5rem;
            border-radius: 5px;
            margin: 0.8rem 0;
            font-size: 0.85rem;
        }
        
        .price-summary p {
            margin: 0.2rem 0;
        }
        
        .options-group {
            margin: 0.5rem 0;
            padding: 0.3rem 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }
        
        .options-group label {
            display: block;
            font-size: 0.8rem;
            margin: 0.2rem 0;
            cursor: pointer;
        }
        
        .total-price {
            font-size: 1rem;
            font-weight: bold;
            text-align: right;
            margin-top: 0.5rem;
            padding-top: 0.3rem;
            border-top: 1px solid #ddd;
        }
        
        .btn-primary.btn-block {
            padding: 8px;
            font-size: 14px;
            margin-top: 10px;
            width: 100%;
        }
        
        .close-modal {
            top: 0.3rem;
            right: 0.5rem;
            font-size: 1.2rem;
        }
        
        #rental-modal h2 {
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
        }
        
        textarea {
            min-height: 50px;
            resize: vertical;
        }
        
        .car-info-summary {
            background: #e8f0fe;
            padding: 0.5rem;
            border-radius: 5px;
            margin-bottom: 0.8rem;
            text-align: center;
            font-weight: bold;
            font-size: 0.9rem;
        }
    </style>
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
                    <li><a href="location.php" class="active">Location</a></li>
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
            <h1>Location de véhicules</h1>
            <p>Louez la voiture de vos rêves pour quelques jours ou quelques semaines</p>
        </div>
    </div>

    <section class="filters-section">
        <div class="container">
            <div class="filters-card">
                <h3><i class="fas fa-filter"></i> Filtres de location</h3>
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="rental-filter-brand">Marque</label>
                        <select id="rental-filter-brand" class="filter-select">
                            <option value="">Toutes les marques</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo htmlspecialchars($brand); ?>"><?php echo htmlspecialchars($brand); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="rental-filter-price">Prix / jour</label>
                        <select id="rental-filter-price" class="filter-select">
                            <option value="">Tous les prix</option>
                            <option value="0-50">Moins de 50 €</option>
                            <option value="50-80">50 - 80 €</option>
                            <option value="80-120">80 - 120 €</option>
                            <option value="120-999">Plus de 120 €</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="rental-filter-fuel">Carburant</label>
                        <select id="rental-filter-fuel" class="filter-select">
                            <option value="">Tous</option>
                            <option value="Essence">Essence</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Hybride">Hybride</option>
                            <option value="Électrique">Électrique</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="rental-filter-seats">Places</label>
                        <select id="rental-filter-seats" class="filter-select">
                            <option value="">Toutes</option>
                            <option value="4">4 places</option>
                            <option value="5">5 places</option>
                            <option value="7">7 places</option>
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <button id="rental-search-btn" class="btn-search"><i class="fas fa-search"></i> Rechercher</button>
                    <button id="rental-reset-btn" class="btn-reset"><i class="fas fa-undo"></i> Réinitialiser</button>
                </div>
            </div>
        </div>
    </section>

    <section class="cars-section">
        <div class="container">
            <div class="section-header">
                <h2>Nos véhicules en location</h2>
                <p id="rental-results-count" class="results-count">Chargement...</p>
            </div>
            <div id="rental-cars-grid" class="cars-grid"></div>
        </div>
    </section>

    <div id="rental-modal" class="modal">
        <div class="modal-content modal-large">
            <span class="close-modal">&times;</span>
            <h2 id="rental-modal-title">Réserver un véhicule</h2>
            <form id="rental-modal-form">
                <input type="hidden" id="rental-vehicle-id">
                
                <div class="car-info-summary" id="car-info-display">
                    Chargement...
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="rental-start-date">📅 Date de début *</label>
                        <input type="date" id="rental-start-date" required>
                    </div>
                    <div class="form-group">
                        <label for="rental-end-date">📅 Date de fin *</label>
                        <input type="date" id="rental-end-date" required>
                    </div>
                </div>
                
                <div class="price-summary">
                    <p>💰 Prix par jour: <strong id="price-per-day">0</strong> €</p>
                    <p>📆 Nombre de jours: <strong id="days-count">0</strong> jours</p>
                    <p>💶 Sous-total: <strong id="subtotal">0</strong> €</p>
                    
                    <div class="options-group">
                        <label><input type="checkbox" id="option-gps"> 🗺️ GPS (+8€/jour)</label>
                        <label><input type="checkbox" id="option-baby"> 👶 Siège bébé (+5€/jour)</label>
                        <label><input type="checkbox" id="option-insurance"> 🛡️ Assurance premium (+15€/jour)</label>
                    </div>
                    
                    <div class="total-price">
                        🎯 TOTAL: <span id="total-price">0</span> €
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="rental-name">👤 Nom complet *</label>
                        <input type="text" id="rental-name" required placeholder="Votre nom et prénom">
                    </div>
                    <div class="form-group">
                        <label for="rental-email">📧 Email *</label>
                        <input type="email" id="rental-email" required placeholder="votre@email.com">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="rental-phone">📞 Téléphone *</label>
                    <input type="tel" id="rental-phone" required placeholder="06 12 34 56 78">
                </div>
                
                <div class="form-group">
                    <label for="rental-message">💬 Message (optionnel)</label>
                    <textarea id="rental-message" rows="2" placeholder="Votre message..."></textarea>
                </div>
                
                <button type="submit" class="btn-primary btn-block">
                    ✅ Confirmer la réservation
                </button>
            </form>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>AutoMarket</h3>
                    <p>Votre spécialiste de la vente et location de véhicules depuis 2010.</p>
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

    <script>
        let currentRentalVehicleId = null;
        let currentRentalPricePerDay = 0;
        let currentVehicleInfo = null;

        function loadRentalVehicles() {
            const brand = document.getElementById('rental-filter-brand').value;
            const price = document.getElementById('rental-filter-price').value;
            const fuel = document.getElementById('rental-filter-fuel').value;
            const seats = document.getElementById('rental-filter-seats').value;
            
            let url = 'api/rentals.php?';
            if (brand) url += `brand=${encodeURIComponent(brand)}&`;
            if (fuel) url += `fuel=${encodeURIComponent(fuel)}&`;
            if (seats) url += `seats=${seats}&`;
            if (price) {
                const [min, max] = price.split('-');
                url += `min_price=${min}&max_price=${max}`;
            }
            
            fetch(url)
                .then(response => response.json())
                .then(vehicles => {
                    const container = document.getElementById('rental-cars-grid');
                    const resultsCount = document.getElementById('rental-results-count');
                    
                    if (vehicles.length === 0) {
                        container.innerHTML = `<div class="no-results"><i class="fas fa-car"></i><p>Aucun véhicule trouvé.</p></div>`;
                        resultsCount.textContent = '0 véhicule trouvé';
                    } else {
                        container.innerHTML = vehicles.map(car => `
                            <div class="car-card">
                                <div class="car-image">
                                    <img src="${car.image_url}" alt="${car.brand} ${car.model}" loading="lazy">
                                    ${car.featured ? '<span class="car-badge">Populaire</span>' : ''}
                                </div>
                                <div class="car-info">
                                    <h3 class="car-title">${car.brand} ${car.model}</h3>
                                    <div class="rental-price">${car.price_per_day} € <small>/ jour</small></div>
                                    <div class="rental-features">
                                        <span><i class="fas fa-calendar"></i> ${car.year}</span>
                                        <span><i class="fas fa-gas-pump"></i> ${car.fuel}</span>
                                        <span><i class="fas fa-cog"></i> ${car.transmission}</span>
                                    </div>
                                    <button class="btn-rent" onclick="openRentalModal(${car.id}, ${car.price_per_day}, '${car.brand} ${car.model}')">
                                        <i class="fas fa-calendar-check"></i> Réserver
                                    </button>
                                </div>
                            </div>
                        `).join('');
                        resultsCount.textContent = `${vehicles.length} véhicule${vehicles.length > 1 ? 's' : ''} disponible${vehicles.length > 1 ? 's' : ''}`;
                    }
                });
        }

        function openRentalModal(vehicleId, pricePerDay, carName) {
            currentRentalVehicleId = vehicleId;
            currentRentalPricePerDay = pricePerDay;
            document.getElementById('price-per-day').textContent = pricePerDay;
            document.getElementById('rental-vehicle-id').value = vehicleId;
            document.getElementById('car-info-display').innerHTML = `🚗 ${carName} - ${pricePerDay}€/jour`;
            
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('rental-start-date').min = today;
            document.getElementById('rental-end-date').min = today;
            document.getElementById('rental-start-date').value = today;
            document.getElementById('rental-end-date').value = today;
            calculateRentalPrice();
            document.getElementById('rental-modal').style.display = 'flex';
        }

        function calculateRentalPrice() {
            const startDate = document.getElementById('rental-start-date').value;
            const endDate = document.getElementById('rental-end-date').value;
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                
                if (days >= 0) {
                    let total = days * currentRentalPricePerDay;
                    const gps = document.getElementById('option-gps').checked ? 8 : 0;
                    const baby = document.getElementById('option-baby').checked ? 5 : 0;
                    const insurance = document.getElementById('option-insurance').checked ? 15 : 0;
                    const optionsTotal = days * (gps + baby + insurance);
                    total += optionsTotal;
                    
                    document.getElementById('days-count').textContent = days;
                    document.getElementById('subtotal').textContent = (days * currentRentalPricePerDay).toFixed(2);
                    document.getElementById('total-price').textContent = total.toFixed(2);
                }
            }
        }
        
        document.getElementById('rental-search-btn').addEventListener('click', loadRentalVehicles);
        document.getElementById('rental-reset-btn').addEventListener('click', () => {
            document.getElementById('rental-filter-brand').value = '';
            document.getElementById('rental-filter-price').value = '';
            document.getElementById('rental-filter-fuel').value = '';
            document.getElementById('rental-filter-seats').value = '';
            loadRentalVehicles();
        });
        
        document.getElementById('rental-start-date').addEventListener('change', calculateRentalPrice);
        document.getElementById('rental-end-date').addEventListener('change', calculateRentalPrice);
        document.getElementById('option-gps').addEventListener('change', calculateRentalPrice);
        document.getElementById('option-baby').addEventListener('change', calculateRentalPrice);
        document.getElementById('option-insurance').addEventListener('change', calculateRentalPrice);
        
        document.getElementById('rental-modal-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = {
                vehicle_id: currentRentalVehicleId,
                start_date: document.getElementById('rental-start-date').value,
                end_date: document.getElementById('rental-end-date').value,
                name: document.getElementById('rental-name').value,
                email: document.getElementById('rental-email').value,
                phone: document.getElementById('rental-phone').value,
                message: document.getElementById('rental-message').value,
                gps: document.getElementById('option-gps').checked ? 1 : 0,
                baby_seat: document.getElementById('option-baby').checked ? 1 : 0,
                insurance: document.getElementById('option-insurance').checked ? 1 : 0,
                total_price: document.getElementById('total-price').textContent
            };
            
            fetch('api/rentals.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`✅ Réservation confirmée !\n\nVéhicule: ${document.getElementById('car-info-display').innerHTML}\nTotal: ${formData.total_price} €\n\nUn email de confirmation vous a été envoyé.`);
                    document.getElementById('rental-modal').style.display = 'none';
                    document.getElementById('rental-modal-form').reset();
                } else {
                    alert('❌ Erreur lors de la réservation\nVeuillez réessayer.');
                }
            })
            .catch(error => {
                alert('❌ Erreur de connexion\nVeuillez vérifier votre connexion.');
            });
        });
        
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => document.getElementById('rental-modal').style.display = 'none');
        });
        
        window.addEventListener('click', (e) => {
            if (e.target === document.getElementById('rental-modal')) {
                document.getElementById('rental-modal').style.display = 'none';
            }
        });
        
        loadRentalVehicles();
    </script>
</body>
</html>