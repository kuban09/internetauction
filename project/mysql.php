<?php

	$connection = @mysql_connect("localhost", "root", "") or die("Brak połączenia z serwerem MySQL.<br />Błąd: ".mysql_error()); 
	$db = @mysql_select_db("zpi_project", $connection)  or die("Nie mogę połączyć się z bazą danych<br />Błąd: ".mysql_error()); 
	mysql_query ('SET NAMES utf8');
?>