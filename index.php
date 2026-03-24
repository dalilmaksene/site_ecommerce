<?php
require_once 'check_session.php';
require_once 'config.php';
$est_connecte = isLoggedIn();

if ($est_connecte) {
    $lien_action = "add_to_cart.php";
    $lien_user = "profil.php"; // <--- Redirige vers la page de modification
}
else {
    $lien_action = "login.php";
    $lien_user = "inscription.php";
}

// Récupération des prix
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
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        /* Modal for product description */
        .modal {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.55);
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }
        .modal.visible {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-content {
            background: #fff;
            padding: 1.5rem;
            max-width: 420px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.25);
            position: relative;
        }
        .modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            border: none;
            background: transparent;
            font-size: 1.25rem;
            cursor: pointer;
        }
        .modal-actions {
            margin-top: 1rem;
            text-align: right;
        }
    </style>
    <title>S&D - Sami & Dalil</title>
</head>

<body>

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
                <i id="search-icon" class='bx bx-search'></i>
                <a href="./panier.php">
                    <i id="cart-icon" class='bx bx-cart-alt'></i></a>
                <a href="<?php echo $lien_user; ?>"><i id="user-icon" class='bx bxs-user'></i></a>

                <?php if ($est_connecte): ?>
                    <a href="logout.php" title="Déconnexion"><i class='bx bx-log-out'></i></a>
                <?php
endif; ?>
                <i id="burger-menu" class='bx bx-menu-alt-left'></i>
            </div>

            <div class="search-container">
                <input type="search" placeholder="Que recherchez vous ?">
            </div>


            </div>

        </header>

        <!-- ################## -->
        <!-- Home -->
        <!-- ################## -->

        <section class="home" id="home">
            <div class="home-content">
                <h1>S&D</h1>
                <p class="home-subtitle">Votre destination premium pour les sneakers les plus recherchées. Nike, Adidas, Asics — les meilleures paires, livrées chez vous.</p>
                <a href="./histoire.php" class="btn">En savoir plus</a>
            </div>
            <div class="home-img">
                <img src="./images/page_d_acceuil.jpg" alt="" class="">
            </div>
        </section>

        <!-- ################## -->
        <!-- Nike-->
        <!-- ################## -->
        <section class="nike" id="nike">
            <div class="section-heading">
                <h2>Nike</h2>
            </div>
            <div class="swiper news-cont">
                <div class="swiper-wrapper">
                    <div class="swiper-slide box">
                        <a href="./produit/nike_airforce.php" data-product-id="1" data-title="Air Force" data-description="La Air Force est un classique indémodable, offrant confort et style urbain. Idéale pour une silhouette streetwear.">
                            <img src="./images/nike/air-force.png.webp" alt="">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[1] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=1" class="btn">Ajouter panier</a>
                            <a href="./produit/nike_airforce.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/nike_dunk.php" data-product-id="2" data-title="Dunk" data-description="La Dunk associe une coupe rétro et une grande polyvalence, parfaite pour le skate et la vie urbaine.">
                            <img src="./images/nike/dunk.png.webp" alt="">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[2] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=2" class="btn">Ajouter panier</a>
                            <a href="./produit/nike_dunk.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/nike_tn.php" data-product-id="6" data-title="TN" data-description="La Air Max TN propose un look affirmé et un amorti maximal pour un confort prolongé.">
                            <img src="./images/nike/tn.png.webp" alt="">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[6] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=6" class="btn">Ajouter panier</a>
                            <a href="./produit/nike_tn.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/nike_airmax.php" data-product-id="7" data-title="Air Max" data-description="La Air Max garantit un amorti visible et un style iconique, parfait pour un usage quotidien.">
                            <img src="./images/nike/air-max.png.webp" alt="">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[7] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=7" class="btn">Ajouter panier</a>
                            <a href="./produit/nike_airmax.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/nike_airjordan.php" data-product-id="8" data-title="Air Jordan" data-description="La Air Jordan mêle performance et héritage basket, avec un design qui traverse les générations.">
                            <img src="./images/nike/air-jordan.png.webp" alt="">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[8] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=8" class="btn">Ajouter panier</a>
                            <a href="./produit/nike_airjordan.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ################## -->
        <!--ADIDAS -->
        <!-- ################## ----------------------------------------------------------------------------------------------------------------------------------------------------------->
        <section class="adidas" id="adidas">
            <div class="section-heading">
                <h2>Adidas</h2>
            </div>
            <div class="swiper news-cont">
                <div class="swiper-wrapper">

                    <div class="swiper-slide box">
                        <a href="./produit/adidas_super.php" data-product-id="4" data-title="Adidas Super" data-description="Une silhouette classique Adidas, avec un confort léger et une coupe iconique pour un style rétro.">
                            <img src="./images/adidas/super.webp"
                                alt="Adidas running shoes with a blue and white design">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[4] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=4" class="btn">Ajouter panier</a>
                            <a href="./produit/adidas_super.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/adidas_sl.php" data-product-id="5" data-title="Adidas SL" data-description="Un modèle épuré et léger, parfait pour celles et ceux qui veulent allier confort et discrétion.">
                            <img src="./images/adidas/adidas-sl.webp"
                                alt="Adidas sneakers with a black and gold design">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[5] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=5" class="btn">Ajouter panier</a>
                            <a href="./produit/adidas_sl.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/adidas_campus.php" data-product-id="9" data-title="Adidas Campus" data-description="Une paire iconique des années 70, reconnue pour son cuir souple et son look rétro-cool.">
                            <img src="./images/adidas/campus.webp"
                                alt="Adidas sports shoes with a white and green design">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[9] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=9" class="btn">Ajouter panier</a>
                            <a href="./produit/adidas_campus.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/adidas_gazelle.php" data-product-id="10" data-title="Adidas Gazelle" data-description="Une icône du football des années 60, la Gazelle est légère, élégante et facile à porter au quotidien.">
                            <img src="./images/adidas/gazelle.webp"
                                alt="Adidas casual shoes with a red and black design">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[10] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=10" class="btn">Ajouter panier</a>
                            <a href="./produit/adidas_gazelle.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/adidas_samba.php" data-product-id="11" data-title="Adidas Samba" data-description="La Samba, légende du football, propose un maintien précis et un look intemporel en ville.">
                            <img src="./images/adidas/samba.webp" alt="Adidas trainers with a yellow and black design">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[11] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=11" class="btn">Ajouter panier</a>
                            <a href="./produit/adidas_samba.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/adidas_yeezy.php" data-product-id="3" data-title="Adidas Yeezy" data-description="La Yeezy associe design futuriste et confort maximal pour un style affirmé.">
                            <img src="./images/adidas/yeezy.webp"
                                alt="Adidas performance shoes with a white and blue design">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[3] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=3" class="btn">Ajouter panier</a>
                            <a href="./produit/adidas_yeezy.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ################## -->
        <!-- ASICS-->
        <!-- ################## ----------------------------------------------------------------------------------------------------------------------------------------------------------->

        <section class="asics" id="asics">
            <div class="section-heading">
                <h2>Asics</h2>
            </div>
            <div class="swiper news-cont">
                <div class="swiper-wrapper">
                    <div class="swiper-slide box">
                        <a href="./produit/asics-gel-kayano-14.php" data-product-id="12" data-title="Asics Gel-Kayano 14" data-description="Une chaussure de running haut de gamme, conçue pour le maintien et l’amorti sur longues distances.">
                            <img src="./images/asics/Asics-Gel-Kayano-14.webp" alt="Baskets asics, design noir et doré">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[12] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=12" class="btn"
                                aria-label="Ajouter les baskets asics au panier">Ajouter panier</a>
                            <a href="./produit/asics-gel-kayano-14.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/asics-gt-2160-cecilie.php" data-product-id="13" data-title="Asics GT-2160 Cecilie" data-description="Un modèle technique inspiré du running, avec un amorti précis et un look élégant.">
                            <img src="./images/asics/asics-gt-2160-cecilie.webp"
                                alt="Chaussures de sport asics, design blanc et vert">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[13] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=13" class="btn"
                                aria-label="Ajouter les chaussures de sport asics au panier">Ajouter
                                panier</a>
                            <a href="./produit/asics-gt-2160-cecilie.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/asics_gel-1130.php" data-product-id="14" data-title="Asics Gel-1130" data-description="Une chaussure casual moderne, alliant design rétro et confort quotidien.">
                            <img src="./images/asics/Gel-1130.webp" alt="Chaussures casual asics, design rouge et noir">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[14] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=14" class="btn"
                                aria-label="Ajouter les chaussures casual asics au panier">Ajouter
                                panier</a>
                            <a href="./produit/asics_gel-1130.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/asics-gel-nyc.php" data-product-id="15" data-title="Asics Gel NYC" data-description="Une édition urbaine avec amorti souple et style moderne pour vos promenades en ville.">
                            <img src="./images/asics/gel-nyc.webp"
                                alt="Chaussures de training asics, design jaune et noir">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[15] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=15" class="btn"
                                aria-label="Ajouter les chaussures de training asics au panier">Ajouter panier</a>
                            <a href="./produit/asics-gel-nyc.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>
                    <div class="swiper-slide box">
                        <a href="./produit/asics_gel-terrain-Faded.php" data-product-id="16" data-title="Asics Gel-Terrain Faded" data-description="Chaussure de trail légère, idéale pour les terrains instables avec un bon maintien.">
                            <img src="./images/asics/Gel-Terrain-Faded.webp"
                                alt="Chaussures de performance asics, design blanc et bleu">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px; color: var(--accent-cyan);">
                                <?php echo $prices[16] ?? 'NC'; ?>
                            </div>
                        </a>
                        <div class="content">
                            <a href="<?php echo $lien_action; ?>?product_id=16" class="btn"
                                aria-label="Ajouter les chaussures de performance asics au panier">Ajouter panier</a>
                            <a href="./produit/asics_gel-terrain-Faded.php" class="btn" style="margin-left: 0.5rem;">Voir chaussure</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>





        <!-- ################## -->
        <!-- Reviews -->
        <!-- ################## -->


        <section class="reviews" id="reviews">
            <div class="section-heading">
                <h2>Avis <span>Clients</span></h2>
            </div>

            <div class="reviews-container">
                <div class="review-box">
                    <img src="./images/review-01.jpg" alt="">
                    <div class="stars">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>"Site très bien conçu, navigation fluide et intuitive, on trouve facilement ce qu’on cherche."
                    </p>
                    <h2>William Hooks</h2>
                </div>
                <div class="review-box">
                    <img src="./images/review-02.jpg" alt="">
                    <div class="stars">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>"Interface moderne et agréable, parfait pour les fans de sneakers."</p>
                    <h2>Luc Hanks</h2>
                </div>
                <div class="review-box">
                    <img src="./images/review-03.jpg" alt="">
                    <div class="stars">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>"Très bon design, la catégorisation par marques et modèles est super pratique."</p>
                    <h2>Peter Does</h2>
                </div>
            </div>


        </section>

        <!-- Modal: description produit -->
        <div id="productModal" class="modal" role="dialog" aria-modal="true" aria-hidden="true">
            <div class="modal-content">
                <button id="modalClose" class="modal-close" aria-label="Fermer">×</button>
                <h2 id="modalTitle"></h2>
                <p id="modalDescription"></p>
                <div class="modal-actions">
                    <a id="modalAddToCart" class="btn" href="#">Ajouter panier</a>
                </div>
            </div>
        </div>

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
                <li>
                    <a href="   "></a>
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
        <script>
            (function () {
                const modal = document.getElementById('productModal');
                const titleEl = document.getElementById('modalTitle');
                const descEl = document.getElementById('modalDescription');
                const addBtn = document.getElementById('modalAddToCart');
                const closeBtn = document.getElementById('modalClose');

                const lienAction = "<?php echo $lien_action; ?>";

                function openModal({ title, description, productId }) {
                    titleEl.textContent = title;
                    descEl.textContent = description;
                    addBtn.href = lienAction + '?product_id=' + encodeURIComponent(productId);
                    modal.setAttribute('aria-hidden', 'false');
                    modal.classList.add('visible');
                }

                function closeModal() {
                    modal.setAttribute('aria-hidden', 'true');
                    modal.classList.remove('visible');
                }

                document.querySelectorAll('a[data-product-id]').forEach((link) => {
                    link.addEventListener('click', (event) => {
                        event.preventDefault();

                        openModal({
                            title: link.dataset.title || 'Produit',
                            description: link.dataset.description || '',
                            productId: link.dataset.productId,
                        });
                    });
                });

                closeBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closeModal();
                    }
                });
            })();
        </script>
    </body>

</html>