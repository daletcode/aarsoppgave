<?php

// Starter en PHP-sesjon for å lagre variabler mellom ulike skriptkjøringer
session_start();

// Frigjør alle registrerte variabler fra sesjonen
session_unset();

// Ødelegger hele sesjonen, inkludert alle lagrede variabler og data
session_destroy();

// Omdirigerer brukeren til login.php-siden etter å ha fullført sesjonsbehandling
header("Location: login.php");

?>