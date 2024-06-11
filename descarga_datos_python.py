from seleniumwire import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.keys import Keys
from datetime import datetime, timedelta
from pathlib import Path
import pandas as pd
import time
import os
import datetime
import shutil

options = webdriver.ChromeOptions()
options.headless = True

# Set the download directory path
downloads_path = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'descargas')
print(downloads_path)

options.add_experimental_option("prefs", {"download.default_directory": downloads_path})


driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)

driver.get('chrome://settings/clearBrowserData')

driver.get("https://soloverde.rexmas.cl/remuneraciones/es-CL/login")

print("Iniciando login")

time.sleep(3)

user = driver.find_element("id","username")
user.send_keys("Consultas")

#insertamos los datos de la contraseña por ID
con = driver.find_element("id","password")
con.send_keys("Config07")
con.send_keys(Keys.RETURN)

time.sleep(3)


informes = [];
informes.append([1122,'consulta_ct01_empleados'])
informes.append([1123,'consulta_ct02_contratos'])
informes.append([1124,'consulta_ct03_empresas'])
informes.append([1125,'consulta_ct04_cargos'])
informes.append([1126,'consulta_ct05_centros_de_costo'])
informes.append([1127,'consulta_ct06_vacaciones'])
informes.append([1128,'consulta_ct07_licencias'])
informes.append([1221,'consulta_ct08_catalogos'])
informes.append([1386,'consulta_ct010_permisos_administrativos'])
informes.append([1419,'consulta_ct12_permisos_en_espera_de_licencia'])
informes.append([1254,'consulta_ct09_resultados_x_proceso'])

actual = datetime.datetime.now().strftime("%d-%m-%Y")
periodo_anterior5 = (datetime.datetime.now() - timedelta(days=5*30)).strftime("%Y-%m")
periodo_anterior4 = (datetime.datetime.now() - timedelta(days=4*30)).strftime("%Y-%m")
periodo_anterior3 = (datetime.datetime.now() - timedelta(days=3*30)).strftime("%Y-%m")
periodo_anterior2 = (datetime.datetime.now() - timedelta(days=2*30)).strftime("%Y-%m")
periodo_anterior = (datetime.datetime.now() - timedelta(days=30)).strftime("%Y-%m")
periodo_actual = datetime.datetime.now().strftime("%Y-%m")

periodos = []
periodos.append(periodo_anterior5)
periodos.append(periodo_anterior4)
periodos.append(periodo_anterior3)
periodos.append(periodo_anterior2)
periodos.append(periodo_anterior)
periodos.append(periodo_actual)


for i in range(len(informes)):
    if i != 10:
        if i ==8:
            print("Descargando informe: " + informes[i][1])
        
            driver.get('https://soloverde.rexmas.cl/remuneraciones/es-CL/rexisa/gecos/' + str(informes[i][0]) + '/ejecutar')
        
            driver.implicitly_wait(180)

            # wait = WebDriverWait(driver, 30)
            # button = wait.until(EC.element_to_be_clickable((By.XPATH, "/html/body/div[1]/div[3]/div[2]/div[2]/form/div[2]/div/input")))
            # button = wait.until(EC.element_to_be_clickable((By.CLASS_NAME, "button-submit")))
            button = driver.find_elements(By.CLASS_NAME, "button-submit")
            button[0].click()

            time.sleep(30)

            # response = driver.requests[-1].response

            # nombre_archivo = informes[i][1] + '.xlsx'
            # ruta_destino = downloads_path + '/' + nombre_archivo
            # with open(ruta_destino, 'wb') as archivo:
            #     archivo.write(response.body)

            #time.sleep(2)

            print("Informe descargado: " + informes[i][1])
        
            time.sleep(2)
    else:
        for j in range(len(periodos)):
            print("Descargando informe: " + informes[i][1] + ", Periodo: " + periodos[j])

            driver.get('https://soloverde.rexmas.cl/remuneraciones/es-CL/rexisa/gecos/' + str(informes[i][0]) + '/ejecutar')

            driver.implicitly_wait(180)

            parametros = driver.find_element("id","id_parametros")
            parametros.clear()
            parametros.send_keys("'" + periodos[j] + "'")

            driver.implicitly_wait(60)

            # wait = WebDriverWait(driver, 70)
            # button = wait.until(EC.element_to_be_clickable((By.XPATH, "/html/body/div[1]/div[3]/div[2]/div[2]/form/div[2]/div/input")))
            button = driver.find_elements(By.CLASS_NAME, "button-submit")
            button[0].click()

            time.sleep(60)

            response = driver.requests[-1].response
            
            nombre_archivo = informes[i][1] + '_' + periodos[j] + '.xlsx'
            print(nombre_archivo)
            ruta_destino = downloads_path + '/' + nombre_archivo
            print(ruta_destino)
            with open(ruta_destino, 'wb') as archivo:
                 archivo.write(response.body)
            shutil.move (informes[i][1] + '.xlsx',nombre_archivo)

            time.sleep(2)

            print("Informe descargado: " + informes[i][1])

            time.sleep(2)
