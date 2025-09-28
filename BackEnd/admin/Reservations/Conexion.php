<?php


// Étape 1 : Définir les paramètres de connexion
$host = 'localhost';
$dbname = 'rent_cars';
$username = 'root';
$password = '';
// Étape 2 : Établir la connexion
$conn = mysqli_connect($host, $username, $password, $dbname);
// Étape 3 : Vérifier la connexion
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}else{
// echo "Connexion réussie à la base de données '$dbname'.";
}








?>