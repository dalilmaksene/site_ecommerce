<?php
session_start();
require_once 'check_session.php';

// 1. Sécurité : redirection si non connecté
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';
$customer_id = $_SESSION['customer_id'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Récupérer le panier actuel (order_status = 0)
    $stmt = $pdo->prepare("SELECT o.order_id, SUM(oi.list_price * (1 - oi.discount)) as total 
                          FROM orders o 
                          JOIN order_items oi ON o.order_id = oi.order_id 
                          WHERE o.customer_id = :customer_id AND o.order_status = 0 
                          GROUP BY o.order_id");
    $stmt->execute(['customer_id' => $customer_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        header("Location: panier.php?error=empty_cart");
        exit();
    }

    // 3. Traitement de la validation (clic sur payer)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ici, on simule la validation du paiement
        // On passe le statut de la commande à 1 (Validée/Payée)
        $update = $pdo->prepare("UPDATE orders SET order_status = 1, shipped_date = NOW() 
                                WHERE order_id = :order_id");
        $update->execute(['order_id' => $cart['order_id']]);

        header("Location: confirmation.php");
        exit();
    }

    

} catch (PDOException $e) {
    die("Erreur technique : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Sécurisé - S&D</title>
    <link rel="stylesheet" href="style.css">
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

        body { 
            background: var(--bg-dark); 
            font-family: 'Inter', 'Poppins', sans-serif; 
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: ""; position: absolute; top: -20%; right: -10%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(0,240,255,0.05) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }

        .payment-container { 
            max-width: 500px; 
            width: 90%;
            padding: 40px; 
            background: rgba(20, 20, 20, 0.8); 
            backdrop-filter: blur(24px);
            border-radius: 24px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.4); 
            border: 1px solid var(--glass-border);
            position: relative;
            z-index: 10;
        }

        .payment-header { text-align: center; margin-bottom: 35px; }
        .payment-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            background: linear-gradient(135deg, #00f0ff, #a855f7);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }
        .payment-header p { color: var(--text-secondary); font-size: 0.9rem; }

        .total-box { 
            background: rgba(255, 255, 255, 0.03); 
            padding: 24px; 
            border-radius: 16px; 
            text-align: center; 
            margin-bottom: 35px; 
            border: 1px solid var(--glass-border);
        }
        .total-box p { color: var(--text-secondary); margin-bottom: 8px; font-size: 0.9rem; }
        .total-amount { 
            font-size: 2.2rem; 
            font-weight: 800; 
            color: var(--accent); 
            font-family: 'Outfit', sans-serif;
        }

        .form-group { margin-bottom: 24px; }
        .form-group label { 
            display: block; 
            margin-bottom: 10px; 
            font-weight: 600; 
            color: var(--text-secondary); 
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-group input { 
            width: 100%; 
            padding: 14px 18px; 
            background: rgba(255,255,255,0.03);
            border: 2px solid var(--glass-border); 
            border-radius: 12px; 
            box-sizing: border-box; 
            color: var(--text-primary);
            font-family: inherit;
            font-size: 1rem;
            transition: var(--transition);
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            background: rgba(255,255,255,0.06);
            box-shadow: 0 0 0 4px rgba(0, 240, 255, 0.1);
        }

        .card-row { display: flex; gap: 15px; }

        .btn-pay { 
            width: 100%; 
            background: linear-gradient(135deg, #00f0ff, #a855f7); 
            color: #0a0a0a; 
            border: none; 
            padding: 18px; 
            border-radius: 14px; 
            font-size: 1.1rem; 
            font-weight: 800; 
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer; 
            transition: var(--transition); 
            margin-top: 10px;
        }
        .btn-pay:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 12px 30px rgba(0, 240, 255, 0.3);
        }

        .secure-badge { 
            text-align: center; 
            margin-top: 25px; 
            color: var(--text-muted); 
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <div class="payment-header">
        <h1>Finaliser la commande</h1>
        <p>Commande n° #<?php echo $cart['order_id']; ?></p>
    </div>

    <div class="total-box">
        <p>Montant total à régler</p>
        <div class="total-amount"><?php echo number_format($cart['total'], 2); ?> €</div>
    </div>

    <form method="POST">
        <div class="form-group">
            <label>Nom sur la carte</label>
            <input type="text" placeholder="Ex: Jean Dupont" required>
        </div>
        <div class="form-group">
            <label>Numéro de carte</label>
            <input type="text" placeholder="0000 0000 0000 0000" maxlength="16" required>
        </div>
        <div class="card-row">
            <div class="form-group" style="flex: 2;">
                <label>Expiration</label>
                <input type="text" placeholder="MM/AA" maxlength="5" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>CVC</label>
                <input type="text" placeholder="123" maxlength="3" required>
            </div>
        </div>

        <button type="submit" class="btn-pay">Payer maintenant</button>
    </form>

    <div class="secure-badge">
        🔒 Paiement sécurisé SSL - Simulation
    </div>
</div>

</body>
</html>