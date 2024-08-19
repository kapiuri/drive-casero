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

// Obtener el user_id del usuario logueado
try {
    $sql = "SELECT id_user FROM users WHERE username = :username";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_id = $result ? $result['id_user'] : null;

    if ($user_id === null) {
        die("Error: No se pudo encontrar el user_id para el usuario.");
    }
} catch (PDOException $e) {
    die("Error al obtener el user_id: " . $e->getMessage());
}

if (isset($_GET['file'])) {
    $fileToRestore = $_GET['file'];
    $trashDirectory = 'trash/' . $username;
    $sourceFile = $trashDirectory . '/' . $fileToRestore;

    // Suponiendo que la ruta original se puede inferir o se almacena en algún lugar
    // Aquí simplemente movemos el archivo a la carpeta de usuario en "uploads"
    $destinationDirectory = 'uploads/' . $username;
    $destinationFile = $destinationDirectory . '/' . $fileToRestore;

    // Verifica que el archivo exista y sea un archivo
    if (file_exists($sourceFile) && is_file($sourceFile)) {
        // Asegúrate de que el directorio de destino exista
        if (!is_dir($destinationDirectory)) {
            mkdir($destinationDirectory, 0755, true); // Crear el directorio si no existe
        }

        // Intenta mover el archivo de regreso
        if (rename($sourceFile, $destinationFile)) {
            echo "success";
        } else {
            echo "Error al restaurar el archivo.";
        }
    } else {
        echo "Error: Archivo no encontrado.";
    }
} else {
    echo "Error: Archivo no especificado.";
}
?>
