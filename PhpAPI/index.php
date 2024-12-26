<?php
namespace main;

// require_once "Engine/Engine.php";
// use Engine\Engine;

require_once "Tests/Test.php";
use TestArea\Test;

// Configuración de encabezados para CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// Verifica si la solicitud es POST
// if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
//     $engine = new Engine();
//     // Respuesta exitosa en formato JSON
//     header('Content-Type: application/json');
//     http_response_code(200); // Código de respuesta exitoso
//     echo json_encode(["Mensaje" => "" . $engine->Mensaje()]);
//     exit;
// }

// // Respuesta en caso de método no permitido
// header('Content-Type: application/json');
// http_response_code(405); // Método no permitido
// echo json_encode(["error" => "Método no permitido."]);
// exit;


//=================Tests Area========================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {

    $testclass = new Test();
    // Define el directorio temporal
    $tempDir = __DIR__ . "/temp_dir/";
    
    // Crea el directorio si no existe
    if (!is_dir($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    $uploadFile = $tempDir . basename($_FILES['file']['name']);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        sleep(2);

        // Responde con JSON
        header('Content-Type: application/json');
        http_response_code(200); // Código de todo OK
        if(file_exists(__DIR__ . "/temp_dir")){
            chmod($tempDir, 0777); // Establece permisos 0777 directorio
            chmod($uploadFile, 0777); // Establece permisos 0777 al archivo subido

            //Asserts
            // $valores = $testclass->excelToArray($uploadFile);
            // echo json_encode(["Mensaje" => "Se pudo convertir a array de objetos"]);
            
            // $json = $testclass->arrayToJson($uploadFile);
            // exec("rm --dir -r temp_dir/");
            // echo $json;

            // $mensaje = $testclass->testDbConnection();
            // exec("rm --dir -r temp_dir/");
            // echo json_encode(["Mensaje" => $mensaje]);

            $array = $testclass->excelToArray($uploadFile);
            $mensaje = $testclass->insertExcelTests($array);
            exec("rm --dir -r temp_dir/");
            echo json_encode(["Mensaje" => $mensaje]);
        }
        else{ echo json_encode(["Mensaje" => "¡Failed uploading file!"]); }
        
    } else {
        header('Content-Type: application/json');
        http_response_code(400); // Código de error
        echo json_encode(["Mensaje" => "Failed uploading file"]);
    }
    
    exit;
}

// Respuesta en caso de error
header('Content-Type: application/json');
http_response_code(400); // Código de error
echo json_encode(["error" => "No se envió ningún archivo."]);
exit;
?>