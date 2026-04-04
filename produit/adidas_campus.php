<?php
require_once './../check_session.php';
require_once './../config.php';
$est_connecte = isLoggedIn();

if ($est_connecte) {
    $lien_action = "./../add_to_cart.php";
    $lien_user = "./../profil.php"; // <--- Redirige vers la page de modification
} else {
    $lien_action = "./../login.php";
    $lien_user = "./../inscription.php";
}

$id_produit = 9;
$res = mysqli_query($lien_base, "SELECT list_price FROM products WHERE product_id = $id_produit");
$row = mysqli_fetch_assoc($res);
$prix = ($row) ? number_format($row['list_price'], 2, ',', ' ') . "€" : "NC";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>S&D - Sami & Dalil</title>
</head>
<body>`n    <!-- Theme Toggle -->`n    <button id="theme-toggle" class="theme-toggle" aria-label="Changer le th�me"></button>

    <!-- ################## -->
    <!-- Navigation -->
    <!-- ################## -->

    <header>
        <!-- Logo -->
        <a href="../index.php" class="logo">
            <img src="../images/logo_sd.png" alt="Logo Sami & Dalil">
        </a>

        <!-- Navigation Menu -->
        <ul class="navigation">
            <li><a href="../index.php">Home</a></li>
            <li><a href="../nike.php">Nike</a></li>
            <li><a href="../adidas.php">Adidas</a></li>
            <li><a href="../asics.php">Asics</a></li>
        </ul>


    </header>

    <!-- ################## -->
    <!-- Product Details -->
    <!-- ################## -->

        <section class="product-detail">
            <div class="product-images">
                <img src="../images/adidas/campus.webp" width=300 height=30% alt="T-Shirt 12A">
            </div>

            <div class="product-info">
                <h1>Adidas Campus</h1>
                <p class="price"><span><?php echo $prix; ?></span></p>
                <p class="description">
                    Cette Air force one de la collection "12A" offre confort et style. Fabriqué à partir de coton 100% de haute qualité, il est idéal pour un usage quotidien.
                </p>

                <div class="product-actions">
                    <div class="quantity">
                        <button class="quantity-btn">-</button>
                        <input type="number" value="1" min="1" max="10">
                        <button class="quantity-btn">+</button>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=9" class="btn">Ajouter panier</a>
                        </div>
            </div>
        </section>
       

    

    <script src="../theme-toggle.js"></script>`n</body>
</html>

