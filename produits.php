<?php
include 'config.php'; // Connexion à la BDD

if (isset($_GET['id'])) {
    $id_produit = intval($_GET['id']); 

    // Simuler une liste de produits
    $produits = [
        1 => "Nike Air Force",
        2 => "Adidas Superstar",
        3 => "Asics Gel-Kayano",
    ];

    if (array_key_exists($id_produit, $produits)) {
        $nom_produit = $produits[$id_produit];

        // Insérer dans la BDD
        $stmt = $pdo->prepare("INSERT INTO panier (panier_id, nom_produit) VALUES (:panier_id, :nom_produit)");
        $stmt->execute([
        ':panier_id' => $id_produit,
        ':nom_produit' => $nom_produit
        ]);


        echo "<h1>Produit ajouté au panier !</h1>";
        echo "<p>ID du produit : " . $id_produit . "</p>";
        echo "<p>Nom du produit : " . $nom_produit . "</p>";
        echo "<a href='index.php'>Retour</a>";
    } else {
        echo "<p>Produit introuvable.</p>";
    }
} else {
    echo "<p>Aucun produit sélectionné.</p>";
}
?>


