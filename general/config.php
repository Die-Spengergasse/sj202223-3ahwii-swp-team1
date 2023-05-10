<?php
// Datenbankverbindungsparameter
$servername = 'Datenbankserver';
$username = 'Benutzername';
$password = 'Benutzerpasswort';
$dbname =  'Datenbankname ';

// Erstellen einer Verbindung zur Datenbank
$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfung der Verbindung
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Setzen des Zeichensatzes für die Verbindung auf utf8
$conn->set_charset("utf8");
?>
