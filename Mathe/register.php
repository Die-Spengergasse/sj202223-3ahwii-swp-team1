<?php
// Start der Session
session_start();
// Einbinden der Konfigurationsdatei für die Datenbankverbindung
require_once 'config.php';

// Wenn der Benutzer bereits angemeldet ist, zur Index-Seite weiterleiten
if (isset($_SESSION['name'])) {
    header("Location: index.php");
    exit;
}

// Überprüfen, ob das Registrierungsformular abgesendet wurde
if (isset($_POST['register'])) {
    $username = $_POST['reg_name'];
    $password = $_POST['reg_password'];

    // Überprüfen, ob Benutzername und Passwort eingegeben wurden
    if ($username != "" && $password != "") {
        // Passwort hashen
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // SQL-Abfrage vorbereiten, um den Benutzer in die Datenbank einzufügen
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        // Benutzername und Passwort an die SQL-Abfrage binden
        $stmt->bind_param("ss", $username, $hashed_password);

        // SQL-Abfrage ausführen
        if ($stmt->execute()) {
            // Session-Variablen für den Benutzer setzen
            $_SESSION['name'] = $username;

            // Benutzer zur Haupt-Chat-Seite weiterleiten
            header("Location: index.php");
        } else {
            echo '<span class="error">Fehler: ' . $stmt->error . '</span>';
        }
        // Schließen des Prepared Statements
        $stmt->close();
    } else {
        echo '<span class="error">Bitte geben Sie einen Benutzernamen und ein Passwort ein.</span>';
    }
}

// Schließen der Datenbankverbindung
$conn->close();
?>
