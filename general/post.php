
session_start();
// Schimpfwortliste
$badWords = array('Scheiße', 'Fick', 'Kaka', 'Bastard');

// Überprüft ob der Benutzer angemeldet ist
if (isset($_SESSION['name'])) {
    // Holt die Nachricht aus der POST Funktion von index.php
    $text = $_POST['text'];

    // Überprüft, ob die letzte Nachricht innerhalb der Zeitbegrenzung liegt
    $currentTime = time();
    $lastMessageTime = isset($_SESSION['last_message_time']) ? $_SESSION['last_message_time'] : 0;
    $timeDiff = $currentTime - $lastMessageTime;
    $messageLimit = 10; // Anzahl der Nachrichten pro Zeitbegrenzung
    $timeLimit = 60; // Zeitbegrenzung in Sekunden

    if ($timeDiff > $timeLimit || $_SESSION['message_count'] < $messageLimit) {
        // Stellt sicher, dass keine leeren Nachrichten gesendet werden (Leerzeichen werden ignoriert)
        if (!empty(trim($text))) {
            // Überprüft die Nachricht auf Schimpfwörter
            $hasBadWord = false;
            foreach ($badWords as $word) {
                if (stripos($text, $word) !== false) {
                    $hasBadWord = true;
                    break;
                }
            }

            if (!$hasBadWord) {
                // Öffnet die Chatlog Datei
                $fp = fopen("log.html", 'a');

                // Formatiert die Nachricht mit Zeitstempel, Benutzername und Inhalt
                $formattedMessage = "<div class='msgln'>(" . date("g:i A") . ") <b>" . $_SESSION['name'] . "</b>: " . stripslashes(htmlspecialchars($text)) . "<br></div>";

                // Schreibt die Nachricht in die Logdatei
                fwrite($fp, $formattedMessage);

                // Schließt die Logdatei
                fclose($fp);

                // Aktualisiert die Zeit der letzten Nachricht und die Anzahl der gesendeten Nachrichten
                $_SESSION['last_message_time'] = $currentTime;
                $_SESSION['message_count'] = isset($_SESSION['message_count']) ? $_SESSION['message_count'] + 1 : 1;
            } else {
                echo "Ihre Nachricht enthält unangemessene Inhalte. Bitte formulieren Sie Ihre Nachricht um.";
            }
        }
    } else {
        echo "Sie haben das Nachrichtenlimit überschritten. Bitte warten Sie eine Weile, bevor Sie eine weitere Nachricht senden.";
    }
}
