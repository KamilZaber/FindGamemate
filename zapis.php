<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf8" />
		<link rel="Stylesheet" type="text/css" href="style.css" />
		<title>Biblioteka</title>
	</head>
	<body>
		<?php
			@$nick = $_GET['nick'];
			@$haslo = $_GET['haslo'];
			@$serwer = $_GET['serwer'];
			@$ilosc = $_GET['ilosc'];
			$baza = mysqli_connect("localhost", "root", "", "find_game_mate");
			
			$usercheck = mysqli_query($baza, 'SELECT nick FROM gracze WHERE nick="' . $nick . '" AND haslo="' . $haslo . '"');
			
			if(mysqli_num_rows($usercheck) == 1) {
					mysqli_query($baza, 'INSERT INTO ogloszenia VALUES ("", "' . $nick . '", "' . $serwer . '", ' . $ilosc .')');
			}
			
			mysqli_close($baza);
			header("Location: index.php?p=2");
		?>
	</body>
</html>