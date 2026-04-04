<?php
require_once 'check_session.php';
require_once 'config.php';

$est_connecte = isLoggedIn();

// Nouveau : Récupérer l'ID du client (NULL si pas connecté)
$current_customer_id = getCustomerId();

// Nouveau : Appel de la fonction de comptage d'articles pour alimenter le badge
// ($lien_base provient de config.php déjà importé)
$cart_count = getCartItemCount($lien_base, $current_customer_id);

if ($est_connecte) {
    $lien_action = "add_to_cart.php";
    $lien_user = "profil.php"; // <--- Redirige vers la page de modification
} else {
    $lien_action = "login.php";
    $lien_user = "inscription.php";
}

$prices = [];
$res = mysqli_query($lien_base, "SELECT product_id, list_price FROM products");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $prices[$row['product_id']] = number_format($row['list_price'], 2, ',', ' ') . "€";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
   
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>S&D - Sami & Dalil - Nike</title>
</head>
<body >
    <!-- Theme Toggle -->
    <button id="theme-toggle" class="theme-toggle" aria-label="Changer le thème"></button>
    <!-- ################## -->
    <!-- Navigation -->
    <!-- ################## -->

    <header>
        <a href="./index.php" class="logo">
            <img src="./images/logo_sd.png" alt="E-SHOP Logo">
        </a>

        <ul class="navigation">
            <li>
                <a href="./index.php">Home</a>
            </li>
            <li>
                <a href="./nike.php">Nike</a>
            </li>
            <li>
                <a href="./adidas.php">Adidas</a>
            </li>
            <li>
                <a href="./asics.php">Asics</a>
            </li>
        </ul>
        
        <div class="navigation-icones">
                <form method='POST' action='vue_cherche_par_nom.php' name='annuaire'
                    enctype='application/x-www-form-urlencoded'>
                    <tr>
                        <td>Modele cherché : </td>
                        <td><input type='text' name='brand_name' size='20'></td>
                    </tr>
                </form>
                <i id="search-icon"></i>
                <a href="./panier.php">
                    <i id="cart-icon" class='bx bx-cart-alt'></i>
                    <!-- Nouveau : Affichage dynamique du badge panier avec le nombre actualisé -->
                    <span class="cart-badge"><?php echo $cart_count; ?></span>
                </a>
                <a href="<?php echo $lien_user; ?>"><i id="user-icon" class='bx bxs-user'></i></a>

                <?php if ($est_connecte): ?>
                    <a href="logout.php" title="Déconnexion"><i class='bx bx-log-out'></i></a>
                <?php endif; ?>
                <i id="burger-menu" class='bx bx-menu-alt-left'></i>
            </div>

    </header>

 <!-- ################## -->
    <!-- Home -->
    <!-- ################## -->

    <section class="home" id="home">
        <div class="home-content">
            <span></span>
            <h1>S&D</h1>
            <a href="./histoire.php" class="btn">En savoir plus</a>
        </div>
        <div class="home-img">
            <img src="./images/nike/nike.png" alt="" class="">
        </div>
    </section>
    
    <section class="shop" id="shop">
        <div class="section-heading">
        </div>
        <div class="shop-content container">
                <div class="box">
                    <a href="./produit/nike_airforce.php">
                        <img src="./images/nike/air-force.png.webp" alt="">
                    </a> 
                    <h3>Air force</h3>
                    <span><?php echo $prices[1] ?? 'NC'; ?></span>
                    <a href="<?php echo $lien_action; ?>?product_id=1" class="btn">Ajouter panier</a>
                </div>
                 <div class="box">
                    <a href="./produit/nike_dunk.php">
                        <img src="./images/nike/dunk.png.webp" alt="">
                    </a> 
                    <h3>Dunk</h3>
                    <span><?php echo $prices[2] ?? 'NC'; ?></span>
                    <a href="<?php echo $lien_action; ?>?product_id=2" class="btn">Ajouter panier</a>
                </div>
                <div class="box">
                    <a href="./produit/nike_airjordan.php">
                        <img src="./images/nike/air-jordan.png.webp" alt="">
                    </a> 
                    <h3>Air jordan</h3>
                    <span><?php echo $prices[8] ?? 'NC'; ?></span>
                    <a href="<?php echo $lien_action; ?>?product_id=8" class="btn">Ajouter panier</a>
                </div>
                <div class="box">
                    <a href="./produit/nike_airmax.php">
                        <img src="./images/nike/air-max.png.webp" alt="">
                    </a>
                    <h3>Air max</h3>
                    <span><?php echo $prices[7] ?? 'NC'; ?></span>
                    <a href="<?php echo $lien_action; ?>?product_id=7" class="btn">Ajouter panier</a>
                </div>
                <div class="box">
                    <a href="./produit/nike_tn.php">
                        <img src="./images/nike/tn.png.webp" alt="">
                    </a>
                    <h3>TN</h3>
                    <span><?php echo $prices[6] ?? 'NC'; ?></span>
                    <a href="<?php echo $lien_action; ?>?product_id=6" class="btn">Ajouter panier</a>
                </div>
        </div>
    </section>
    
    <!-- ################## -->
    <!-- Footer-->
    <!-- ################## -->


    <footer>

        <div class="footer-box">
            <h2>ESHOP</h2>
            
        </div>

   
        <div class="footer-box">
            <h2>Informations</h2>
            <li>
                <a href="#">Conditions de vente</a>
            </li>
            <li>
                <a href="#">Mentions légales</a>
            </li>
            <li>
                <a href="#">Support</a>
            </li>
            <li>
                <a href="contact.php">Nous contacter</a>
            </li>
        </div>

        <div class="footer-box">
            <h2>Nos locaux</h2>
            <li>
                <a href="#">France</a>
            </li>
            <li>
                <a href="#">Algerie</a>
            </li>
            <li>
                <a href="#">Espagne</a>
            </li>
            <li>
                <a href="#">Italie</a>
            </li>
            <li>
                <a href="#">Canada</a>
            </li>
        </div>

        <div class="footer-box">
            <h3>Paiements</h3>
            <div class="payement">
                <img src="./images/pay-01.png" alt="Image mastercard">
                <img src="./images/pay-02.png" alt="Image paypal">
                <img src="./images/pay-03.png" alt="Image visa">
                <img src="./images/pay-04.png" alt="Image american expresse">
            </div>
        </div>

    </footer>

    <div class="copy">
        <p>&copy; S&D - Tous droits réservés - 2025</p>
    </div>
  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

 
    <script src="theme-toggle.js"></script>
    <script src="app.js"></script>
</body >
</html>
