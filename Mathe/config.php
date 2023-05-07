<?php
// Datenbank-Verbindungsinformationen
$db_host = 'datnebankserver';
$db_name = 'Datenbankname';
$db_user = 'username';
$db_pass = 'passwort';

// Versuchen, eine Verbindung zur Datenbank herzustellen
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Fehlermodus auf Exceptions setzen
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Verbindungsfehlermeldung ausgeben
    echo "Verbindung fehlgeschlagen: " . $e->getMessage();
}
?>