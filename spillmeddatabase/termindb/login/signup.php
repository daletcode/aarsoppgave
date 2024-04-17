<?php
// Starter en PHP-sesjon
session_start();

// Inkluderer filen for databasekobling
include "db_connect.php";

// Sjekker om POST-data for brukernavn og passord er satt
if(isset($_POST['brukernavn']) && isset($_POST['passord'])) {

    // Funksjon for å validere inndata og fjerne uønskede tegn
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Henter og validerer brukernavn og passord fra POST-data
    $brukernavn = validate($_POST['brukernavn']);
    $passord = validate($_POST['passord']);

    // Sjekker om brukernavnet er tomt
    if(empty($brukernavn)) {
        // Omdirigerer tilbake til registreringssiden med feilmelding hvis brukernavnet mangler
        header("Location: signup.php?error=Username is required!");
        exit();
    }
    // Sjekker om passordet er tomt
    else if(empty($passord)) {
        // Omdirigerer tilbake til registreringssiden med feilmelding hvis passordet mangler
        header("Location: signup.php?error=Password is required!");
        exit();
    }

    // SQL-spørring for å legge til en ny bruker i databasen
    $sql = "INSERT INTO bruker(brukernavn, passord) VALUES ('$brukernavn', '$passord');"; 

    // Utfører spørringen
    $result = mysqli_query($conn, $sql);

    // Omdirigerer til innloggingssiden etter vellykket registrering
    header("location: login.php");
    exit();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Skjema for registrering -->
    <form  method="post">
        <h2>Signup:</h2>
        <label>Bruker: </label>
        <input type="text" name="brukernavn" placeholder="Brukernavn"><br/>
        <label>Passord: </label>
        <input type="password" name="passord" placeholder="Passord"><br/>
        <button type="submit">signup</button><br/>
    </form>
</body>
</html>

