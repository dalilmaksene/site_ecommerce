<?php
require_once 'config.php';
$res = mysqli_query($lien_base, "SELECT product_id, product_name, list_price FROM products");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['product_id'] . ": " . $row['product_name'] . " - " . $row['list_price'] . "\n";
}
