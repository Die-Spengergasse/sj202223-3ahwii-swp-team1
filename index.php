<?php
session_start();

// Logout handler
if (isset($_GET['logout'])) {
    $fp = fopen("log.html", 'a');// Schreibt in den Chatverlauf (Logdatei log.html), dass der Benutzer den Chat verlassen hat, wenn Logout gedr�ckt wird
    fwrite($fp, "<div class='msgln'><i>User " . $_SESSION['name'] . " has left the chat session.</i><br></div>");
    fclose($fp);

    // Meldet hier den Benutzer ab und f�hrt ihn zur Loginform zur�ck
    session_destroy();
    header("Location: index.php");
}

/**
 * Die Funktion loginForm Gibt eine Sch�ne Seite aus, wo man sich einen Benutzernamen f�r den Chat aussuchen kann
 */
function loginForm()
{
    echo '
	<div id="loginform">
	<form action="index.php" method="post">
		<p>Please enter your name to continue:</p>
		<label for="name">Name:</label>
		<input type="text" name="name" id="name" />
		<input type="submit" name="enter" id="enter" value="Enter" />
	</form>
	</div>
	';
}

// Login handler
if (isset($_POST['enter'])) {
    
    if ($_POST['name'] != "") // Wenn das Namensfeld nicht Leer ist wird hier eine Chat Session mit dem Gew�hlten Benutzernamen angelegt
    {
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    } else {
        // Wenn das Namensfeld leer ist wird hier ein Fehler ausgegeben
        echo '<span class="error">Please type in a name</span>';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>LiveChat</title><!-- Hier wird die Haupt-Seite Erstellt, auf welcher der Benutzer dann mit dem Chat interagiert -->
<link type="text/css" rel="stylesheet" href="style.css" />
</head>
<body>
<h1 style="text-align:center; font: 50px Broadway;">LiveChat</h1>
<?php
if (!isset($_SESSION['name'])) // Wenn der Benutzer nicht angemeldet ist wird er hier zur loginForm funktion gesendet
{
    loginForm();
} 
else {
?>
<div id="wrapper">
	<div id="menu">
		<p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p> <!-- Hier wird der Benutzername im linken oberen Eck der Chatbox angezeigt in der Form "Willkommen, Benutzername -->"
		<p class="logout"><a id="exit" href="#">Exit Chat</a></p> <!-- hier ist der Text Exit Chat, welcher gleichzeitig als Logout Button funktioniert -->
		<div style="clear:both"></div>
	</div>	
	<div id="chatbox">
    <?php
    // Wenn die Logdatei nicht Leer ist wird sie hier im Chat angezeigt. Wenn die Datei leer ist, dann gibt es nichts anzuzeigen. 
    if (file_exists("log.html") && filesize("log.html") > 0) //�berpr�ft, dass die Datei existiert und die Gr��e mehr als 0 Bytes ist
    {
        $handle = fopen("log.html", "r");
        $contents = fread($handle, filesize("log.html"));
        fclose($handle);

        echo $contents;
    }
    ?>
    </div>
	 
	<form name="message" action=""> <!-- Eingabebox, wo der Benutzer die Nachricht eingibt -->
		<input name="usermsg" type="text" id="usermsg" size="63" />
		<input name="submitmsg" type="submit"  id="submitmsg" value="Send" />
	</form>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script> <!-- inklusion von jQuery, einer JavaScript library -->
<script type="text/javascript">

$(document).ready(function () {
    
    $("#submitmsg").click(function () //in dieser Funktion wird die Nachricht an den Programmteil post.php weitergegeben, der sie dann in den Chatverlauf schreibt
    {
        var clientmsg = $("#usermsg").val();

        
        $.post("post.php", { text: clientmsg }); //sendet hier den Text der nachricht an post.php
        $("#usermsg").attr("value", ""); //leert das Inputfeld, damit die n�chste Nachricht eingegeben werden kann
        return false;
    });

    // die Funktion loadLog wird verwendet um den Chatverlauf kontinuierlich zu laden und auch um bei neuen Nachrichten automatisch ganz nach unten zu scrollen
    function loadLog() {
        var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;

        $.ajax({
            url: "log.html",
            cache: false,
            success: function (html) {
                $("#chatbox").html(html);
                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;

                // Hier wird gescrollt, wenn eine neue Nachricht eintrifft
                if (newscrollHeight > oldscrollHeight) {
                    $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
                }
            },
        });
    }

    // hier wird der Chatverlauf alle 50 Millisekunden aus log.html geladen damit immer der neueste Stand in der Chatbox zu sehen ist.
    setInterval(loadLog, 50);

    // �berpr�ft beim Logout, ob der Benutzer den Chat wirklich verlassen will, damit man nicht unabsichtlich auf logout klicken kann
    $("#exit").click(function () {
        var exit = confirm("Are you sure you want to end the session?");
        if (exit == true) {
            window.location = 'index.php?logout=true';
        }
    });
});
</script>
<?php
}
?>
</body>
</html>