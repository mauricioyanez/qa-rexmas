<?php
	session_start();

	// Conectando a la base de datos
	function conectar(){
		//Producción
		$user = "AppConnect";
		$pass = 'TCw4etVPjEcqTRkGOqG8';
		$db = "SOLO_VERDE";
		$host = "ls-c325378e153b6215b55eceeb3bd07a3f8ae76ca2.chtrgv4wtigr.us-east-1.rds.amazonaws.com";

		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			$mysqli = new mysqli($host, $user, $pass, $db, 3306);
			if ($mysqli->connect_errno) {
		   		echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
				return "No conectado";
			}
			else{
				$mysqli->set_charset("utf8");
				return $mysqli;
			}
		}
		catch(Exception $e){
			return "No conectado";
		}
	}
?>
