<?php
require_once 'config.php';

// Verbindung zur Datenbank herstellen
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
// Nachricht an User falls ein Fehler aufkommt 
catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Tabelle 'users' erstellen, falls sie noch nicht existiert
try {
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        is_admin BOOLEAN DEFAULT 0
    );
    ";
    
    $pdo->exec($sql);
    echo "Table 'users' created successfully.";

}
// Nachricht falls ein Fehler aufkommt
catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
