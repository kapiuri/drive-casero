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
$trashDirectory = 'trash/' . $username; // Ruta de la carpeta de papelera

if (isset($_GET['file'])) {
    $fileToDelete = $_GET['file'];
    $filePath = $trashDirectory . '/' . $fileToDelete;

    // Verifica que el archivo exista y sea un archivo
    if (file_exists($filePath) && is_file($filePath)) {
        // Intenta borrar el archivo
        if (unlink($filePath)) {
        } else {
            $message = "No se pudo eliminar el archivo '$fileToDelete'.";
        }
    } else {
        $message = "El archivo '$fileToDelete' no existe.";
    }
} else {
    $message = "No se especificó ningún archivo para eliminar.";
}

    // Redirige de vuelta a la página de la papelera con un mensaje
    header("Location: trash.php?mensaje=" . urlencode($message));
    exit();
?>
