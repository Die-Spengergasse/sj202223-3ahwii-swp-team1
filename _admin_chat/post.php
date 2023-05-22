<?php
session_start();

// Bad words list
$bad_words = array(
    "damn", 
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

    // Replace bad words
    foreach ($bad_words as $word) {
        if (strpos($text, $word) !== false) {
            $text = str_ireplace($word, '[Zensiert]', $text);
        }
    }

    // Stellt sicher, dass keine leeren Nachrichten gesendet werden (leerzeichen werden ignoriert, sprich auch mit drei leerzeichen wird nichts gesendet)
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