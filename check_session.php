<?php
// check_session.php - Fichier à inclure sur toutes les pages

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['customer_id']);
}

// Fonction pour obtenir le prénom de l'utilisateur
function getUserFirstName() {
    return $_SESSION['first_name'] ?? 'Invité';
}

// Fonction pour obtenir l'ID du client
function getCustomerId() {
    return $_SESSION['customer_id'] ?? null;
}

/**
 * Fonction pour récupérer le nombre d'articles dans le panier
 * @param mysqli $lien_base Connexion à la base de données (défini dans config.php)
 * @param int|null $customer_id ID de l'utilisateur (peut être nul si déconnecté)
 * @return int Nombre d'articles total
 */
function getCartItemCount($lien_base, $customer_id) {
    // 1. Si l'utilisateur n'est pas connecté, on renvoie 0 article par défaut
    if (!$customer_id) {
        return 0;
    }
    
    // 2. Requête SQL pour compter les lignes dans table 'order_items'
    // On fait une jointure avec table 'orders' pour identifier le panier actif (status 0) du client spécifique
    $query = "SELECT COUNT(*) as total 
              FROM order_items oi
              JOIN orders o ON oi.order_id = o.order_id
              WHERE o.customer_id = ? AND o.order_status = 0";
              
    // 3. Préparation de la requête pour la sécurité (évite les injections SQL)
    $stmt = mysqli_prepare($lien_base, $query);
    
    // 4. On remplace le '?' dans la requête par l'ID réel de l'utilisateur ('i' signifie entier)
    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    
    // 5. Envoi de la commande finale vers la base de données MySQL
    mysqli_stmt_execute($stmt);
    
    // 6. Extraction des données brutes reçues en retour
    $result = mysqli_stmt_get_result($stmt);
    
    // 7. Lecture de la ligne de résultat sous forme d'un tableau associatif
    $row = mysqli_fetch_assoc($result);
    
    // 8. On retourne la valeur numérique trouvée (ou 0 si aucune donnée n'existe)
    return (int)($row['total'] ?? 0);
}
?>