<?php
// Starten der Session
session_start();
// Einbinden der Konfigurationsdatei für die Datenbankverbindung
require_once 'config.php';

// Überprüfen, ob das Login-Formular abgesendet wurde
if (isset($_POST['login'])) {
    $username = $_POST['login_name'];
    $password = $_POST['login_password'];

    // Überprüfen, ob Benutzername und Passwort eingegeben wurden
    if ($username != "" && $password != "") {
        // SQL-Abfrage vorbereiten, um Benutzerdaten aus der Datenbank abzurufen
        $stmt = $conn->prepare("SELECT id, username, password, is_admin FROM users WHERE username = ?");
        // Benutzername an die SQL-Abfrage binden
        $stmt->bind_param("s", $username);
        // SQL-Abfrage ausführen
        $stmt->execute();
        // Ergebnis speichern
        $stmt->store_result();
        // Ergebnisvariablen binden
        $stmt->bind_result($id, $db_username, $db_password, $is_admin);

        // Überprüfen, ob ein Benutzer mit dem eingegebenen Benutzernamen gefunden wurde
        if ($stmt->num_rows > 0) {
            // Benutzerdaten abrufen
            if ($stmt->fetch()) {
                // Überprüfen, ob das eingegebene Passwort mit dem gespeicherten Passwort übereinstimmt
                if (password_verify($password, $db_password)) {
                    // Benutzername und Admin-Status in der Session speichern
                    $_SESSION['name'] = $db_username;
                    $_SESSION['is_admin'] = $is_admin;
                    // Weiterleitung zur Index-Seite
                    header("Location: index.php");
                } else {
                    echo '<span class="error">Ungültiges Passwort.</span>';
                }
            }
        } else {
            echo '<span class="error">Ungültiger Benutzername.</span>';
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