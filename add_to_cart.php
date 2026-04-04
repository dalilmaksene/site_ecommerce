<?php
session_start();

// 1. Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Vérifier si un product_id a été envoyé
if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    header("Location: index.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_GET['product_id'];

// Connexion à la base de données
$host = 'localhost';
$dbname = 'ecommerce';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // --- ÉTAPE A : TROUVER OU CRÉER LE PANIER ---
    $stmt = $pdo->prepare("SELECT order_id FROM orders 
                          WHERE customer_id = :customer_id AND order_status = 0 
                          ORDER BY order_date DESC LIMIT 1");
    $stmt->execute(['customer_id' => $customer_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    // On récupère l'ID s'il existe, sinon null
    $order_id = $cart ? $cart['order_id'] : null;

    if (!$order_id) {
        // On définit une date requise (aujourd'hui + 7 jours) pour éviter l'erreur 1364
        $required_date = date('Y-m-d', strtotime('+7 days'));
        
        $stmt = $pdo->prepare("INSERT INTO orders (customer_id, order_date, required_date, order_status) 
                               VALUES (:customer_id, NOW(), :required_date, 0)");
        $stmt->execute([
            'customer_id' => $customer_id,
            'required_date' => $required_date
        ]);
        $order_id = $pdo->lastInsertId();
    }

    // --- ÉTAPE B : RÉCUPÉRER LE PRIX DU PRODUIT ---
    $stmt = $pdo->prepare("SELECT list_price FROM products WHERE product_id = :product_id");
    $stmt->execute(['product_id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header("Location: index.php?error=product_not_found");
        exit();
    }
    
    // --- ÉTAPE C : VÉRIFIER SI LE PRODUIT EST DÉJÀ DANS CE PANIER ---
    $stmt = $pdo->prepare("SELECT item_id FROM order_items 
                          WHERE order_id = :order_id AND product_id = :product_id");
    $stmt->execute(['order_id' => $order_id, 'product_id' => $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        header("Location: panier.php?msg=already_in_cart");
        exit();
    }
    
    // --- ÉTAPE D : AJOUTER LE PRODUIT AU PANIER ---
    // Calcul du prochain item_id pour cette commande spécifique
    $stmt = $pdo->prepare("SELECT COALESCE(MAX(item_id), 0) + 1 as next_id 
                           FROM order_items WHERE order_id = :order_id");
    $stmt->execute(['order_id' => $order_id]);
    $item_id = $stmt->fetch(PDO::FETCH_ASSOC)['next_id'];
    
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, item_id, product_id, list_price, discount) 
                          VALUES (:order_id, :item_id, :product_id, :list_price, 0)");
    $stmt->execute([
        'order_id'   => $order_id,
        'item_id'    => $item_id,
        'product_id' => $product_id,
        'list_price' => $product['list_price']
    ]);
    
    // Tout est bon ! Direction le panier
    header("Location: panier.php?msg=added");
    exit();
    
} catch(PDOException $e) {
    // En cas d'erreur, on affiche le message pour débugger
    die("Erreur technique : " . $e->getMessage());
}
?>