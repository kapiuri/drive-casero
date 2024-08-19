<?php
// Incluir el archivo de conexión a la base de datos
require('conexionBD.php');

// Iniciar sesión
session_start();

// Activar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Comprobar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Guardar el nombre de usuario en una variable
$username = $_SESSION['username'];
$directory = 'trash/' . $username;

if (is_dir($directory)) {
    $files = scandir($directory);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $filePath = $directory . '/' . $file;
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
    }

    header("Location: trash.php?mensaje=Todos los archivos han sido eliminados.");
} else {
    echo "Error: El directorio de la papelera no existe.";
}
?>