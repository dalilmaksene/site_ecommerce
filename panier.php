<?php
session_start();

// 1. Vérification de la connexion
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// 2. Connexion à la base de données
function getDBConnection() {
    $host = 'localhost';
    $dbname = 'ecommerce';
    $username = 'root';
    $password = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

// 3. Fonctions de gestion du panier
function getActiveCart($customer_id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT order_id FROM orders 
                          WHERE customer_id = :customer_id AND order_status = 0 
                          ORDER BY order_date DESC LIMIT 1");
    $stmt->execute(['customer_id' => $customer_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['order_id'] : null;
}

function getCartItems($customer_id) {
    $pdo = getDBConnection();
    $order_id = getActiveCart($customer_id);
    
    if (!$order_id) {
        return [];
    }
    
    $stmt = $pdo->prepare("SELECT oi.order_id, oi.item_id, oi.product_id, oi.list_price, oi.discount,
                          p.product_name, p.brand_name,
                          (oi.list_price * (1 - oi.discount)) as final_price
                          FROM order_items oi
                          JOIN products p ON oi.product_id = p.product_id
                          WHERE oi.order_id = :order_id
                          ORDER BY oi.item_id");
    $stmt->execute(['order_id' => $order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCartTotal($customer_id) {
    $pdo = getDBConnection();
    $order_id = getActiveCart($customer_id);
    
    if (!$order_id) {
        return 0;
    }
    
    $stmt = $pdo->prepare("SELECT SUM(list_price * (1 - discount)) as total 
                          FROM order_items WHERE order_id = :order_id");
    $stmt->execute(['order_id' => $order_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

// 4. Récupération des produits du panier
$produits = getCartItems($customer_id);
$totalHT = getCartTotal($customer_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Mon Panier - S&D</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap">
    <style>
        :root {
            --accent: #00f0ff;
            --accent-purple: #a855f7;
            --bg-dark: #0a0a0a;
            --bg-card: #141414;
            --text-primary: #f5f5f5;
            --text-secondary: #a0a0a0;
            --glass-border: rgba(255, 255, 255, 0.06);
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="light"] {
            --bg-dark: #f5f5f7;
            --bg-card: #ffffff;
            --text-primary: #1a1a1a;
            --text-secondary: #555555;
            --glass-border: rgba(0, 0, 0, 0.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            padding-top: 100px;
            background: var(--bg-dark);
            color: var(--text-primary);
            font-family: 'Inter', 'Poppins', sans-serif;
            min-height: 100vh;
            transition: background 0.4s ease, color 0.4s ease;
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: var(--bg-glass, rgba(10, 10, 10, 0.8));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 15px clamp(20px, 5vw, 80px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--glass-border);
            z-index: 1000;
        }

        [data-theme="light"] header {
            background: rgba(255, 255, 255, 0.8);
        }

        /* Toggle Theme */
        .theme-toggle {
            position: fixed; bottom: 35px; right: 35px; z-index: 10000;
            width: 58px; height: 58px; border-radius: 50%;
            border: 2px solid var(--accent); background: var(--bg-card);
            backdrop-filter: blur(20px); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: var(--accent);
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .theme-toggle:hover { transform: scale(1.15) rotate(15deg); background: var(--accent); color: white; }

        .logo img {
            width: 55px;
            filter: brightness(1.1);
            transition: var(--transition);
        }

        .logo img:hover {
            transform: scale(1.05);
            filter: brightness(1.3);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .user-info strong {
            color: var(--accent);
        }

        /* Cart Section */
        .cart-section {
            padding: 40px clamp(20px, 5vw, 80px);
            max-width: 1400px;
            margin: 0 auto;
        }

        .cart-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 2.5rem;
            background: linear-gradient(135deg, var(--accent), var(--accent-purple));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .cart-wrapper {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 3rem;
            align-items: start;
        }

        .cart-items {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: background 0.4s ease, border 0.4s ease;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--glass-border);
            gap: 1.5rem;
            transition: var(--transition);
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item:hover {
            transform: translateX(5px);
        }

        .item-details {
            flex-grow: 1;
        }

        .item-name {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
        }

        .item-brand {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .item-price {
            font-weight: 800;
            color: var(--accent);
            font-size: 1.3rem;
            font-family: 'Outfit', sans-serif;
        }

        .item-discount {
            background: rgba(191, 255, 0, 0.1);
            color: #bfff00;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-left: 0.8rem;
            border: 1px solid rgba(191, 255, 0, 0.2);
        }

        .remove-btn {
            color: #ff4757;
            background: rgba(255, 71, 87, 0.1);
            border: none;
            cursor: pointer;
            font-size: 1.4rem;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .remove-btn:hover {
            background: #ff4757;
            color: #fff;
            transform: rotate(90deg) scale(1.1);
        }

        /* Summary Sidebar */
        .summary {
            background: var(--bg-card);
            padding: 2.5rem;
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            position: sticky;
            top: 120px;
            transition: background 0.4s ease, border 0.4s ease;
        }

        .summary h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: var(--text-primary);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.2rem;
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .total {
            font-size: 1.8rem;
            font-weight: 800;
            border-top: 1px solid var(--glass-border);
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            color: var(--accent);
            font-family: 'Outfit', sans-serif;
        }

        .btn-checkout {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            background: linear-gradient(135deg, var(--accent), var(--accent-purple));
            color: #0a0a0a;
            text-align: center;
            padding: 1.2rem;
            text-decoration: none;
            border-radius: 14px;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2rem;
            transition: var(--transition);
            border: none;
        }

        .btn-checkout:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 240, 255, 0.3);
        }

        @media (max-width: 1200px) {
            .cart-wrapper {
                grid-template-columns: 1fr;
            }
            .summary {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .cart-section {
                padding: 20px;
            }
            header {
                padding: 10px 20px;
            }
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            .item-price {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <button id="theme-toggle" class="theme-toggle" aria-label="Changer le thème"></button>
    <header>
        <a href="index.php" class="logo"><img src="./images/logo_sd.png" alt="S&D"></a>
        <div class="user-info">
            <span>Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Utilisateur'); ?></strong></span>
            <a href="logout.php" title="Déconnexion"><i class='bx bx-log-out' style="font-size: 1.5rem; color: var(--text-primary);"></i></a>
        </div>
    </header>
    <script src="theme-toggle.js"></script>

    <section class="cart-section">
        <h1 class="cart-title"><i class='bx bx-shopping-bag'></i> Mon Panier</h1>

        <div class="cart-wrapper">
            <div class="cart-items">
                <?php if (empty($produits)): ?>
                    <div style="text-align:center; padding: 20px;">
                        <p>Votre panier est encore vide.</p>
                        <a href="index.php" style="color: var(--accent);">Continuer mes achats</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($produits as $item): ?>
                        <div class="cart-item">
                            <div class="item-details">
                                <div class="item-name"><?php echo htmlspecialchars($item['brand_name'] . ' ' . $item['product_name']); ?></div>
                                <div class="item-brand">
                                    Prix unitaire : <?php echo number_format($item['list_price'], 2, ',', ' '); ?> €
                                    <?php if ($item['discount'] > 0): ?>
                                        <span class="item-discount">-<?php echo ($item['discount'] * 100); ?>%</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="item-price"><?php echo number_format($item['final_price'], 2, ',', ' '); ?> €</div>
                            
                            <form method="POST" action="remove_from_cart.php">
                                <input type="hidden" name="order_id" value="<?php echo $item['order_id']; ?>">
                                <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <button type="submit" class="remove-btn" title="Retirer du panier"><i class='bx bx-trash'></i></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="summary">
                <h3>Résumé de la commande</h3>
                <div class="summary-row">
                    <span>Sous-total HT</span>
                    <span><?php echo number_format($totalHT, 2, ',', ' '); ?> €</span>
                </div>
                <div class="summary-row">
                    <span>TVA (20%)</span>
                    <span><?php echo number_format($totalHT * 0.2, 2, ',', ' '); ?> €</span>
                </div>
                <div class="summary-row total">
                    <span>Total TTC</span>
                    <span><?php echo number_format($totalHT * 1.2, 2, ',', ' '); ?> €</span>
                </div>
                <?php if (!empty($produits)): ?>
                    <a href="paiement.php" class="btn-checkout">Procéder au paiement</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

</body>
</html>