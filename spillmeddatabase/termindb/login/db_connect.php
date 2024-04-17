<?php

// Definerer variabler for databaseforbindelse
$server = "localhost";
$user = "root";
$pw = "";
$db = "mydb";

// Oppretter en tilkobling til databasen ved hjelp av mysqli-funksjonen
$conn = mysqli_connect($server, $user, $pw, $db);

// Sjekker om tilkoblingen var vellykket
if (!$conn) {
    echo "Connection failed!";
}

?>