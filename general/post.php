<?php
session_start();

// Schimpfwörter die Gefiltert werden sollen
$bad_words = array(
    "damn", "idiot",
    "hell", 
    "shit", 
    "fuck", 
    "asshole", 
    "bastard", 
    "bitch", 
    "piss",
    "scheiße", 
    "arschloch", 
    "hurensohn", 
    "fick dich", 
    "wichser", 
    "fotze", 
    "pisser", 
    "schwanzlutscher", 
    "schlampen", 
    "schwuchtel", 
    "drecksau", 
    "miststück", 
    "kackbratze", 
    "hure", 
    "vollidiot"
);

// Überprüft ob der Benutzer angemeldet ist
if (isset($_SESSION['name'])) {
    // Holt die Nachricht aus der POST Funktion von index.php
    $text = $_POST['text'];

    // Prüft, ob die Nachricht die maximale Länge überschreitet
    if (strlen($text) > 255) {
        echo "<span class='error'>Die Nachricht darf maximal 255 Zeichen enthalten.</span>";
    } else {
        // Hier werden die Schimpfwörter
        foreach ($bad_words as $word) {
            if (strpos($text, $word) !== false) {
                $stars = str_repeat('*', strlen($word));
                $text = str_ireplace($word, $stars, $text);
            }
        }

        // Stellt sicher, dass keine leeren Nachrichten gesendet werden (Leerzeichen werden ignoriert, sprich auch mit drei Leerzeichen wird nichts gesendet)
        if (!empty(trim($text))) {
            // Öffnet die Chatlog Datei
            $fp = fopen("log.html", 'a');

            // Formatiert die Nachricht mit Zeitstempel, Benutzername und Inhalt
            $formattedMessage = "<div class='msgln'>(" . date("g:i A") . ") <b>" . $_SESSION['name'] . "</b>: " . stripslashes(htmlspecialchars($text)) . "<br></div>";

            // Schreibt die Nachricht in die Logdatei
            fwrite($fp, $formattedMessage);

            // Schließt die Logdatei
            fclose($fp);
        }
    }
}
