<?php
require 'vendor/autoload.php';

use App\src\config\Connection;
use model\ORMModel;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$user = $_ENV["ENV_CONFIG_USERNAME"];
$pass = $_ENV["ENV_CONFIG_PASSWORD"];
$host = $_ENV["ENV_CONFIG_HOST"];
$db   = $_ENV["ENV_CONFIG_DATABASE"];

$sql = new Connection($user, $pass, $host, $db);
$sql->openSQL_PDO();

if($sql->conn === null) {
    http_response_code(500);
    echo ("Error en la conexion");
    exit();
}
//No acepta el parametro $sql->conn
// $ormModel = new ORMModel($sql->conn);