<?php
namespace TestArea;

include_once __DIR__ . "/../vendor/autoload.php";
include_once __DIR__ . "/../DAL/DAL.php";
include_once __DIR__ . "/../Models/ExcelTest.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use Models_ExcelTest\ExcelTest;
use DAL\DAL;
use DateTime;

class Test{
    public function stringMessage(){
        return "Este mensaje vino de Test";
    }

    public function excelToArray($filePath){
        $arrayTest = []; // Array para almacenar objetos exceltest
    
        try {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($filePath);
    
            // Obtener la hoja específica
            $sheet = $spreadsheet->getSheet(0);
            if (!$sheet) {
                throw new \Exception("No se encontró la hoja: $sheet");
            }
    
            // Obtener el número máximo de filas y empezar desde la fila 2 (omitiendo encabezado)
            $highestRow = $sheet->getHighestRow();
    
            for ($row = 2; $row <= $highestRow; $row++) {
                // Leer valores de las celdas de la fila
                $nombre       = $sheet->getCell("A$row")->getValue();
                $fecha   = $sheet->getCell("B$row")->getValue();
                $linkurl = $sheet->getCell("C$row")->getValue();
                $nulo   = $sheet->getCell("D$row")->getValue();

                // Validar si la celda es nula o está vacía
                $nulo = ($nulo === null || strtoupper($nulo) === 'NULL') ? null : $nulo;

                // Validar que la fecha como string sea datetime y formato latino
                if($this->isValidDateTime($fecha)){
                    $fecha = new DateTime($fecha);//$this->convertToDatetime($fecha);
                    $fecha = $fecha->format("Y-m-d H:i:s"); //Formato de bases de datos
                }
                else{
                    // return acaba con la ejecución de la función
                    return;
                }
    
                // Crear una instancia de BoletaVenta
                $objeto = new ExcelTest($nombre,$fecha,$linkurl,$nulo);
    
                // Agregar al array
                $arrayTest[] = $objeto;
            }
        } catch (\Exception $e) {
            die("Error al procesar el archivo: " . $e->getMessage());
        }
    
        return $arrayTest;
    }

    public function arrayToJson($filePath) {
        $arrayTest = $this->excelToArray($filePath);
        try {
            // Convertir el array de objetos a un array asociativo
            $arrayAssoc = array_map(function($excelTest) {
                $nulo = $excelTest->Nulo;
                $nulo = ($nulo === null) ? "null":$nulo; // Como es un json prefiero reotrnar un string que diga null
                return [
                    'nombre' => $excelTest->Nombre,
                    'fecha' => $excelTest->Fecha,
                    'linkurl' => $excelTest->LinkUrl,
                    'nulo' => $nulo
                ];
            }, $arrayTest);
    
            // Convertir el array asociativo a JSON
            $json = json_encode($arrayAssoc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Error al convertir a JSON: " . json_last_error_msg());
            }
    
            return $json;
        } catch (\Exception $e) {
            die("Error al procesar el array: " . $e->getMessage());
        }
    }

    function isValidDateTime($date, $format = 'd-m-Y') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    //===============DAL TESTS====================//
    public function testDbConnection(){
        $dal = new DAL();
        return "Conexión exitosa con la DB!";
    }

    /**
     * Inserta un array de objetos ExcelTest en la tabla testexcel.
     * 
     * @param array $excelTests Array de objetos ExcelTest.
     * @return void
     */
    public function insertExcelTests(array $excelTests) {
        $dal = new DAL();
        $mensaje = $dal->insertExcelTests($excelTests);
        return $mensaje;
    }
}