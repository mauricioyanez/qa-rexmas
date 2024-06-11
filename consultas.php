<?php
require('conexion.php');

	function datoCentroCostoIngresado($item){
		$con = conectar();
		if($con != 'No conectado'){
			$sql = "SELECT COUNT(*) 'CANTIDAD'
	            FROM ESTRUCTURA_OPERACION
	            WHERE DEFINICION = '{$item}'";
			if ($row = $con->query($sql)) {
				while($array = $row->fetch_array(MYSQLI_BOTH)){
					$return[] = $array;
				}
				return $return;
			}
			else{
				return "Error";
			}
		}
		else{
			return "Error";
		}
	}

	function ingresaCentroCosto($item,$nombre, $hab_int){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO ESTRUCTURA_OPERACION(DEFINICION, NOMENCLATURA, FECHA, USUARIO, HABILITADO)
	            VALUES('{$item}','{$nombre}', NOW(), 'Automata', '{$hab_int}')";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function personalExistente($DNI){
		$con = conectar();
		if($con != 'No conectado'){
			$sql = "SELECT COUNT(*) 'CANTIDAD'
							FROM PERSONAL
							WHERE DNI = '{$DNI}'";
			if ($row = $con->query($sql)) {
				while($array = $row->fetch_array(MYSQLI_BOTH)){
					$return[] = $array;
				}
				return $return;
			}
			else{
				return "Error";
			}
		}
		else{
			return "Error";
		}
	}

	function ingresaPersonal($DNI,$NOMBRES,$APELLIDOS,$SEXO,$FECHA_NACIMIENTO,$NACIONALIDAD,$DOMICILIO,$TELEFONO,$EMAIL,$BANCO,$BANCO_CUENTA,$BANCO_FORMA_PAGO,$IDAFP,$IDSALUD,$EMAIL_PERSONAL){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO PERSONAL
							(
								DNI,
								NOMBRES,
								APELLIDOS,
								SEXO,
								FECHA_NACIMIENTO,
								NACIONALIDAD,
								DOMICILIO,
								TELEFONO,
								EMAIL,
								IDBANCO,
								BANCO_CUENTA,
								BANCO_FORMA_PAGO,
								IDAFP,
								IDSALUD,
								EMAIL_PERSONAL,
								FECHAACTUALIZACION
							)
							VALUES
							(
								'{$DNI}',
								'{$NOMBRES}',
								'{$APELLIDOS}',
								'{$SEXO}',
								'{$FECHA_NACIMIENTO}',
								(SELECT NACIONALIDAD
								FROM NACIONALIDAD
								WHERE PAIS LIKE '%{$NACIONALIDAD}%'),
								'{$DOMICILIO}',
								'{$TELEFONO}',
								'{$EMAIL}',
								(SELECT IDBANCO
								FROM BANCO
								WHERE BANCO_REX LIKE '%{$BANCO}%'),
								'{$BANCO_CUENTA}',
								'{$BANCO_FORMA_PAGO}',
								(SELECT IDAFP
								FROM AFP
								WHERE AFP_REX LIKE '%{$IDAFP}%'),
								(SELECT IDSALUD
								FROM SALUD
								WHERE SALUD_REX LIKE '%{$IDSALUD}%'),
								'{$EMAIL_PERSONAL}',
								NOW()
							)";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function actualizaPersonal($DNI,$NOMBRES,$APELLIDOS,$SEXO,$FECHA_NACIMIENTO,$NACIONALIDAD,$DOMICILIO,$TELEFONO,$EMAIL,$BANCO,$BANCO_CUENTA,$BANCO_FORMA_PAGO,$IDAFP,$IDSALUD,$EMAIL_PERSONAL){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "UPDATE PERSONAL
							SET
								NOMBRES = '{$NOMBRES}',
								APELLIDOS = '{$APELLIDOS}',
								SEXO = '{$SEXO}',
								FECHA_NACIMIENTO = '{$FECHA_NACIMIENTO}',
								NACIONALIDAD = (SELECT NACIONALIDAD
								FROM NACIONALIDAD
								WHERE PAIS LIKE '%{$NACIONALIDAD}%'),
								DOMICILIO = '{$DOMICILIO}',
								TELEFONO = '{$TELEFONO}',
								EMAIL = '{$EMAIL}',
								IDBANCO = (SELECT IDBANCO
								FROM BANCO
								WHERE BANCO_REX LIKE '%{$BANCO}%'),
								BANCO_CUENTA = '{$BANCO_CUENTA}',
								BANCO_FORMA_PAGO = '{$BANCO_FORMA_PAGO}',
								IDAFP = (SELECT IDAFP
								FROM AFP
								WHERE AFP_REX LIKE '%{$IDAFP}%'),
								IDSALUD = (SELECT IDSALUD
								FROM SALUD
								WHERE SALUD_REX LIKE '%{$IDSALUD}%'),
								EMAIL_PERSONAL = '{$EMAIL_PERSONAL}',
								FECHAACTUALIZACION = NOW()
							WHERE DNI = '{$DNI}'";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function cargoExistente($id){
		$con = conectar();
		if($con != 'No conectado'){
			$sql = "SELECT COUNT(*) 'CANTIDAD'
							FROM CARGO_LIQUIDACION
							WHERE IDREXMAS = '{$id}'";
			if ($row = $con->query($sql)) {
				while($array = $row->fetch_array(MYSQLI_BOTH)){
					$return[] = $array;
				}
				return $return;
			}
			else{
				return "Error";
			}
		}
		else{
			return "Error";
		}
	}

	function ingresaCargo($id,$cargo){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO CARGO_LIQUIDACION
							(
								IDREXMAS,
								CARGO
							)
							VALUES
							(
								'{$id}',
								'{$cargo}'
							)";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function actualizaCargoPersonal($dni,$idcargo){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "UPDATE PERSONAL
							SET CARGO =
							(
								SELECT CARGO
								FROM CARGO_LIQUIDACION
								WHERE IDREXMAS = '{$idcargo}'
							)
							WHERE DNI = '{$dni}'";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function ACTExistente($dni){
		$con = conectar();
		if($con != 'No conectado'){
			$sql = "SELECT COUNT(*) 'CANTIDAD'
							FROM ACT
							WHERE IDPERSONAL =
							(
								SELECT IDPERSONAL
								FROM PERSONAL
								WHERE DNI = '{$dni}'
							)";
			if ($row = $con->query($sql)) {
				while($array = $row->fetch_array(MYSQLI_BOTH)){
					$return[] = $array;
				}
				return $return;
			}
			else{
				return "Error";
			}
		}
		else{
			return "Error";
		}
	}

	function ingresaACT($dni,$idcentrocosto){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO ACT
							(
								IDPERSONAL,
								IDESTRUCTURA_OPERACION,
								FECHA,
								USUARIO
							)
							VALUES
							(
								(SELECT IDPERSONAL
								FROM PERSONAL
								WHERE DNI  = '{$dni}'),
								(SELECT IDESTRUCTURA_OPERACION
								FROM ESTRUCTURA_OPERACION
								WHERE DEFINICION = '{$idcentrocosto}'),
								NOW(),
								'AUTOMATA'
							)";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function actualizaACT(){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "CALL ACTUALIZAR_CENTRO_COSTO();";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function datosCatalogoIngresado($codigo){
		$con = conectar();
		if($con != 'No conectado'){
			$sql = "SELECT COUNT(*) 'CANTIDAD'
							FROM CARGO_GENERICO_UNIFICADO
							WHERE CODIGO = '{$codigo}'";
			if ($row = $con->query($sql)) {
				while($array = $row->fetch_array(MYSQLI_BOTH)){
					$return[] = $array;
				}
				return $return;
			}
			else{
				return "Error";
			}
		}
		else{
			return "Error";
		}
	}

	function ingresaCatalogo($codigo,$nombre,$clasificacion,$habilitado){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO CARGO_GENERICO_UNIFICADO
							(
								NOMBRE,
								IDCLASIFICACION,
								CODIGO,
								HABILITADO
							)
							VALUES
							(
								'{$nombre}',
								(SELECT IDCLASIFICACION
								FROM CLASIFICACION
								WHERE NOMBRE = '{$clasificacion}'),
								'{$codigo}',
								'{$habilitado}'
							)";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function actualizaCargoGenericoPersonal(){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "CALL ACTUALIZAR_PERSONAL();";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function datosCatalogoReferencia1($codigo){
		$con = conectar();
		if($con != 'No conectado'){
			$sql = "SELECT COUNT(*) 'CANTIDAD'
							FROM REFERENCIA1
							WHERE CODIGO = '{$codigo}'";
			if ($row = $con->query($sql)) {
				while($array = $row->fetch_array(MYSQLI_BOTH)){
					$return[] = $array;
				}
				return $return;
			}
			else{
				return "Error";
			}
		}
		else{
			return "Error";
		}
	}

	function ingresaCatalogoReferencia1($codigo,$nombre,$detalle,$habilitado){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO REFERENCIA1
							(
								CODIGO,
								NOMBRE,
								DETALLE,
								HABILITADO
							)
							VALUES
							(
								'{$codigo}',
								'{$nombre}',
								'{$detalle}',
								'{$habilitado}'
							)";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function datosCatalogoReferencia2($codigo){
		$con = conectar();
		if($con != 'No conectado'){
			$sql = "SELECT COUNT(*) 'CANTIDAD'
							FROM REFERENCIA2
							WHERE CODIGO = '{$codigo}'";
			if ($row = $con->query($sql)) {
				while($array = $row->fetch_array(MYSQLI_BOTH)){
					$return[] = $array;
				}
				return $return;
			}
			else{
				return "Error";
			}
		}
		else{
			return "Error";
		}
	}

	function ingresaCatalogoReferencia2($codigo,$nombre,$detalle,$habilitado){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO REFERENCIA2
							(
								CODIGO,
								NOMBRE,
								DETALLE,
								HABILITADO
							)
							VALUES
							(
								'{$codigo}',
								'{$nombre}',
								'{$detalle}',
								'{$habilitado}'
							)";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function ingresaVacacionRexmas($dni,$fini,$fter,$idRexmas){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "CALL INSERTAR_VACACIONES_RANGO('{$dni}','{$fini}','{$fter}','{$idRexmas}')";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function ingresaLicenciaRexmas($dni,$fini,$fter,$tipoLic,$idRexmas){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "CALL INSERTAR_LICENCIA_RANGO('{$dni}','{$fini}','{$fter}','{$tipoLic}','{$idRexmas}')";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      return $con->error;
	      $con->query("ROLLBACK");
	      // return $sql;
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function ingresaCatalogoFiniquito($codigo,$nombre,$detalle,$habilitado){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO CATALOGO_FINIQUITO
							(
								CODIGO,
								NOMBRE,
								DETALLE,
								HABILITADO
							)
							VALUES
							(
								'{$codigo}',
								'{$nombre}',
								'{$detalle}',
								'{$habilitado}'
							)";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function datosCatalogoFiniquito($codigo){
		$con = conectar();
		if($con != 'No conectado'){
			$sql = "SELECT COUNT(*) 'CANTIDAD'
							FROM CATALOGO_FINIQUITO
							WHERE CODIGO = '{$codigo}'";
			if ($row = $con->query($sql)) {
				while($array = $row->fetch_array(MYSQLI_BOTH)){
					$return[] = $array;
				}
				return $return;
			}
			else{
				return "Error";
			}
		}
		else{
			return "Error";
		}
	}

	function ingresaDesvinculacion(){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "CALL INSERTAR_DESVINCULACION();";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      return $con->error;
	      $con->query("ROLLBACK");
	      // return $sql;
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function limpiaPeriodoProceso($periodo){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "DELETE FROM PROCESOS_PERIODO
							WHERE FECHAPROC = '{$periodo}'";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      return $con->error;
	      $con->query("ROLLBACK");
	      // return $sql;
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function ingresaPeriodoProceso($fechaProc,$empresa,$cargo,$centroCost,$contrato,$tipoContrato,$empleado){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO PROCESOS_PERIODO(
								FECHAPROC,
								EMPRESA,
								CARGO,
								CECO,
								CONTRATO,
								TIPOCONTRATO,
								EMPLEADO
							)
							VALUES
							(
								'{$fechaProc}',
								'{$empresa}',
								'{$cargo}',
								'{$centroCost}',
								'{$contrato}',
								'{$tipoContrato}',
								'{$empleado}'
							)";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      return $con->error;
	      $con->query("ROLLBACK");
	      // return $sql;
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function ingresaDatosContrato($dni,$fechaTermino,$causalTermino,$codigoCargoGenerico,$codigoRef1,$codigoRef2,$idcargo,$idcentrocosto,$fechaInicio,$clasificacionContrato,$idempresa){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO CONTRATO_TEMPORAL
							(
								DNI,
								FECHA_TERMINO,
								CAUSAL_TERMINO,
								CODIGO_CARGO_GENERICO,
								CODIGO_REF1,
								CODIGO_REF2,
								IDCARGO,
								IDCENTRO_COSTO,
								FECHA_INICIO,
								CLASIFICACION_CONTRATO,
								IDEMPRESA
							)
							VALUES
							(
								'{$dni}',
								'{$fechaTermino}',
								'{$causalTermino}',
								'{$codigoCargoGenerico}',
								'{$codigoRef1}',
								'{$codigoRef2}',
								'{$idcargo}',
								'{$idcentrocosto}',
								'{$fechaInicio}',
								'{$clasificacionContrato}',
								'{$idempresa}'
							)";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function limpiarDatosContrato(){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "DELETE FROM CONTRATO_TEMPORAL";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      return $con->error;
	      $con->query("ROLLBACK");
	      // return $sql;
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function updateCentroCosto($item,$nombre, $hab_int){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "UPDATE ESTRUCTURA_OPERACION
							SET NOMENCLATURA = '{$nombre}',
							HABILITADO = '{$hab_int}'
							WHERE DEFINICION = '{$item}'";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function eliminarVacLicBorradasRexmas(){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "CALL ELIMINA_VAC_LIC_BORRADAS_REXMAS();";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      return $con->error;
	      $con->query("ROLLBACK");
	      // return $sql;
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function ingresaCatalogoFeriado($fecha,$tipo,$habilitado){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "INSERT INTO FERIADOS_REXMAS
							(
								FECHA,
								TIPO,
								ESTADO
							)
							VALUES
							(
								'{$fecha}',
								'{$tipo}',
								'{$habilitado}'
							)";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function limpiarFeriados(){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "DELETE FROM FERIADOS_REXMAS";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      return $con->error;
	      $con->query("ROLLBACK");
	      // return $sql;
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function ingresaPermisoAdministrativoRexmas($dni,$fini,$fter){
	  $con = conectar();
	  $con->query("SET @@SESSION.sql_mode ='ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE';");

	  //$con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "CALL INSERTAR_PERMISO_ADMINISTRATIVO_RANGO('{$dni}','{$fini}','{$fter}')";
	    if ($con->query($sql)) {
	      //$con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      // $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    //$con->query("ROLLBACK");
	    return "Error";
	  }
	}

	function ingresaPermisoEsperaLicenciaRexmas($dni,$fini,$fter){
	  $con = conectar();
	  $con->query("START TRANSACTION");
	  if($con != 'No conectado'){
	    $sql = "CALL INSERTAR_PERMISO_ESPERA_LICENCIA_RANGO('{$dni}','{$fini}','{$fter}')";
	    if ($con->query($sql)) {
	      $con->query("COMMIT");
	      return "Ok";
	    }
	    else{
	      // return $con->error;
	      $con->query("ROLLBACK");
	      return "Error";
	      // return $sql;
	    }
	  }
	  else{
	    $con->query("ROLLBACK");
	    return "Error";
	  }
	}
?>
