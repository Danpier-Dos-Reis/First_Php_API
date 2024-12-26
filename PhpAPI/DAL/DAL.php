<?php
namespace DAL;

use PDO;
use PDOException;

class DAL {
    private $pdo;

    /**
     * Constructor que inicializa la conexión a la base de datos.
     */
    public function __construct() {
        $dsn = 'mysql:host=localhost;dbname=dbtest;charset=utf8mb4';
        $user = 'windan';
        $password = 'd@np13_d0sr3Is';

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error al conectar con la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Inserta un array de objetos ExcelTest en la tabla testexcel.
     * 
     * @param array $excelTests Array de objetos ExcelTest.
     * @return void
     */
    public function insertExcelTests(array $excelTests) {
        try {
            // Preparar la consulta SQL para insertar datos
            $sql = "INSERT INTO testexcel (nombre, fecha, linkurl, nulos) VALUES (:nombre, :fecha, :linkurl, :nulos)";
            $stmt = $this->pdo->prepare($sql);

            // Iterar sobre los objetos y realizar inserciones
            foreach ($excelTests as $excelTest) {
                $stmt->execute([
                    ':nombre' => $excelTest->Nombre,
                    ':fecha' => $excelTest->Fecha,
                    ':linkurl' => $excelTest->LinkUrl,
                    ':nulos' => $excelTest->Nulo
                ]);
            }
            return "Datos insertados correcatmente en la DB";
        } catch (PDOException $e) {
            exec("echo " . $e->getMessage() . " > temp_dir/dalerror.log");
            die("Error al insertar los datos en la tabla: " . $e->getMessage());
        }
    }
}