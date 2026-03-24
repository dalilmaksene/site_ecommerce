<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['item_id'])) {
    
    $host = 'localhost';
    $dbname = 'ecommerce';
    $username = 'root';
    $password = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Supprimer l'article du panier
        $stmt = $pdo->prepare("DELETE FROM order_items 
                              WHERE order_id = :order_id AND item_id = :item_id");
        $stmt->execute([
            'order_id' => $_POST['order_id'],
            'item_id' => $_POST['item_id']
        ]);
        
        // Redirection vers le panier
        header("Location: panier.php");
        exit();
        
    } catch(PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    header("Location: panier.php");
    exit();
}
?>