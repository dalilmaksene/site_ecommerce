<?php
require_once 'check_session.php';
require_once 'config.php';

$est_connecte = isLoggedIn();
$current_customer_id = getCustomerId();
$cart_count = getCartItemCount($lien_base, $current_customer_id);

if ($est_connecte) {
    $lien_user = "profil.php";
} else {
    $lien_user = "inscription.php";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>Documentation Technique - S&D</title>
</head>
<body>

    <!-- Header -->
    <header>
        <a href="./index.php" class="logo">
            <img src="./images/logo_sd.png" alt="E-SHOP Logo">
        </a>

        <ul class="navigation">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./nike.php">Nike</a></li>
            <li><a href="./adidas.php">Adidas</a></li>
            <li><a href="./asics.php">Asics</a></li>
        </ul>

        <div class="navigation-icones">
            <a href="./panier.php">
                <i id="cart-icon" class='bx bx-cart-alt'></i>
                <span class="cart-badge"><?php echo $cart_count; ?></span>
            </a>
            <a href="<?php echo $lien_user; ?>"><i id="user-icon" class='bx bxs-user'></i></a>
            <?php if ($est_connecte): ?>
                <a href="logout.php" title="Déconnexion"><i class='bx bx-log-out'></i></a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Documentation Content -->
    <section class="doc-section">
        <div class="doc-header">
            <h1>Architecture Technique</h1>
            <p>Guide complet des fichiers et de la structure du projet e-commerce S&D. Ce document explique le rôle de chaque composant et leurs interactions pour un étudiant en BTS SIO.</p>
        </div>

        <div class="doc-grid">
            <!-- Cœur du Système -->
            <div class="doc-card">
                <i class='bx bx-cog'></i>
                <h3>Cœur & Configuration</h3>
                <ul>
                    <li>
                        <strong>config.php</strong>
                        <span>Établit la connexion MySQL. Définit <code>$lien_base</code> qui est le canal de communication avec la base de données.</span>
                    </li>
                    <li>
                        <strong>check_session.php</strong>
                        <span>Gère les sessions utilisateurs. Contient les fonctions de sécurité (<code>isLoggedIn</code>) et utilitaires (<code>getCartItemCount</code>).</span>
                    </li>
                    <li>
                        <strong>fonctions.php</strong>
                        <span>Bibliothèque de fonctions métier, notamment pour la recherche de produits par nom ou marque.</span>
                    </li>
                </ul>
            </div>

            <!-- Boutique & Catalogue -->
            <div class="doc-card">
                <i class='bx bx-store'></i>
                <h3>Boutique & Catalogue</h3>
                <ul>
                    <li>
                        <strong>index.php</strong>
                        <span>Point d'entrée principal. Affiche dynamiquement les produits phares de toutes les marques.</span>
                    </li>
                    <li>
                        <strong>nike.php / adidas.php...</strong>
                        <span>Pages de catégories filtrées. Elles utilisent des requêtes SQL ciblées pour n'afficher qu'une marque précise.</span>
                    </li>
                    <li>
                        <strong>vue_recherche.php</strong>
                        <span>Interface dédiée à l'affichage des résultats après une recherche via le moteur interne.</span>
                    </li>
                </ul>
            </div>

            <!-- Panier & Logique -->
            <div class="doc-card">
                <i class='bx bx-shopping-bag'></i>
                <h3>Gestion du Panier</h3>
                <ul>
                    <li>
                        <strong>add_to_cart.php</strong>
                        <span>Logique serveur (invisible) : vérifie si un panier existe, crée une commande si besoin, et ajoute l'article.</span>
                    </li>
                    <li>
                        <strong>panier.php</strong>
                        <span>Visualisation du panier. Calcule les taxes (TVA 20%), les remises et le total final TTC.</span>
                    </li>
                    <li>
                        <strong>remove_from_cart.php</strong>
                        <span>Supprime proprement un article d'une commande sans affecter le reste du panier.</span>
                    </li>
                </ul>
            </div>

            <!-- Authentification -->
            <div class="doc-card">
                <i class='bx bx-user-circle'></i>
                <h3>Utilisateurs</h3>
                <ul>
                    <li>
                        <strong>inscription.php</strong>
                        <span>Inscription sécurisée avec insertion dans la table <code>customers</code>.</span>
                    </li>
                    <li>
                        <strong>login.php / logout.php</strong>
                        <span>Gestion du cycle de vie de la connexion. Utilise <code>$_SESSION</code> pour mémoriser l'utilisateur.</span>
                    </li>
                    <li>
                        <strong>profil.php</strong>
                        <span>Mise à jour des données du compte avec gestion sécurisée du mot de passe (hachage).</span>
                    </li>
                </ul>
            </div>

            <!-- Design & Frontend -->
            <div class="doc-card">
                <i class='bx bx-palette'></i>
                <h3>Design & Frontend</h3>
                <ul>
                    <li>
                        <strong>style.css</strong>
                        <span>Design system centralisé : Dark mode, Glassmorphism, Responsive Design (Media Queries).</span>
                    </li>
                    <li>
                        <strong>app.js</strong>
                        <span>Interactivité et animations (ScrollReveal, Swiper). Gère les effets visuels dynamiques.</span>
                    </li>
                    <li>
                        <strong>theme-toggle.js</strong>
                        <span>Script léger permettant de basculer et de mémoriser (LocalStore) le thème clair/sombre.</span>
                    </li>
                </ul>
            </div>

            <!-- Commandes & Paiement -->
            <div class="doc-card">
                <i class='bx bx-credit-card'></i>
                <h3>Tunnel d'Achat</h3>
                <ul>
                    <li>
                        <strong>paiement.php</strong>
                        <span>Simule la validation bancaire et finalize la transaction dans la base de données.</span>
                    </li>
                    <li>
                        <strong>confirmation.php</strong>
                        <span>Page de succès affichant le récapitulatif final après validation du paiement.</span>
                    </li>
                </ul>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="index.php" class="btn">Retour à la boutique</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-box">
            <h2>S&D</h2>
        </div>
        <div class="footer-box">
            <h2>Aide & Informations</h2>
            <li><a href="contact.php">Nous contacter</a></li>
            <li><a href="histoire.php">Notre Histoire</a></li>
            <li><a href="documentation.php" class="doc-footer-link">Documentation technique</a></li>
        </div>
        <div class="copy">
            <p>&copy; S&D - Réalisé par Sami & Dalil - 2025</p>
        </div>
    </footer>

    <script src="theme-toggle.js"></script>
    <script src="app.js"></script>
</body>
</html>
