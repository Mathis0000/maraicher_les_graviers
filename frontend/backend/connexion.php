<?php
include('/dev/config.php');

try {
    $access = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $access->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>
