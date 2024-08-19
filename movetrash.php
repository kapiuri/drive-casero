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

// Directorios
$uploadsDirectory = 'uploads/' . $username;
$sharedDirectory = 'shared';
$trashDirectory = 'trash/' . $username; // Ruta de la carpeta de papelera

// Crear la carpeta de papelera si no existe
if (!is_dir($trashDirectory)) {
    mkdir($trashDirectory, 0755, true);
}

$message = "";

if (isset($_GET['file'])) {
    $fileToMove = $_GET['file'];
    $sourceFile = ""; // Inicializamos la variable

    // Verificar si el archivo existe en 'uploads' o 'shared'
    if (file_exists($uploadsDirectory . '/' . $fileToMove)) {
        $sourceFile = $uploadsDirectory . '/' . $fileToMove;
    } elseif (file_exists($sharedDirectory . '/' . $fileToMove)) {
        // Aquí puedes agregar cualquier otra lógica de seguridad, si es necesario
        $sourceFile = $sharedDirectory . '/' . $fileToMove;
    } else {
        $message = "El archivo '$fileToMove' no existe.";
    }

    if ($sourceFile && is_file($sourceFile)) {
        $destinationFile = $trashDirectory . '/' . $fileToMove;

        // Intenta mover el archivo
        if (rename($sourceFile, $destinationFile)) {
        } else {
            $message = "No se pudo mover el archivo '$fileToMove'.";
        }
    }
} else {
    $message = "No se especificó ningún archivo para mover.";
}

// Redirige de vuelta a la página de gestión de archivos con un mensaje
header("Location: myfiles.php?mensaje=" . urlencode($message));
exit();
?>
