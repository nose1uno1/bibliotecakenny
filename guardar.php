<?php
/**
 * Controlador de inserción de nuevos libros en el inventario.
 *
 * Recibe los datos del formulario enviados por POST desde index.php,
 * sanitiza las cadenas de texto e inserta el registro en la tabla `libros`.
 *
 * @package    BibliotecaKenny
 * @subpackage Controladores
 * @author     Kenny <kenny@ejemplo.com>
 * @version    1.1.0
 */

/**
 * Sanitiza los datos e inserta un nuevo libro en la base de datos.
 *
 * @param mysqli $conexion Conexión activa a la base de datos.
 * @param string $titulo   Título del libro a registrar.
 * @param string $autor    Nombre del autor del libro.
 * @param string $isbn     Código ISBN del libro.
 * @return bool True si la inserción fue exitosa, false en caso contrario.
 */
function registrarLibro(mysqli $conexion, string $titulo, string $autor, string $isbn): bool
{
    $tituloLimpio = $conexion->real_escape_string($titulo);
    $autorLimpio = $conexion->real_escape_string($autor);
    $isbnLimpio = $conexion->real_escape_string($isbn);

    $sql = "INSERT INTO libros (titulo, autor, isbn) "
         . "VALUES ('$tituloLimpio', '$autorLimpio', '$isbnLimpio')";

    return $conexion->query($sql) === true;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = "fdb1029.awardspace.net";
    $usuario = "4796121_ecovida";
    $password = "contaminacion67";
    $baseDatos = "4796121_ecovida";

    /** @var mysqli $conexion Instancia de conexión a la base de datos */
    $conexion = new mysqli($host, $usuario, $password, $baseDatos);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    /** @var bool $guardado Resultado de la operación de inserción */
    $guardado = registrarLibro(
        $conexion,
        $_POST['titulo'],
        $_POST['autor'],
        $_POST['isbn']
    );

    if ($guardado) {
        header("Location: index.php?success=1");
    } else {
        echo "Error al guardar el libro: " . $conexion->error;
    }

    $conexion->close();
}
