<?php
session_start();
if(isset($_SESSION['name'])){
	$text = $_POST['text'];
	// Schreibt die Nachricht in die Logdatei mit Benutzername, Datum und Inhalt
	$fp = fopen("log.html", 'a');
	fwrite($fp, "<div class='msgln'>(".date("g:i A").") <b>".$_SESSION['name']."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
	fclose($fp);
}
?>