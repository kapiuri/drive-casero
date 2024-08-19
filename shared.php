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

        // Verificar si se obtuvo el user_id
        if ($user_id === null) {
            die("Error: No se pudo encontrar el user_id para el usuario.");
        }
    } catch (PDOException $e) {
        die("Error al obtener el user_id: " . $e->getMessage());
    }

    // Cargar el mapa de iconos desde el archivo JSON
    $iconmapfile = 'iconmap.json';
    $iconmap = file_exists($iconmapfile) ? json_decode(file_get_contents($iconmapfile), true) : [];
    $defaulticon = isset($iconmap['default']) ? $iconmap['default'] : 'fas fa-file';

    // Verifica si la carpeta compartida existe
    $directory = 'shared/'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styleshared.css">
    <title>Compartido</title>
</head>
<body>
    <header class="header">
        <h1>Compartido</h1>
    </header>

    <nav class="nav">
        <a href="drive.php" class="nav-link nav-inicio">Inicio</a>
        <a href="myfiles.php" class="nav-link nav-myfiles">Mis archivos</a>
        <a href="trash.php" class="nav-link nav-papelera">Papelera</a>
    </nav>

    <main class="main-content">
        <?php
            // Mostrar mensaje de resultado de mover si existe
            if (isset($_GET['mensaje'])) {
                echo '<p class="message">' . htmlspecialchars($_GET['mensaje']) . '</p>';
            }

            // Verifica si la carpeta existe
            if (is_dir($directory)) {
                // Obtiene una lista de los archivos y carpetas dentro del directorio
                $files = scandir($directory);

                // Itera sobre cada archivo o carpeta
                foreach ($files as $file) {
                    // Ignora los directorios '.' y '..'
                    if ($file !== '.' && $file !== '..') {
                        $rutafile = $directory . '/' . $file;
                        $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // Definir la extensión del archivo

                        // Verifica si es un archivo (para evitar descargar carpetas)
                        if (is_file($rutafile)) {
                            // Obtener el icono correspondiente
                            $iconclass = isset($iconmap[$file_ext]) ? $iconmap[$file_ext] : $defaulticon;

                            echo '<div class="file-item">';
                            echo '<i class="' . htmlspecialchars($iconclass) . ' file-icon"></i>';
                            echo '<a class="file-link" href="' . htmlspecialchars($rutafile) . '" download>' . htmlspecialchars($file) . '</a>';
                            echo ' <a href="movetrash.php?file=' . urlencode($file) . '" class="move-to-trash">[Mover a papelera]</a>';
                            echo '</div>';
                        }
                    }
                }
            } else {
                echo '<p class="error">El directorio no existe.</p>';
            }
        ?>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <nav class="footer-nav">
                <a href="drive.php" class="footer-link">Inicio</a>
                <a href="myfiles.php" class="footer-link">Mis archivos</a>
                <a href="shared.php" class="footer-link">Compartido</a>
                <a href="trash.php" class="footer-link">Papelera</a>
            </nav>
            <p class="footer-text">© <?= date('Y'); ?> Drive Casero. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
