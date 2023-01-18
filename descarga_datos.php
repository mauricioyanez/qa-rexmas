<?php
  date_default_timezone_set('America/Santiago');
  ini_set('display_errors', 'On');
  set_time_limit(3600);

  require('consultas.php');
  require('phpSpreadsheet/vendor/autoload.php');

  use PhpOffice\PhpSpreadsheet\IOFactory;

  echo "Hora de inicio: " . date('Y-m-d H:i:s') . "\n\n";

  echo "Seteando estructura y cookie\n";

  // $ruta = 'C:\\xampp\\htdocs\\Git\\rexmas\\';
  $ruta = '/var/www/html/generico/rexmas/';
  $cookie = $ruta . 'descargas\\cookieRR.txt';

  echo "Abriendo primer sitio\n";

  // Pagina 1
  $ch = curl_init('https://soloverde.rexmas.cl/remuneraciones/es-CL/login');
  curl_setopt ($ch, CURLOPT_POST, false);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)");

  $respuesta = curl_exec($ch);

  curl_close($ch);

  sleep(5);

  $linea = "";

  echo "Obteniendo token\n";

  $fp = fopen($ruta . 'descargas\\cookieRR.txt', "r");
  while (!feof($fp)){
      $linea = fgets($fp);
      if(strpos($linea, "csrftoken"))
      {
          // echo $linea;
          break;
      }
  }
  fclose($fp);

  $array = explode("csrftoken",$linea);

  $csrftoken = trim($array[1]);

  echo "Token1: " . $csrftoken . "\n";

  echo "Logueandonos en sistema\n";

  // Pagina 2
  $request = [];

  // $request[] = 'POST /remuneraciones/es-CL/login HTTP/1.1';
  // $request[] = 'Host: soloverde.rexmas.cl';
  // $request[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0';
  // $request[] = 'Accept: application/json, text/plain, */*';
  // $request[] = 'Accept-Language: es-CL,es;q=0.8,en-US;q=0.5,en;q=0.3';
  // $request[] = 'Accept-Encoding: gzip, deflate, br';
  $request[] = 'Referer: https://soloverde.rexmas.cl/remuneraciones/es-CL/login';
  $request[] = 'Content-Type: application/json';
  $request[] = 'X-XSRF-TOKEN: ' . $csrftoken;
  $request[] = 'X-CSRFTOKEN: ' . $csrftoken;
  // $request[] = 'Content-Length: 46';
  // $request[] = 'Origin: https://soloverde.rexmas.cl';
  // $request[] = 'DNT: 1';
  // $request[] = 'Connection: keep-alive';
  // $request[] = 'Cookie: csrftoken=' . $csrftoken;
  // $request[] = 'Sec-Fetch-Dest: empty';
  // $request[] = 'Sec-Fetch-Mode: cors';
  // $request[] = 'Sec-Fetch-Site: same-origin';
  // $request[] = 'Pragma: no-cache';
  // $request[] = 'Cache-Control: no-cache';

  $ch = curl_init('https://soloverde.rexmas.cl/remuneraciones/es-CL/login');

  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)");
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $request);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{"username":"Consultas","password":"Config01"}');
  curl_setopt($ch, CURLOPT_ENCODING,"");

  $respuesta = curl_exec($ch);

  $file = fopen($ruta . 'login.html', 'w+');
  fwrite($file, $respuesta);
  fclose($file);

  curl_close($ch);

  sleep(5);

  echo "Obteniendo token e identificador de sesion\n";

  $linea = "";

  $fp = fopen($ruta . 'descargas\\cookieRR.txt', "r");
  while (!feof($fp)){
      $linea = fgets($fp);
      if(strpos($linea, "csrftoken"))
      {
          // echo $linea;
          break;
      }
  }
  fclose($fp);

  $array = explode("csrftoken",$linea);

  $csrftoken = trim($array[1]);

  $linea = "";

  $fp = fopen($ruta . 'descargas\\cookieRR.txt', "r");
  while (!feof($fp)){
      $linea = fgets($fp);
      if(strpos($linea, "sessionid"))
      {
          // echo $linea;
          break;
      }
  }
  fclose($fp);

  $array = explode("sessionid",$linea);

  // var_dump($array);

  $sessionid = trim($array[1]);

  echo "Token2: " . $csrftoken . "\n";
  echo "Sessionid1: " . $sessionid . "\n";

  if($sessionid == ""){
    $linea = "";

    $fp = fopen($ruta . 'login.html', "r");
    while (!feof($fp)){
        $linea = fgets($fp);
        if(strpos($linea, "sessionid"))
        {
            // echo $linea;
            break;
        }
    }
    fclose($fp);

    $array = explode("sessionid=",$linea);
    $array2 = explode(";",$array);

    // var_dump($array);

    $sessionid = trim($array2[0]);
  }

  echo "Sessionid1_head: " . $sessionid . "\n";

  $informes = [];
  $informes[0] = [1122,'Empleados'];
  $informes[1] = [1123,'Contratos'];
  $informes[2] = [1124,'Empresas'];
  $informes[3] = [1125,'Cargos'];
  $informes[4] = [1126,'Centro_de_costos'];
  $informes[5] = [1127,'Vacaciones'];
  $informes[6] = [1128,'Licencias'];
  $informes[7] = [1221,'Catalogo'];

  for($i = 0; $i < count($informes) ; $i++){
    echo "Descargando informe de {$informes[$i][1]} \n";

    // Informe Empleados
    $request = [];

    $request[] = 'POST /remuneraciones/es-CL/rexisa/gecos/' . $i . '/ejecutar HTTP/1.1';
    $request[] = 'Host: soloverde.rexmas.cl';
    $request[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0';
    $request[] = 'Accept: application/json, text/plain, */*';
    $request[] = 'Accept-Language: es-CL,es;q=0.8,en-US;q=0.5,en;q=0.3';
    $request[] = 'Accept-Encoding: gzip, deflate, br';
    $request[] = 'Referer: https://soloverde.rexmas.cl/remuneraciones/es-CL/rexisa/gecos/' . $informes[$i][0] . '/ejecutar';
    $request[] = 'Content-Type: application/json;charset=utf-8';
    $request[] = 'X-CSRFToken: ' . $csrftoken;
    $request[] = 'Content-Length: 17';
    $request[] = 'Origin: https://soloverde.rexmas.cl';
    $request[] = 'DNT: 1';
    $request[] = 'Connection: keep-alive';
    $request[] = 'Cookie: csrftoken=' . $csrftoken . '; sessionid=' . $sessionid;
    $request[] = 'Sec-Fetch-Dest: empty';
    $request[] = 'Sec-Fetch-Mode: cors';
    $request[] = 'Sec-Fetch-Site: same-origin';
    $request[] = 'Pragma: no-cache';
    $request[] = 'Cache-Control: no-cache';

    $ch = curl_init('https://soloverde.rexmas.cl/remuneraciones/es-CL/rexisa/gecos/' . $informes[$i][0] . '/ejecutar');

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"parametros":""}');
    curl_setopt($ch, CURLOPT_ENCODING,"");

    $respuesta = curl_exec($ch);

    curl_close($ch);

    sleep(5);

    $file = fopen($ruta . "descargas/" . $informes[$i][1] . '.xlsx', 'w+');
    fwrite($file, $respuesta);
    fclose($file);

    echo "Ruta de informe: " . $ruta . "descargas/" . $informes[$i][1] . ".xlsx\n";

    // Re Login

    $request = [];

    // $request[] = 'POST /remuneraciones/es-CL/login HTTP/1.1';
    // $request[] = 'Host: soloverde.rexmas.cl';
    // $request[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0';
    // $request[] = 'Accept: application/json, text/plain, */*';
    // $request[] = 'Accept-Language: es-CL,es;q=0.8,en-US;q=0.5,en;q=0.3';
    // $request[] = 'Accept-Encoding: gzip, deflate, br';
    $request[] = 'Referer: https://soloverde.rexmas.cl/remuneraciones/es-CL/login';
    $request[] = 'Content-Type: application/json';
    $request[] = 'X-XSRF-TOKEN: ' . $csrftoken;
    $request[] = 'X-CSRFTOKEN: ' . $csrftoken;
    // $request[] = 'Content-Length: 46';
    // $request[] = 'Origin: https://soloverde.rexmas.cl';
    // $request[] = 'DNT: 1';
    // $request[] = 'Connection: keep-alive';
    // $request[] = 'Cookie: csrftoken=' . $csrftoken;
    // $request[] = 'Sec-Fetch-Dest: empty';
    // $request[] = 'Sec-Fetch-Mode: cors';
    // $request[] = 'Sec-Fetch-Site: same-origin';
    // $request[] = 'Pragma: no-cache';
    // $request[] = 'Cache-Control: no-cache';

    $ch = curl_init('https://soloverde.rexmas.cl/remuneraciones/es-CL/login');

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"username":"Consultas","password":"Config01"}');
    curl_setopt($ch, CURLOPT_ENCODING,"");

    $respuesta = curl_exec($ch);

    $file = fopen($ruta . 'login.html', 'w+');
    fwrite($file, $respuesta);
    fclose($file);

    curl_close($ch);

    sleep(5);

    echo "Obteniendo token e identificador de sesion\n";

    $linea = "";

    $fp = fopen($ruta . 'descargas\\cookieRR.txt', "r");
    while (!feof($fp)){
        $linea = fgets($fp);
        if(strpos($linea, "csrftoken"))
        {
            // echo $linea;
            break;
        }
    }
    fclose($fp);

    $array = explode("csrftoken",$linea);

    $csrftoken = trim($array[1]);

    $linea = "";

    $fp = fopen($ruta . 'descargas\\cookieRR.txt', "r");
    while (!feof($fp)){
        $linea = fgets($fp);
        if(strpos($linea, "sessionid"))
        {
            // echo $linea;
            break;
        }
    }
    fclose($fp);

    $array = explode("sessionid",$linea);

    $sessionid = trim($array[1]);

    echo "Token2: " . $csrftoken . "\n";
    echo "Sessionid1: " . $sessionid . "\n";

    if($sessionid == ""){
      $linea = "";

      $fp = fopen($ruta . 'login.html', "r");
      while (!feof($fp)){
          $linea = fgets($fp);
          if(strpos($linea, "sessionid"))
          {
              // echo $linea;
              break;
          }
      }
      fclose($fp);

      $array = explode("sessionid=",$linea);
      $array2 = explode(";",$array);

      // var_dump($array);

      $sessionid = trim($array2[0]);
    }

    echo "Sessionid1_head: " . $sessionid . "\n";

    sleep(20);
  }

  echo "Informe descargado se borrara la cookie para matar la sesion\n\n";

  // unlink($cookie);

  // Lectura de archivo de centro de costo
  $rutaArchivo = $ruta . "descargas/Centro_de_costos.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arreglo = [];
  $f = 0;
  foreach ($hojaActual->getRowIterator() as $fila) {
    if($f > 1){
      $flag = 0;
      $datos = [];
      foreach ($fila->getCellIterator() as $celda) {
        if($flag > 13){
          break;
        }
        $fila = $celda->getRow();
        $columna = $celda->getColumn();

        $datos[] = strval($celda->getValue());

        $flag++;
      }
      $arreglo[] = $datos;
    }
    $f++;
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

    $sel = datoCentroCostoIngresado($item);
    $sel[0]['CANTIDAD'];

    if($sel[0]['CANTIDAD'] == '0'){
      if($item != ""){
        $ins = ingresaCentroCosto($item,$nombre);
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
      echo "Centro de costo existente: " . $item . "\n";
    }
  }

  // Lectura de archivo de Empleados
  $rutaArchivo = $ruta . "descargas/Empleados.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arreglo = [];
  $f = 0;
  foreach ($hojaActual->getRowIterator() as $fila) {
    if($f > 1){
      $flag = 0;
      $datos = [];
      foreach ($fila->getCellIterator() as $celda) {
        if($flag > 56){
          break;
        }
        $fila = $celda->getRow();
        $columna = $celda->getColumn();

        $datos[] = strval($celda->getValue());

        $flag++;
      }
      $arreglo[] = $datos;
    }
    $f++;
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
    $FECHA_NACIMIENTO = convertDate($arreglo[$j][5]);
    $NACIONALIDAD = ucwords(strtolower($arreglo[$j][7]));
    $DOMICILIO = ucwords(strtolower($arreglo[$j][8] . ", " . $arreglo[$j][9] . ", " . $arreglo[$j][10]));
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
  $rutaArchivo = $ruta . "descargas/Cargos.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arreglo = [];
  $f = 0;
  foreach ($hojaActual->getRowIterator() as $fila) {
    if($f > 1){
      $flag = 0;
      $datos = [];
      foreach ($fila->getCellIterator() as $celda) {
        if($flag > 22){
          break;
        }
        $fila = $celda->getRow();
        $columna = $celda->getColumn();

        $datos[] = strval($celda->getValue());

        $flag++;
      }
      $arreglo[] = $datos;
    }
    $f++;
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
        }
        else{
          echo "Cargo error: " . $id . "\n";
        }
      }
      else{
        echo "Cargo error: " . $id . "\n";
      }
    }
    else{
      echo "Cargo error: " . $id . "\n";
    }
  }

  // Lectura de archivo de centro de catalogo
  $rutaArchivo = $ruta . "descargas/Catalogo.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arreglo = [];
  $f = 0;
  foreach ($hojaActual->getRowIterator() as $fila) {
    if($f > 1){
      $flag = 0;
      $datos = [];
      foreach ($fila->getCellIterator() as $celda) {
        if($flag > 13){
          break;
        }
        $fila = $celda->getRow();
        $columna = $celda->getColumn();

        $datos[] = strval($celda->getValue());

        $flag++;
      }
      $arreglo[] = $datos;
    }
    $f++;
  }

  $JEAS = [];
  $JEAS[1] = "J";
  $JEAS[2] = 'E';
  $JEAS[3] = 'A';
  $JEAS[4] = 'S';
  $JEAS[6] = 'G';

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
  }

  // Lectura de archivo de contratos
  $rutaArchivo = $ruta . "descargas/Contratos.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arreglo = [];
  $f = 0;
  foreach ($hojaActual->getRowIterator() as $fila) {
    if($f > 1){
      $flag = 0;
      $datos = [];
      foreach ($fila->getCellIterator() as $celda) {
        if($flag > 91){
          break;
        }
        $fila = $celda->getRow();
        $columna = $celda->getColumn();

        $datos[] = strval($celda->getValue());

        $flag++;
      }
      $arreglo[] = $datos;
    }
    $f++;
  }

  for($j = 0; $j < count($arreglo); $j++){
    $dni = $arreglo[$j][1];
    $idempresa = $arreglo[$j][2];
    $idcentrocosto = $arreglo[$j][12];
    $idcargo = $arreglo[$j][82];
    $codigoCargoGenerico = $arreglo[$j][44];
    $codigoRef1 = $arreglo[$j][43];
    $codigoRef2 = $arreglo[$j][31];

    $ins = actualizaCargoPersonal($dni,$idcargo);

    if($ins == "Ok"){
      echo "Cargo actualizado a personal: " . $dni . " - " . $idcargo . "\n";
    }
    else{
      echo "Cargo error a personal: " . $dni . " - " . $idcargo . "\n";
    }

    $ins = actualizaCargoGenericoPersonal($dni,$codigoCargoGenerico,$codigoRef1,$codigoRef2);

    if($ins == "Ok"){
      echo "Cargo generico actualizado a personal: " . $dni . " - " . $codigoCargoGenerico . "\n";
    }
    else{
      echo "Cargo generico error a personal: " . $dni . " - " . $codigoCargoGenerico . "\n";
    }

    $sel = ACTExistente($dni);
    $sel[0]['CANTIDAD'];

    if($sel[0]['CANTIDAD'] == '0'){
      if($dni != ""){
        $ins = ingresaACT($dni,$idcentrocosto);

        if($ins == "Ok"){
          echo "CECO ingresado correctamente: " . $dni . " - " . $idcentrocosto . "\n";
        }
        else{
          echo "CECO error: " . $dni . " - " . $idcentrocosto . "\n";
        }
      }
      else{
        echo "CECO error: " . $dni . " - " . $idcentrocosto . "\n";
      }
    }
    else{
      if($dni != ""){
        $ins = actualizaACT($dni,$idcentrocosto);

        if($ins == "Ok"){
          echo "CECO actualizado correctamente: " . $dni . " - " . $idcentrocosto . "\n";
        }
        else{
          echo "CECO error: " . $dni . " - " . $idcentrocosto . "\n";
        }
      }
    }
  }


  // Lectura de archivo de cargos
  $rutaArchivo = $ruta . "descargas/Vacaciones.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arreglo = [];
  $f = 0;
  foreach ($hojaActual->getRowIterator() as $fila) {
    if($f > 1){
      $flag = 0;
      $datos = [];
      foreach ($fila->getCellIterator() as $celda) {
        if($flag > 17){
          break;
        }
        $fila = $celda->getRow();
        $columna = $celda->getColumn();

        $datos[] = strval($celda->getValue());

        $flag++;
      }
      $arreglo[] = $datos;
    }
    $f++;
  }

  for($j = 0; $j < count($arreglo); $j++){
    $firmado = $arreglo[$j][12];

    if($firmado == "si" && $arreglo[$j][8] != ""){
      $dni = $arreglo[$j][1];
      $fini = convertDate($arreglo[$j][8]);
      $fter = convertDate($arreglo[$j][9]);
      $ins = ingresaVacacionRexmas($dni,$fini,$fter);

      var_dump($ins);

      if($ins == "Ok"){
        echo "Vacación ingresada: " . $dni . "\n";
      }
      else{
        echo "Vacación error: " . $dni . "\n";
      }
    }
  }

  // Lectura de archivo de cargos
  $rutaArchivo = $ruta . "descargas/Licencias.xlsx";
  $documento = IOFactory::load($rutaArchivo);
  $hojaActual = $documento->getSheet(0);

  $arreglo = [];
  $f = 0;
  foreach ($hojaActual->getRowIterator() as $fila) {
    if($f > 1){
      $flag = 0;
      $datos = [];
      foreach ($fila->getCellIterator() as $celda) {
        if($flag > 20){
          break;
        }
        $fila = $celda->getRow();
        $columna = $celda->getColumn();

        $datos[] = strval($celda->getValue());

        $flag++;
      }
      $arreglo[] = $datos;
    }
    $f++;
  }

  for($j = 0; $j < count($arreglo); $j++){
    $dni = $arreglo[$j][4];
    $fini = explode("-",substr($arreglo[$j][19],0,10));
    $fini = $fini[2] . "-" . $fini[1] . "-" . $fini[0];
    $fter = explode("-",substr($arreglo[$j][19],10,10));
    $fter = $fter[2] . "-" . $fter[1] . "-" . $fter[0];
    $ins = ingresaLicenciaRexmas($dni,$fini,$fter);

    if($ins == "Ok"){
      echo "Licencia ingresada: " . $dni . "\n";
    }
    else{
      echo "Licencia error: " . $dni . "\n";
    }
  }

  echo "Hora de termino: " . date('Y-m-d H:i:s') . "\n";

  //Funciones
  function convertDate($dateValue) {
    $unixDate = ($dateValue - 25569) * 86400;
    return gmdate("Y-m-d", $unixDate);
  }

  // echo count($arreglo) . "\n";
?>
