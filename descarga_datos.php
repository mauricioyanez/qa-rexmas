<?php
  date_default_timezone_set('America/Santiago');
  ini_set('display_errors', 'On');
  set_time_limit(3600);


  require('consultas.php');
  require('phpSpreadsheet/vendor/autoload.php');

  use PhpOffice\PhpSpreadsheet\IOFactory;

  // $ruta = 'C:\\xampp\\htdocs\\Git\\rexmas\\';
  $ruta = '/var/www/html/qa-generico/qa-rexmas/descargas/';

  echo "Hora de inicio: " . date('Y-m-d H:i:s') . "\n\n";

  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  fwrite($logFile, "\n================= " . date("d/m/Y")." =================") or die("Error escribiendo en el archivo");
  fclose($logFile);

  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Hora de inicio") or die("Error escribiendo en el archivo");
  fclose($logFile);

  echo "Informe descargado se borrara la cookie para matar la sesion\n\n";

  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Informes descargados se borrara la cookie para matar la sesion") or die("Error escribiendo en el archivo");
  fclose($logFile);

  // unlink($cookie);
  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Ingresando procesos") or die("Error escribiendo en el archivo");
  fclose($logFile);

  $Actual = date("d-m-Y");
  $periodoAnterior5 = date("Y-m",strtotime($Actual."-5 month"));
  $periodoAnterior4 = date("Y-m",strtotime($Actual."-4 month"));
  $periodoAnterior3 = date("Y-m",strtotime($Actual."-3 month"));
  $periodoAnterior2 = date("Y-m",strtotime($Actual."-2 month"));
  $periodoAnterior = date("Y-m",strtotime($Actual."-1 month"));
  $periodoActual = date("Y-m",strtotime($Actual."-0 month"));

  $periodos = [];
  $periodos[0] = $periodoAnterior5;
  $periodos[1] = $periodoAnterior4;
  $periodos[2] = $periodoAnterior3;
  $periodos[3] = $periodoAnterior2;
  $periodos[4] = $periodoAnterior;
  $periodos[5] = $periodoActual;

  for($z = 0; $z < count($periodos); $z++){
    //echo $z;
    try {
      // Lectura de archivo de proceso
      $rutaArchivo = $ruta . "consulta_ct09_resultados_x_proceso_" . $periodos[$z] . ".xlsx";
      //echo file_exists($rutaArchivo);
      //echo filesize($rutaArchivo);

      if(file_exists($rutaArchivo) && filesize($rutaArchivo) > 2000){        
        $documento = IOFactory::load($rutaArchivo);
        // echo $rutaArchivo;
        $hojaActual = $documento->getSheet(0);

        $arregloIni = $hojaActual->toArray();
        $arreglo = [];

        for($i = 2; $i < count($arregloIni); $i++){
          $arreglo[] = $arregloIni[$i];
        }

        limpiaPeriodoProceso($periodos[$z]);

        for($j = 0; $j < count($arreglo); $j++){
          if($arreglo[$j][0] == 'Más información disponible si se establece DEBUG=True.'){
            break;
          }

          $ins = ingresaPeriodoProceso($arreglo[$j][0],$arreglo[$j][1],$arreglo[$j][2],$arreglo[$j][3],$arreglo[$j][4],$arreglo[$j][5],$arreglo[$j][6]);
          if($ins == "Ok"){
            echo "Proceso ingresado: " . $arreglo[$j][0] . "\n";
          }
          else{
            echo "Proceso error: " . $arreglo[$j][0] . "\n";
          }
        }
      }
      else{
        echo "Error en archivo Procesos = ". $rutaArchivo;
      }
    } catch (Exception $e) {
        echo "Se produjo un error: " . $e->getMessage() . "\n";
    }
  }

  // Lectura de archivo de centro de costo
  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Ingresando centro costos") or die("Error escribiendo en el archivo");
  fclose($logFile);


  $rutaArchivo = $ruta . "consulta_ct05_centros_de_costo.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arregloIni = $hojaActual->toArray();
  $arreglo = [];

  for($i = 2; $i < count($arregloIni); $i++){
    $arreglo[] = $arregloIni[$i];
  }

  for($j = 0; $j < count($arreglo); $j++){
    $id = $arreglo[$j][0];
    $lista = $arreglo[$j][1];
    $item = $arreglo[$j][2];
    $nombre = str_replace("'","",$arreglo[$j][3]);
    $valora = $arreglo[$j][4];
    $valorb = $arreglo[$j][5];
    $valorc = $arreglo[$j][6];
    $datoAdic = str_replace("'","",$arreglo[$j][7]);
    $sincronizado_externos = $arreglo[$j][8];
    $habilitado = $arreglo[$j][9];
    $reservado = $arreglo[$j][10];
    $hab_int = 0;
    if($habilitado == true){
      $hab_int = 1;
    }

    $sel = datoCentroCostoIngresado($item);

    if($sel[0]['CANTIDAD'] == '0'){
      if($item != ""){
        $ins = ingresaCentroCosto($item,$nombre, $hab_int);
        if($ins == "Ok"){
          echo "Centro de costo ingresado: " . $item . "\n";
        }
        else{
          echo "Centro de costo error: " . $item . "\n";
        }
      }
      else{
        echo "Centro de costo error: " . $item . "\n";
      }
    }
    else{
      $ins = updateCentroCosto($item,$nombre, $hab_int);
      if($ins == "Ok"){
        echo "Centro de costo actualizado: " . $item . "\n";
      }
      else{
        echo "Centro de costo error actuaizacion: " . $item . "\n";
      }
    }
  }

  // Lectura de archivo de Empleados
  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Ingresando personal") or die("Error escribiendo en el archivo");
  fclose($logFile);

  $rutaArchivo = $ruta . "consulta_ct01_empleados.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arregloIni = $hojaActual->toArray();
  $arreglo = [];

  for($i = 2; $i < count($arregloIni); $i++){
    $arreglo[] = $arregloIni[$i];
  }

  for($j = 0; $j < count($arreglo); $j++){
    $DNI = $arreglo[$j][0];
    $NOMBRES = ucwords(strtolower($arreglo[$j][1]));
    $APELLIDOS = ucwords(strtolower($arreglo[$j][2] . " " . $arreglo[$j][3]));
    if($arreglo[$j][4] == "M"){
      $SEXO = "Hombre";
    }
    else{
      $SEXO = "Mujer";
    }
    $FECHA_NACIMIENTO = $arreglo[$j][5];
    $NACIONALIDAD = ucwords(strtolower($arreglo[$j][7]));
    $DOMICILIO = ucwords(strtolower(str_replace("'","",$arreglo[$j][8]) . ", " . str_replace("'","",$arreglo[$j][9]) . ", " . $arreglo[$j][10]));
    $TELEFONO = $arreglo[$j][11];
    $EMAIL = strtolower($arreglo[$j][12]);
    $BANCO = $arreglo[$j][13];
    $BANCO_CUENTA = $arreglo[$j][14];
    $BANCO_FORMA_PAGO = $arreglo[$j][15];
    $IDAFP = $arreglo[$j][18];
    $IDSALUD = $arreglo[$j][23];
    $EMAIL_PERSONAL = strtolower($arreglo[$j][39]);

    $sel = personalExistente($DNI);
    $sel[0]['CANTIDAD'];

    if($sel[0]['CANTIDAD'] == '0'){
      if($DNI != ""){
        $ins = ingresaPersonal($DNI,$NOMBRES,$APELLIDOS,$SEXO,$FECHA_NACIMIENTO,$NACIONALIDAD,$DOMICILIO,$TELEFONO,$EMAIL,$BANCO,$BANCO_CUENTA,$BANCO_FORMA_PAGO,$IDAFP,$IDSALUD,$EMAIL_PERSONAL);

        if($ins == "Ok"){
          echo "Personal ingresado: " . $DNI . "\n";
        }
        else{
          echo "Personal error: " . $DNI . "\n";
        }
      }
      else{
        echo "Personal error: " . $DNI . "\n";
      }
    }
    else{
      if($DNI != ""){
        $ins = actualizaPersonal($DNI,$NOMBRES,$APELLIDOS,$SEXO,$FECHA_NACIMIENTO,$NACIONALIDAD,$DOMICILIO,$TELEFONO,$EMAIL,$BANCO,$BANCO_CUENTA,$BANCO_FORMA_PAGO,$IDAFP,$IDSALUD,$EMAIL_PERSONAL);

        if($ins == "Ok"){
          echo "Personal actualizado: " . $DNI . "\n";
        }
        else{
          echo "Personal error: " . $DNI . "\n";
        }
      }
      else{
        echo "Personal error: " . $DNI . "\n";
      }
    }
  }

  // Lectura de archivo de cargos
  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  $logFile2 = fopen($ruta . "logdetalle.txt", 'a') or die("Error creando archivo");

  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Ingresando cargos") or die("Error escribiendo en el archivo");
  fclose($logFile);

  $rutaArchivo = $ruta . "consulta_ct04_cargos.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arregloIni = $hojaActual->toArray();
  $arreglo = [];

  for($i = 2; $i < count($arregloIni); $i++){
    $arreglo[] = $arregloIni[$i];
  }

  for($j = 0; $j < count($arreglo); $j++){
    $id = $arreglo[$j][0];
    $cargo = ucwords(strtolower($arreglo[$j][1]));

    $sel = cargoExistente($id);
    $sel[0]['CANTIDAD'];

    if($sel[0]['CANTIDAD'] == '0'){
      if($id != ""){
        $ins = ingresaCargo($id,$cargo);

        if($ins == "Ok"){
          echo "Cargo ingresado: " . $id . "\n";
          fwrite($logFile2, "Cargo ingresado: " . $id . "\n") or die("Error escribiendo en el archivo");
        }
        else{
          echo "Cargo error: " . $id . "\n";
          fwrite($logFile2, "Cargo error: " . $id . "\n") or die("Error escribiendo en el archivo");
        }
      }
      else{
        echo "Cargo error: " . $id . "\n";
        fwrite($logFile2, "Cargo error: " . $id . "\n") or die("Error escribiendo en el archivo");

      }
    }
    else{
      echo "Cargo error: " . $id . "\n";
      fwrite($logFile2, "Cargo error: " . $id . "\n") or die("Error escribiendo en el archivo");

    }
  }
  fclose($logFile2);


  // Lectura de archivo de centro de catalogo
  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Ingresando catalogo") or die("Error escribiendo en el archivo");
  fclose($logFile);

  $rutaArchivo = $ruta . "consulta_ct08_catalogos.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arregloIni = $hojaActual->toArray();
  $arreglo = [];

  for($i = 2; $i < count($arregloIni); $i++){
    $arreglo[] = $arregloIni[$i];
  }

  $JEAS = [];
  $JEAS[1] = "J";
  $JEAS[2] = 'E';
  $JEAS[3] = 'A';
  $JEAS[4] = 'S';
  $JEAS[6] = 'G';

  limpiarFeriados();

  for($j = 0; $j < count($arreglo); $j++){
    if($arreglo[$j][1] == "lta10"){
      // echo $arreglo[$j][1] . "\n";
      // echo $arreglo[$j][2] . "\n";
      // echo $arreglo[$j][3] . "\n";
      // echo trim(explode("-",explode(")",$arreglo[$j][7])[1])[0]) . "\n";
      // echo $JEAS[trim(explode("-",explode(")",$arreglo[$j][7])[1])[0])] . "\n\n";

      $codigo = $arreglo[$j][2];
      $nombre = $arreglo[$j][3];
      if($arreglo[$j][7] !== ""){
        $clasificacion = $JEAS[trim(explode("-",explode(")",$arreglo[$j][7])[1])[0])];
      }
      else{
        $clasificacion = "";
      }
      $habilitado = trim($arreglo[$j][9]);

      $sel = datosCatalogoIngresado($codigo);
      $sel[0]['CANTIDAD'];

      if($sel[0]['CANTIDAD'] == '0'){
        if($codigo != ""){
          $ins = ingresaCatalogo($codigo,$nombre,$clasificacion, $habilitado);
          if($ins == "Ok"){
            echo "Catalogo ingresado: " . $codigo . "\n";
          }
          else{
            echo "Catalogo error: " . $codigo . "\n";
          }
        }
        else{
          echo "Catalogo error: " . $codigo . "\n";
        }
      }
      else{
        echo "Catalogo error: " . $codigo . "\n";;
      }
    }

    if($arreglo[$j][1] == "lta9"){
      $codigo = $arreglo[$j][2];
      $nombre = $arreglo[$j][3];
      $detalle = trim($arreglo[$j][7]);
      $habilitado = trim($arreglo[$j][9]);

      $sel = datosCatalogoReferencia1($codigo);
      $sel[0]['CANTIDAD'];

      if($sel[0]['CANTIDAD'] == '0'){
        if($codigo != ""){
          $ins = ingresaCatalogoReferencia1($codigo,$nombre,$detalle,$habilitado);
          if($ins == "Ok"){
            echo "Referencia1 ingresado: " . $codigo . "\n";
          }
          else{
            echo "Referencia1 error: " . $codigo . "\n";
          }
        }
        else{
          echo "Referencia1 error: " . $codigo . "\n";
        }
      }
      else{
        echo "Referencia1 error: " . $codigo . "\n";;
      }
    }

    if($arreglo[$j][1] == "lta4"){
      $codigo = $arreglo[$j][2];
      $nombre = $arreglo[$j][3];
      $detalle = trim($arreglo[$j][7]);
      $habilitado = trim($arreglo[$j][9]);

      $sel = datosCatalogoReferencia2($codigo);
      $sel[0]['CANTIDAD'];

      if($sel[0]['CANTIDAD'] == '0'){
        if($codigo != ""){
          $ins = ingresaCatalogoReferencia2($codigo,$nombre,$detalle,$habilitado);
          if($ins == "Ok"){
            echo "Referencia2 ingresado: " . $codigo . "\n";
          }
          else{
            echo "Referencia2 error: " . $codigo . "\n";
          }
        }
        else{
          echo "Referencia2 error: " . $codigo . "\n";
        }
      }
      else{
        echo "Referencia2 error: " . $codigo . "\n";;
      }
    }

    if($arreglo[$j][1] == "feriados"){
      $codigo = $arreglo[$j][2];
      $dia = substr($codigo, 0, 2);
      $mes = substr($codigo, -2);
      $ano = date("Y");
      $fecha = $ano . '-' . $mes . '-' . $dia;
      $habilitado = trim($arreglo[$j][9]);

      if($codigo != ""){
        $ins = ingresaCatalogoFeriado($fecha,'feriados',$habilitado);
        if($ins == "Ok"){
          echo "Fecha ingresada: " . $codigo . "\n";
        }
        else{
          echo "Fecha error: " . $codigo . "\n";
        }
      }
      else{
        echo "Fecha error: " . $codigo . "\n";
      }
    }

    if($arreglo[$j][1] == "feriadosMovi"){
      $codigo = $arreglo[$j][2];
      $dia = substr($codigo, 0, 2);
      $mes = substr($codigo, 2, 2);
      $ano = substr($codigo, 4, 2);
      $fecha = $ano . '-' . $mes . '-' . $dia;
      $habilitado = trim($arreglo[$j][9]);

      if($codigo != ""){
        $ins = ingresaCatalogoFeriado($fecha,'feriadosMovi',$habilitado);
        if($ins == "Ok"){
          echo "Fecha Movi ingresada: " . $codigo . "\n";
        }
        else{
          echo "Fecha Movi error: " . $codigo . "\n";
        }
      }
      else{
        echo "Fecha Movi error: " . $codigo . "\n";
      }
    }
  }

  // Lectura de archivo de contratos
  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  $logFile2 = fopen($ruta . "logdetalle.txt", 'a') or die("Error creando archivo");

  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Ingresando contratos") or die("Error escribiendo en el archivo");
  fclose($logFile);

  $rutaArchivo = $ruta . "consulta_ct02_contratos.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arregloIni = $hojaActual->toArray();
  $arreglo = [];

  for($i = 2; $i < count($arregloIni); $i++){
    $arreglo[] = $arregloIni[$i];
  }

  limpiarDatosContrato();

  for($j = 0; $j < count($arreglo); $j++){
    $dni = $arreglo[$j][1];
    $idempresa = $arreglo[$j][2];
    $idcentrocosto = $arreglo[$j][12];
    $idcargo = $arreglo[$j][82];
    $codigoCargoGenerico = $arreglo[$j][44];
    $codigoRef1 = $arreglo[$j][43];
    $codigoRef2 = $arreglo[$j][31];
    // $fechaTermino = convertDate($arreglo[$j][8]);
    // $fechaInicio = convertDate($arreglo[$j][7]);
    $fechaTermino = $arreglo[$j][8];
    $fechaInicio = $arreglo[$j][7];
    $causalTermino = $arreglo[$j][9];
    $clasificacionContrato = $arreglo[$j][3];

    $date1 = new DateTime();
    $date2 = new DateTime($fechaTermino);
    $diff = $date2->diff($date1);

    $ins = ingresaDatosContrato($dni,$fechaTermino,$causalTermino,$codigoCargoGenerico,$codigoRef1,$codigoRef2,$idcargo,$idcentrocosto,$fechaInicio,$clasificacionContrato,$idempresa);

    if($ins == "Ok"){
      echo "Datos contrato cargados: " . $dni . " - " . $fechaInicio . "\n";
      fwrite($logFile2, "Datos contrato cargados: " . $dni . " - " . $fechaInicio . "\n") or die("Error escribiendo en el archivo");

    }
    else{
      echo "Datos contrato no cargados: " . $dni . " - " . $fechaInicio . "\n";
      fwrite($logFile2, "Datos contrato no cargados: " . $dni . " - " . $fechaInicio . "\n") or die("Error escribiendo en el archivo");

    }
  }
  fclose($logFile2);

  ingresaDesvinculacion();
  actualizaCargoGenericoPersonal();
  actualizaACT();


  // Lectura de archivo de vacaciones
  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  $logFile2 = fopen($ruta . "logdetalle.txt", 'a') or die("Error creando archivo");

  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Ingresando vacaciones") or die("Error escribiendo en el archivo");
  fclose($logFile);

  $rutaArchivo = $ruta . "consulta_ct06_vacaciones.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arregloIni = $hojaActual->toArray();

  for($i = 2; $i < count($arregloIni); $i++){
    $fini = $arregloIni[$i][8];

    $date1 = new DateTime();
    $date2 = new DateTime($fini);
    $diff = $date1->diff($date2);

    if($diff->days <= 370){
      if($arregloIni[$i][8] != ""){
        $idRexmas = $arregloIni[$i][0];
        $dni = $arregloIni[$i][1];
        $fini = $arregloIni[$i][8];
        $fter = $arregloIni[$i][9];

        $ins = ingresaVacacionRexmas($dni,$fini,$fter,$idRexmas);

        if($ins == "Ok"){
          echo "Vacación ingresada: " . $dni . " | " . $fini . " - " . $fter . "\n";
          fwrite($logFile2, "Vacación ingresada: " . $dni . " | " . $fini . " - " . $fter . "\n") or die("Error escribiendo en el archivo");

        }
        else{
          echo "Vacación error: " . $dni . " | " . $fini . " - " . $fter . "\n";
          fwrite($logFile2, "Vacación error: " . $dni . " | " . $fini . " - " . $fter . "\n") or die("Error escribiendo en el archivo");

        }
      }
      else{
        echo "Vacio: " . $arregloIni[$i][8] . "\n";
        fwrite($logFile2, "Vacio: " . $arregloIni[$i][8] . "\n") or die("Error escribiendo en el archivo");

      }
    }
    else{
      echo "No aplica \n";
    }
  }
fclose($logFile2);

  // Lectura de archivo de licencias
  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  $logFile2 = fopen($ruta . "logdetalle.txt", 'a') or die("Error creando archivo");

  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Ingresando licencias") or die("Error escribiendo en el archivo");
  fclose($logFile);

  $rutaArchivo = $ruta . "consulta_ct07_licencias.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arregloIni = $hojaActual->toArray();

  for($i = 2; $i < count($arregloIni); $i++){
    $fini = explode("-",substr($arregloIni[$i][19],0,10));
    $fini = $fini[2] . "-" . $fini[1] . "-" . $fini[0];

    $date1 = new DateTime();
    $date2 = new DateTime($fini);
    $diff = $date1->diff($date2);

    if($diff->days <= 370){
      $idRexmas = $arregloIni[$i][0];
      $dni = $arregloIni[$i][4];
      $tipoLic = $arregloIni[$i][7];
      $fini = explode("-",substr($arregloIni[$i][19],0,10));
      $fini = $fini[2] . "-" . $fini[1] . "-" . $fini[0];
      $fter = explode("-",substr($arregloIni[$i][19],10,10));
      $fter = $fter[2] . "-" . $fter[1] . "-" . $fter[0];

      $ins = ingresaLicenciaRexmas($dni,$fini,$fter,$tipoLic,$idRexmas);
      if($ins == "Ok"){
        echo "Licencia ingresada: " . $dni . " | " . $fini . " - " . $fter . "\n";
        fwrite($logFile2, "Licencia ingresada: " . $dni . " | " . $fini . " - " . $fter . "\n") or die("Error escribiendo en el archivo");

      }
      else{
        echo "Licencia error: " . $dni . " | " . $fini . " - " . $fter . "\n";
        fwrite($logFile2, "Licencia error: " . $dni . " | " . $fini . " - " . $fter . "\n") or die("Error escribiendo en el archivo");

      }
    }
    else{
      echo "No aplica \n";
      fwrite($logFile2, "No aplica :" . $dni . " | " . $fini . " - " . $fter . "\n") or die("Error escribiendo en el archivo");
        }
  }
  fclose($logFile2);


  // Lectura de archivo de permisos administrativos
  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  $logFile2 = fopen($ruta . "logdetalle.txt", 'a') or die("Error creando archivo");
  
  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Ingresando permisos administrativos") or die("Error escribiendo en el archivo");
  fclose($logFile);

  $rutaArchivo = $ruta . "consulta_ct010_permisos_administrativos.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arregloIni = $hojaActual->toArray();

  for($i = 2; $i < count($arregloIni); $i++){
    $fini = $arregloIni[$i][2];

    $date1 = new DateTime();
    $date2 = new DateTime($fini);
    $diff = $date1->diff($date2);

    if($diff->days <= 370){
      if($arregloIni[$i][2] != ""){
        $dni = $arregloIni[$i][0];
        $fini = $arregloIni[$i][2];
        $fter = $arregloIni[$i][3];

        $ins = ingresaPermisoAdministrativoRexmas($dni,$fini,$fter);

        if($ins == "Ok"){
          echo "Permiso administrativo ingresado: " . $dni . " | " . $fini . " - " . $fter . "\n";
          fwrite($logFile2, "Permiso administrativo ingresado: " . $dni . " | " . $fini . " - " . $fter . PHP_EOL);
        }
        else{
          echo "Permiso administrativo error: " . $dni . " | " . $fini . " - " . $fter . "\n";
          fwrite($logFile2, "Permiso administrativo error: " . $dni . " | " . $fini . " - " . $fter  . PHP_EOL);
        }
      }
      else{
        echo "Vacio: " . $arregloIni[$i][2] . "\n";
        fwrite($logFile2, "Vacio: " . $arregloIni[$i][2] . PHP_EOL);
      }
    }
    else{
      echo "No aplica \n";
      fwrite($logFile2, "No aplica: " . $dni . " | " . $fini . " - " . $fter  . PHP_EOL);
    }
  }
  fclose($logFile2);

  // Lectura de archivo de permisos espera licencia
  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Ingresando permisos espera licencia") or die("Error escribiendo en el archivo");
  fclose($logFile);

  $rutaArchivo = $ruta . "consulta_ct12_permisos_en_espera_de_licencia.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arregloIni = $hojaActual->toArray();

  for($i = 2; $i < count($arregloIni); $i++){
    $fini = $arregloIni[$i][9];

    $date1 = new DateTime();
    $date2 = new DateTime($fini);
    $diff = $date1->diff($date2);

    if($diff->days <= 370){
      if($arregloIni[$i][9] != ""){
        $dni = $arregloIni[$i][3];
        $fini = $arregloIni[$i][9];
        $fter = $arregloIni[$i][10];

        $ins = ingresaPermisoEsperaLicenciaRexmas($dni,$fini,$fter);

        if($ins == "Ok"){
          echo "Permiso espera licencia ingresado: " . $dni . " | " . $fini . " - " . $fter . "\n";
        }
        else{
          echo "Permiso espera licencia error: " . $dni . " | " . $fini . " - " . $fter . "\n";
        }
      }
      else{
        echo "Vacio: " . $arregloIni[$i][9] . "\n";
      }
    }
    else{
      echo "No aplica \n";
    }
  }

  eliminarVacLicBorradasRexmas();

  unlink($ruta . "consulta_ct01_empleados.xlsx");
  unlink($ruta . "consulta_ct02_contratos.xlsx");
  unlink($ruta . "consulta_ct03_empresas.xlsx");
  unlink($ruta . "consulta_ct04_cargos.xlsx");
  unlink($ruta . "consulta_ct05_centros_de_costo.xlsx");
  unlink($ruta . "consulta_ct06_vacaciones.xlsx");
  unlink($ruta . "consulta_ct07_licencias.xlsx");
  unlink($ruta . "consulta_ct08_catalogos.xlsx");
  unlink($ruta . "consulta_ct010_permisos_administrativos.xlsx");
  unlink($ruta . "consulta_ct12_permisos_en_espera_de_licencia.xlsx");
  unlink($ruta . "consulta_ct09_resultados_x_proceso_" . $periodoAnterior5 . ".xlsx");
  unlink($ruta . "consulta_ct09_resultados_x_proceso_" . $periodoAnterior4 . ".xlsx");
  unlink($ruta . "consulta_ct09_resultados_x_proceso_" . $periodoAnterior3 . ".xlsx");
  unlink($ruta . "consulta_ct09_resultados_x_proceso_" . $periodoAnterior2 . ".xlsx");
  unlink($ruta . "consulta_ct09_resultados_x_proceso_" . $periodoAnterior . ".xlsx");
  unlink($ruta . "consulta_ct09_resultados_x_proceso_" . $periodoActual . ".xlsx");
  echo "Hora de termino: " . date('Y-m-d H:i:s') . "\n";

  $logFile = fopen($ruta . "log.txt", 'a') or die("Error creando archivo");
  fwrite($logFile, "\n" . date("d/m/Y H:i:s")." - Hora de termino") or die("Error escribiendo en el archivo");
  fclose($logFile);

  //Funciones
  function convertDate($dateValue) {
    $unixDate = ($dateValue - 25569) * 86400;
    return gmdate("Y-m-d", $unixDate);
  }

  // echo count($arreglo) . "\n";
?>
