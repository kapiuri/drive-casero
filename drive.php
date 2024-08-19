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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Asegúrate de que el archivo haya sido enviado
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];

            // Verificar el tamaño del archivo
            if ($fileSize > 5000000) {
                die("Error: El archivo es demasiado grande.");
            }

            // Verificar el tipo de archivo
            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            // Determinar la carpeta de destino
            $uploadDirectory = isset($_POST['shared']) && $_POST['shared'] === 'on' ? "shared" : "uploads/$username";
            $fileDestination = "$uploadDirectory/$fileName";

            // Crear la carpeta de destino si no existe
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            // Mover el archivo a la carpeta de destino
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                echo "Archivo subido exitosamente.";
            } else {
                die("Error: No se pudo subir el archivo.");
            }
        } else {
            die("Error: No se ha enviado ningún archivo o hubo un problema con la subida.");
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styledrive.css">
    <title>Drive Casero</title>
    </head>
<body>
    <header class="header">
        <h1>Drive Casero</h1>
    </header>
    
    <nav class="nav">
        <a href="myfiles.php" class="nav-link">Mis archivos</a>
        <a href="shared.php" class="nav-link">Compartido</a>
        <a href="trash.php" class="nav-link">Papelera</a>
    </nav>
    
    <main class="main-content">
        <div class="user-info">
            <p>Bienvenido, <a href="logout.php" class="user-link"><?= htmlspecialchars($username) ?></a></p>
        </div>
        
        <form id="upload-form" action="drive.php" method="post" enctype="multipart/form-data" class="upload-form">
            <input type="file" name="file" id="file" class="file-input" required>
            <label>
                <input type="checkbox" name="shared"> Guardar en carpeta compartida
            </label>
            <input type="submit" value="Subir Archivo" name="submit" class="submit-button">
            <div id="progress-container" class="progress-container">
                <div id="progress-bar" class="progress-bar"></div>
            </div>
            <p id="progress-text" class="progress-text"></p>
        </form>
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
    <script>
        document.getElementById('upload-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario tradicional

            // Mostrar la barra de progreso y el texto
            document.getElementById('progress-container').style.display = 'block';
            document.getElementById('progress-text').style.display = 'block';

            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            
            xhr.open('POST', this.action, true);
            
            // Actualizar la barra de progreso
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    var percentComplete = (e.loaded / e.total) * 100;
                    document.getElementById('progress-bar').style.width = percentComplete + '%';
                    document.getElementById('progress-bar').textContent = Math.round(percentComplete) + '%';
                }
            });

            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    document.getElementById('progress-text').textContent = 'Archivo subido exitosamente.';
                } else {
                    document.getElementById('progress-text').textContent = 'Error al subir el archivo.';
                }
                // Ocultar la barra de progreso después de la carga
                setTimeout(function() {
                    document.getElementById('progress-container').style.display = 'none';
                    document.getElementById('progress-text').style.display = 'none';
                }, 3000); // Esperar 3 segundos antes de ocultar para que el usuario vea el mensaje
            });

            xhr.addEventListener('error', function() {
                document.getElementById('progress-text').textContent = 'Error al enviar el archivo.';
                // Ocultar la barra de progreso después de un error
                setTimeout(function() {
                    document.getElementById('progress-container').style.display = 'none';
                    document.getElementById('progress-text').style.display = 'none';
                }, 3000); // Esperar 3 segundos antes de ocultar para que el usuario vea el mensaje
            });

            xhr.send(formData);
        });
    </script>
</body>
</html>