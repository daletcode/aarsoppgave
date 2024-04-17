<?php
session_start();
include "db_connect.php";
//henter info fra post formen
if (isset($_POST['brukernavn']) && isset($_POST['passord'])) {

    // Funksjon for å validere inndata
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Validerer brukernavn og passord fra POST-data
    $brukernavn = validate($_POST['brukernavn']);
    $passord = validate($_POST['passord']);

    // Sjekker om brukernavnet er tomt
    if (empty($brukernavn)) {
        header("Location: index.php?error=Username is required!");
        exit();
    }
    // Sjekker om passordet er tomt
    else if (empty($passord)) {
        header("Location: index.php?error=Password is required!");
        exit();
    }

    // SQL-spørring for å hente brukeren basert på brukernavn og passord
    $sql = "SELECT * FROM bruker WHERE brukernavn='$brukernavn' AND passord='$passord'";

    // Utfører spørringen
    $result = mysqli_query($conn, $sql);

    // Sjekker om det er minst én rad i resultatet (gyldig bruker)
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Sjekker om brukernavn og passord matcher
        if ($row['brukernavn'] == $brukernavn && $row['passord'] == $passord) {
            echo "Innlogget";
            // Lagrer brukerinformasjon i sesjonen
            $_SESSION['navn'] = $row['brukernavn'];
            $_SESSION['id'] = $row['idbruker'];
            // Omdirigerer til hovedsiden etter vellykket innlogging
            header("Location: ../index.php");
            echo "her";
            exit();
        } else {
            // Feilmelding hvis brukernavn eller passord er ugyldig
            header("Location: login.php?error=Ugyldig brukernavn eller passord!");
            echo "der";
            exit();
        }
    } else {
        // Omdirigerer til innloggingssiden hvis brukeren ikke finnes
        header("Location: signup.php");
        echo "her";
        exit();
    }
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
    <!-- Skjema for innlogging -->
    <form method="post">
        <h2>Login:</h2>
        <label>Bruker: </label>
        <input type="text" name="brukernavn" placeholder="Brukernavn"><br />
        <label>Passord: </label>
        <input type="password" name="passord" placeholder="Passord"><br />
        <button type="submit">Login</button><br />
    </form>
    <!-- Lenke for å registrere seg -->
    Klikk <a href="signup.php">her</a> for å registrere
</body>

</html>
