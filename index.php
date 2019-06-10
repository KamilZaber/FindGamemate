<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf8" />
		<link rel="Stylesheet" type="text/css" href="style.css" />
		<title>FindGamemate</title>
		<style type="text/css">
			#tresc {
				overflow-x: hidden;
				overflow-y: scroll;
			}
		</style>
	</head>
	<body>
		<div id="calosc">
			<div id="naglowek">
				<h1>FIND GAMEMATE</h1>
			</div>
			<div id="menu">
				<a href="index.php?p=1">dodaj ogloszenie</a>
				<a href="index.php?p=2">pokaz ogloszenia</a>
			</div>
			<div id="tresc">
				<?php
					@$wybor = $_GET['p'];
					
					if($wybor == NULL)
						$wybor = 2;
					
					if($wybor == 1) {
						$baza = mysqli_connect("localhost", "root", "", "find_game_mate");
						mysqli_query($baza, "SET NAMES utf8");
						
						$serwery = mysqli_query($baza, "SELECT nazwa,gra FROM serwery");
						
						echo '
							<form name="wprowadzdane" action="zapis.php" method="GET">
								<h1 id="naglowek_autor">Dodaj ogłoszenie:</h1><hr />
								Nick: <br /><input type="text" name="nick"></input><br /><br />
								Haslo: <br /><input type="text" name="haslo"></input><br /><br />
								Serwer: <br /><select name="serwer">
						';
						
						for($i = 0; $i < mysqli_num_rows($serwery); $i++) {
							$serwer = mysqli_fetch_array($serwery);
							echo '<option value="' . $serwer['nazwa'] .'">' . $serwer['nazwa'] . '</option>';
						}
						
						
						echo '</select><br /><br />
							Ilosc poszukiwanych:<br /><input type="text" name="ilosc"></input><br /><br />
							<button type="submit">Zapisz</button><br /><br />
							</form>
							<a href="index.php?p=2"><button type="button">Pomiń</button></a>';
					}
					
					if($wybor == 2) {
						echo '<table border="2">
								<tr>
									<th id="tabela_gracz">GRACZ</th>
									<th id="tabela_gra">NAZWA GRY</th>
									<th id="tabela_serwer">NAZWA SERWERA</th>
									<th id="tabela_ilosc_poszukiwanych">POSZUKIWANYCH GRACZY</th>
								</tr>';
						$baza = mysqli_connect("localhost", "root", "", "find_game_mate");
						mysqli_query($baza, "SET NAMES utf8");
						$ogloszenia = mysqli_query($baza, "SELECT gracz,serwer,ilosc_poszukiwanych,serwery.gra AS gra FROM ogloszenia INNER JOIN serwery ON ogloszenia.serwer = serwery.nazwa");
						for($i=0; $i < mysqli_num_rows($ogloszenia); $i++) {
							$ogloszenie = mysqli_fetch_array($ogloszenia);
							echo '<tr>
									<td><a href="index.php?p=3&wyb_gracz=' . $ogloszenie['gracz'] . '">' . $ogloszenie['gracz'] . '</a></td>
									<td><a href="index.php?p=4&wyb_gra=' . $ogloszenie['gra'] . '">' . $ogloszenie['gra'] . '</a></td>
									<td><a href="index.php?p=5&wyb_serwer=' . $ogloszenie['serwer'] . '">' . $ogloszenie['serwer'] . '</a></td>
									<td>' . $ogloszenie['ilosc_poszukiwanych'] . '</td></tr>';
						}
						echo '</table><br /><br /><a href="index.php?p=7"><button type="button">Tabela popularności</button></a>';
						echo '<br /><br /><a href="index.php?p=8"><button type="button">Pokaż nieaktywnych graczy</button></a>';
						mysqli_close($baza);
					}
					
					if($wybor == 3) {
						echo '<table border="2">
								<tr>
									<th>NICK</th>
									<th>WIEK</th>
									<th>E-MAIL</th>
									<th>LOKALIZACJA</th>
								</tr>';
						$wyb_gracz = $_GET['wyb_gracz'];
						$baza = mysqli_connect("localhost", "root", "", "find_game_mate");
						mysqli_query($baza, "SET NAMES utf8");
						$gracz = mysqli_fetch_array(mysqli_query($baza, "SELECT nick,wiek,email,lokalizacje.nazwa AS lokalizacja,lokalizacje.id FROM gracze INNER JOIN lokalizacje ON gracze.lokalizacja = lokalizacje.id WHERE nick='" . $wyb_gracz . "'"));
						echo '<tr>
									<td>' . $gracz['nick'] . '</td>
									<td>' . $gracz['wiek'] . '</td>
									<td>' . $gracz['email'] . '</td>
									<td><a href="index.php?p=6&wyb_lokalizacja=' . $gracz['id'] . '">' . $gracz['lokalizacja'] . '</a></td></tr></table><br /><a href="index.php?p=2"><button type="button">Wróć</button></a>';
					}
					
					if($wybor == 4) {
						echo '<table border="2">
								<tr>
									<th>NAZWA</th>
									<th>WYDAWCA</th>
									<th>ROK WYDANIA</th>
								</tr>';
						$wyb_gra = $_GET['wyb_gra'];
						$baza = mysqli_connect("localhost", "root", "", "find_game_mate");
						mysqli_query($baza, "SET NAMES utf8");
						$gra = mysqli_fetch_array(mysqli_query($baza, "SELECT nazwa,wydawca,rok_wydania FROM gry WHERE nazwa='" . $wyb_gra . "'"));
						echo '<tr>
									<td>' . $gra['nazwa'] . '</td>
									<td>' . $gra['wydawca'] . '</td>
									<td>' . $gra['rok_wydania'] . '</td></tr></table><br /><a href="index.php?p=2"><button type="button">Wróć</button></a>';
					}
					
					if($wybor == 5) {
						echo '<table border="2">
								<tr>
									<th>NAZWA</th>
									<th>GRA</th>
									<th>RATE</th>
									<th>LICZBA GRACZY</th>
									<th>LOKALIZACJA</th>
								</tr>';
						$wyb_serwer = $_GET['wyb_serwer'];
						$baza = mysqli_connect("localhost", "root", "", "find_game_mate");
						mysqli_query($baza, "SET NAMES utf8");
						$serwer = mysqli_fetch_array(mysqli_query($baza, "SELECT serwery.nazwa AS nazwa,gra,rate,liczba_graczy,lokalizacje.nazwa AS lokalizacja,lokalizacje.id FROM serwery INNER JOIN lokalizacje ON serwery.lokalizacja = lokalizacje.id WHERE serwery.nazwa='" . $wyb_serwer . "'"));
						echo '<tr>
									<td>' . $serwer['nazwa'] . '</td>
									<td><a href="index.php?p=4&wyb_gra=' . $serwer['gra'] . '">' . $serwer['gra'] . '</a></td>
									<td>' . $serwer['rate'] . 'x</td>
									<td>' . $serwer['liczba_graczy'] . '</td>
									<td><a href="index.php?p=6&wyb_lokalizacja=' . $serwer['id'] . '">' . $serwer['lokalizacja'] . '</a></td></tr></table><br /><a href="index.php?p=2"><button type="button">Wróć</button></a>';
					}
					
					if($wybor == 6) {
						echo '<table border="2">
								<tr>
									<th>NAZWA</th>
									<th>PANSTWO</th>
									<th>KONTYNENT</th>
								</tr>';
						$wyb_lokalizacja = $_GET['wyb_lokalizacja'];
						$baza = mysqli_connect("localhost", "root", "", "find_game_mate");
						mysqli_query($baza, "SET NAMES utf8");
						$lokalizacja = mysqli_fetch_array(mysqli_query($baza, "SELECT * FROM lokalizacje WHERE id=" . $wyb_lokalizacja));
						echo '<tr>
									<td>' . $lokalizacja['nazwa'] . '</td>
									<td>' . $lokalizacja['panstwo'] . '</td>
									<td>' . $lokalizacja['kontynent'] . '</td></tr></table><br /><a href="index.php?p=2"><button type="button">Wróć</button></a>';
					}
					
					if($wybor == 7) {
						echo '<table border="2">
								<tr>
									<th>GRA</th>
									<th>ILOŚĆ OGŁOSZEŃ</th>
								</tr>';
						$baza = mysqli_connect("localhost", "root", "", "find_game_mate");
						mysqli_query($baza, "SET NAMES utf8");
						$popularne = mysqli_query($baza, "SELECT gra,COUNT(gra) AS ilosc FROM ogloszenia INNER JOIN serwery ON ogloszenia.serwer = serwery.nazwa GROUP BY gra");
						for($i = 0; $i < mysqli_num_rows($popularne); $i++) {
							$temp = mysqli_fetch_array($popularne);
							echo '<tr><td><a href="index.php?p=4&wyb_gra=' . $temp['gra'] . '">' . $temp['gra'] . '</a></td>
								<td>' . $temp['ilosc'] . '</td></tr>';
						}
						echo '</table><br /><a href="index.php?p=2"><button type="button">Wróć</button></a>';
					}
					
					if($wybor == 8) {
						echo '<table border="2">
								<tr>
									<th>NICK</th>
									<th>WIEK</th>
									<th>EMAIL</th>
									<th>LOKALIZACJA</th>
								</tr>';
						$baza = mysqli_connect("localhost", "root", "", "find_game_mate");
						mysqli_query($baza, "SET NAMES utf8");
						$nieaktywni = mysqli_query($baza, "SELECT nick,wiek,email,lokalizacje.nazwa AS lokalizacja,lokalizacje.id FROM gracze LEFT OUTER JOIN ogloszenia ON gracze.nick = ogloszenia.gracz INNER JOIN lokalizacje ON gracze.lokalizacja = lokalizacje.id WHERE ogloszenia.id IS NULL");
						for($i = 0; $i < mysqli_num_rows($nieaktywni); $i++) {
							$temp = mysqli_fetch_array($nieaktywni);
							echo '<tr><td>' . $temp['nick'] . '</td>
								<td>' . $temp['wiek'] . '</td>
								<td>' . $temp['email'] . '</td>
								<td><a href="index.php?p=6&wyb_lokalizacja=' . $temp['id'] . '">' . $temp['lokalizacja'] . '</a></td></tr>';
						}
						echo '</table><br /><a href="index.php?p=2"><button type="button">Wróć</button></a>';
					}
				?>
			</div>
			<div id="stopka">
				autor: Kamil Ząber
			</div>
		</div>
	</body>
</html>