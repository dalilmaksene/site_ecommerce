<?php
require_once 'config.php';
$res = mysqli_query($lien_base, "DESCRIBE products");
while ($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . "\n";
}
?>
