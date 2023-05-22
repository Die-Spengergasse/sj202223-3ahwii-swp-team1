CREATE TABLE IF NOT EXISTS users ( --Erzeugt eine Tabelle namens 'users', wenn diese noch nicht existiert
    id INT AUTO_INCREMENT PRIMARY KEY, -- Erstellt einen Primärschlüssel
    username VARCHAR(255) NOT NULL UNIQUE, -- Erstellt Benutzernamen
    password VARCHAR(255) NOT NULL, -- Erstellt Passwort
    is_admin BOOLEAN DEFAULT 0 -- Identifiziert Administratoren
);
