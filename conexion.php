<?php
	session_start();

	// Conectando a la base de datos
	function conectar(){
		//QA
		// $user = "AppConnect";
		// $pass = 'TCw4etVPjEcqTRkGOqG8';
		// $db = "SOLO_VERDE";
		// $host = "ls-c325378e153b6215b55eceeb3bd07a3f8ae76ca2.chtrgv4wtigr.us-east-1.rds.amazonaws.com";

		// Producción
		$user = "AppConnect";
		$pass = "TCw4etVPjEcqTRkGOqG8";
		$db = "QA-SOLOVERDE";
		$host = "10.0.0.8";

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
				//$mysqli->set_sql_mode("ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE");
				return $mysqli;
			}
		}
		catch(Exception $e){
			return "No conectado";
		}
	}
?>
