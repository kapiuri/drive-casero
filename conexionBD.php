<?php
$usuario = 'root'; // Usuario
$contraseña = ''; // Contraseña del usuario
$servidor = 'localhost'; // Servidor de la base de datos
$bd = 'drive'; // Nombre de la base de datos que se va a utilizar
$dsn = "mysql:host=$servidor;dbname=$bd"; // DSN (Data Source Name) para conectar con MySQL

try {
    $con = new PDO($dsn, $usuario, $contraseña); // Crear una conexión PDO
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configurar el modo de error
} catch (PDOException $ex) {
    exit('No se ha podido conectar con la BD:<br/>' . $ex->getMessage()); // Mostrar el error si no se puede conectar con la BD
}
?>
