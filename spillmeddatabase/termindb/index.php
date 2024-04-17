<?php

    // Starter en PHP-sesjon for å lagre variabler mellom ulike skriptkjøringer
    session_start();

    // Inkluderer en fil for databaseforbindelse
    include "login/db_connect.php";

    // Sjekker om brukeren ikke er logget inn, og omdirigerer til påloggingsiden hvis det er tilfellet
    if (!isset($_SESSION["id"]) && !isset($_SESSION["navn"])){
        header("location: login/login.php");
    }

    // Håndterer POST-data når skjemaet er sendt
    if(isset($_POST["score"])){
        $score = $_POST["score"];
        $user = $_SESSION["id"];

        // Lager SQL-spørring for å sette inn score i databasen
        $sql = "INSERT INTO reaction(score, bruker) VALUES ('$score', '$user');"; 

        // Utfører SQL-spørringen og lagrer resultatet
        $result = mysqli_query($conn, $sql);

    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- HTML-kode for brukergrensesnittet -->

<div class="end-screen ">
    <div class="container">
        <!-- Innhold for sluttskjermen -->
        <h1>Reaction Time Tet</h1>
        <div class="reaction-time-text">234 ms</div>
        <button><div class="play-again-btn">Play Again</div></button>
        
        <!-- Skjema for å lagre score -->
        <form id="post" method="POST">
            <div class="score">
            <input type="number" id="score" name="score">
            </div>
            <button type="submit"><div class="play-again-btn">Save score</div></button>
        </form>

        <!-- Toppscore-liste fra databasen -->
        <div class="topscore">
            <?php
                // SQL-spørring for å hente topplisten fra databasen
                $sql = "SELECT r.*, b.brukernavn FROM reaction r
                        JOIN bruker b ON r.bruker = b.idbruker
                        ORDER BY r.score ASC
                        LIMIT 8";

                // Utfører SQL-spørringen og viser resultatet
                $result = mysqli_query($conn, $sql);
                echo "<h1>LEADERBOARD</h1>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<h1 class='top10'> PLAYER: " . $row["brukernavn"] . " | SCORE: " . $row["score"] . "</h1><br>";
                }
            ?>
        </div>
    </div>
</div>

<!-- Hovedmenyen -->
<div class="main-menu active">
    <div class="container">
        <!-- Innhold for hovedmenyen -->
        <h1>Reaction Time Test</h1>
        <p>To score click the left mousebutton as fast as you can </p>
        <p> when you the screen color changes to green</p>
        <p>Click anywhere on the screen to start </p>
        <p> <h2> <i> the "Reaction Test" game! </i> </h2> </p>
        <!-- Logg ut-knapp som fører til en loggut-skript -->
        <a href="login/logout.php" class="logout-btn">Logg ut</a>
    </div>
</div>

<!-- Klikkbart område for spillet -->
<div class="clickable-area">
    <div class="message">Click Now!</div>
</div>

<!-- JavaScript-fil for spillet -->
<script src="reaction.js"></script>

</body>
</html>
