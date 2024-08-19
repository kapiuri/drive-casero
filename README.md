# Drive Casero

**Drive Casero** es una página web desarrollada en PHP, HTML, CSS, AJAX y jQuery que actúa como un sistema de almacenamiento de archivos en una carpeta personal, similar a Google Drive. Este sistema permite a los usuarios registrar una cuenta, subir, organizar y compartir archivos en línea.

## Primeros Pasos: Importación de la Base de Datos

Antes de comenzar a utilizar el sistema, es necesario realizar la configuración inicial de la base de datos. Sigue estos pasos:

1. **Importar `database.sql`**:
   - Lo primero que debes hacer es importar el archivo `database.sql` en tu sistema de gestión de bases de datos (como MySQL).
   - Este archivo contiene todas las tablas y estructuras necesarias para que el sistema funcione correctamente.

2. **Configuración de la Base de Datos**:
   - Asegúrate de que los detalles de conexión a la base de datos (host, usuario, contraseña, nombre de la base de datos) estén correctamente configurados en los archivos del sistema, normalmente en un archivo de configuración como `conexionDB.php` o similar.

## Características Principales

- **Interfaz de Usuario Amigable**: Diseño sencillo y accesible para facilitar la navegación y la gestión de archivos.
- **Gestión de Archivos**: Subida, eliminación, restauración y compartición de archivos de manera intuitiva.
- **Soporte para Múltiples Tipos de Archivos**: Compatible con una amplia variedad de formatos de archivos.
- **Unidad Compartida**: Espacio compartido donde todos los usuarios registrados pueden colaborar.

## Estructura de Carpetas

Para que el sistema funcione correctamente, es esencial que se creen las siguientes carpetas:

- **`shared`**: Almacena los archivos que son compartidos entre todos los usuarios.
- **`trash`**: Contiene los archivos que los usuarios han enviado a la papelera.
- **`uploads`**: Directorio principal donde se almacenan los archivos subidos por los usuarios.

Si prefieres cambiar la ubicación de estas carpetas, debes modificar los archivos correspondientes del sistema, como se detalla a continuación.

### Archivos a Modificar

Si cambias las ubicaciones predeterminadas de las carpetas, es necesario ajustar las rutas en los siguientes archivos:

- `delete.php`: Maneja la eliminación de archivos.
- `deleteall.php`: Permite la eliminación masiva de archivos.
- `drive.php`: Archivo principal que gestiona la lógica del "drive".
- `movetrash.php`: Se encarga de mover archivos a la papelera.
- `myfiles.php`: Gestiona la visualización y organización de los archivos del usuario.
- `register.php`: Automáticamente crea las carpetas del usuario al registrarse.
- `restore.php`: Restaura los archivos desde la papelera a la unidad principal.
- `shared.php`: Administra los archivos en la unidad compartida.
- `trash.php`: Muestra y gestiona los archivos en la papelera.

### Variables de Directorios

Es crucial actualizar las siguientes variables si modificas la ubicación de las carpetas:

- `$directory`: Directorio principal donde se gestionan los archivos del usuario.
- `$trashDirectory`: Ruta de la papelera de reciclaje.
- `$destinationDirectory`: Ruta de destino para restaurar o mover archivos.
- `$uploadsDirectory`: Directorio de almacenamiento de archivos subidos.
- `$sharedDirectory`: Carpeta compartida accesible para todos los usuarios.

## Funcionalidad del Sistema

El sistema permite realizar diversas operaciones con los archivos, similares a las funciones básicas de Google Drive:

1. **Registro de Usuario**:
   - Al registrarse, se crean automáticamente las carpetas personales del usuario.
   - Cada usuario tiene un espacio personal separado y privado dentro del sistema.

2. **Gestión de Archivos**:
   - Los usuarios pueden subir archivos desde su dispositivo local a su unidad personal.
   - Es posible organizar los archivos dentro de diferentes carpetas creadas por el usuario.
   - Se pueden mover archivos a la papelera, eliminarlos permanentemente o restaurarlos cuando sea necesario.

3. **Unidad Compartida**:
   - Existe una unidad compartida donde todos los usuarios pueden subir archivos.
   - Los archivos en esta unidad son accesibles para todos los usuarios registrados, facilitando la colaboración y el intercambio de archivos.

## Mapa de Iconos

El sistema utiliza un archivo JSON denominado `iconmap.json` que contiene un mapa de iconos. Este mapa asocia las extensiones de archivo con iconos específicos, mejorando la experiencia visual del usuario al identificar fácilmente el tipo de archivo.

- **Personalización de Iconos**: Si deseas añadir o modificar los iconos asociados con ciertos tipos de archivo, puedes editar el archivo `iconmap.json`.
- **Soporte Extendido de Iconos**: Añadir nuevos tipos de archivos es sencillo, solo debes actualizar el JSON con la extensión del nuevo archivo y el icono correspondiente.

## Seguridad y Privacidad

El sistema está diseñado con consideraciones básicas de seguridad:

- **Protección de Archivos**: Cada usuario solo tiene acceso a sus propios archivos y a los archivos en la unidad compartida.
- **Cifrado de Contraseñas**: Las contraseñas de los usuarios se almacenan de manera segura utilizando técnicas de cifrado.
- **Autenticación de Usuarios**: Solo los usuarios registrados pueden acceder a sus archivos o a la unidad compartida.

## Recomendaciones de Implementación

Para asegurar un funcionamiento óptimo del sistema, se recomienda:

- **Configuración del Servidor**: Asegúrate de que el servidor donde se despliega el sistema soporta PHP y tiene las extensiones necesarias para manejar archivos y sesiones.
- **Permisos de Carpeta**: Configura los permisos de las carpetas para asegurar que el servidor web tenga acceso de escritura y lectura a las carpetas `shared`, `trash` y `uploads`.
- **Backup Regular**: Realiza copias de seguridad regulares de los archivos almacenados para prevenir la pérdida de datos.

---

Con esta guía detallada, podrás entender mejor cómo funciona el sistema, cómo configurarlo según tus necesidades específicas, y cómo realizar la configuración inicial de la base de datos.
