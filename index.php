<?php
session_start();

if(isset($_GET['logout'])){	//wenn der Logout Knopf gedrückt wird
	
	
	$fp = fopen("log.html", 'a');
	fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has left the chat session.</i><br></div>");
	fclose($fp); //Schreibt die Verlassen Nachricht in die Logdatei
	
	session_destroy();
	header("Location: index.php"); 
}

function loginForm(){
	echo'
	<div id="loginform">
	<form action="index.php" method="post">
		<p>Please enter your name to continue:</p>
		<label for="name">Name:</label>
		<input type="text" name="name" id="name" />
		<input type="submit" name="enter" id="enter" value="Enter" />
	</form>
	</div>
	'; // Anmeldeformular
}

if(isset($_POST['enter'])){ //wenn der Submit button gedrückt wird
	if($_POST['name'] != ""){ // wenn der name nicht leer ist wird eine Session erstellt
		$_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
	}
	else{
		echo '<span class="error">Please type in a name</span>'; //fehler wenn der Name Leer ist
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>LiveChat</title>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>
<h1 style="text-align:center; font: 50px Broadway;">LiveChat</h1>
<?php
if(!isset($_SESSION['name'])){ //wenn keine Session besteht passiert hier die umleitung zur Login Form
	loginForm();
}
else{
?>
<div id="wrapper">
	<div id="menu">
		<p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
		<p class="logout"><a id="exit" href="#">Exit Chat</a></p>
		<div style="clear:both"></div>
	</div>	
	<div id="chatbox"><?php
	if(file_exists("log.html") && filesize("log.html") > 0){ //liest die Log (Chatverlauf) datei aus, wenn diese nicht leer ist
		$handle = fopen("log.html", "r");
		$contents = fread($handle, filesize("log.html"));
		fclose($handle);
		
		echo $contents;//gibt den Inhalt der Logdatei aus
	}
	?></div>
	
	<form name="message" action="">
		<input name="usermsg" type="text" id="usermsg" size="63" />
		<input name="submitmsg" type="submit"  id="submitmsg" value="Send" />
	</form>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript">
// jQuery Document
$(document).ready(function(){
	//Wenn der Benutzer eine Nachricht abschickt wird diese funktion aufgerufen, die die Nachricht an Post.php weitergibt
	$("#submitmsg").click(function(){	
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});				
		$("#usermsg").attr("value", "");
		return false;
	});
	
	//Funktion um die Logdatei immer wieder zu laden, wird zum aktualisieren verwendet
	function loadLog(){		
		var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
		$.ajax({
			url: "log.html",
			cache: false,
			success: function(html){		
				$("#chatbox").html(html);			
				var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
				if(newscrollHeight > oldscrollHeight){
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Immer nach unten autoscrollen
				}				
		  	},
		});
	}
	setInterval (loadLog, 50);	//datei alle 50ms neu laden
	
	//Wenn der Benutzer aussteigen will wird diese Funktion auferufen, wo bestätigt wird, dass mann verlassen will
	$("#exit").click(function(){
		var exit = confirm("Are you sure you want to end the session?");
		if(exit==true){window.location = 'index.php?logout=true';}		
	});
});
</script>
<?php
}
?>
</body>
</html>