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
?>