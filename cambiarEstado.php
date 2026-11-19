<?php
/**
 * Controlador para alternar el estado de disponibilidad de un libro.
 *
 * Procesa solicitudes POST con el ID del libro y su estado actual
 * (1 = Disponible, 0 = Prestado) y actualiza el registro en MySQL.
 *
 * @package    BibliotecaKenny
 * @subpackage Controladores
 * @author     Kenny <kenny@ejemplo.com>
 * @version    1.1.0
 */

/**
 * Calcula el nuevo estado lógico y actualiza la disponibilidad del libro.
 *
 * @param mysqli $conexion     Conexión activa a la base de datos.
 * @param int    $id           Identificador único del libro.
 * @param int    $estadoActual Estado actual (1 = Disponible, 0 = Prestado).
 * @return bool True si el registro se actualizó correctamente, false si falló.
 */
function alternarEstadoLibro(mysqli $conexion, int $id, int $estadoActual): bool
{
    /** @var int $nuevoEstado Estado invertido tras la acción */
    $nuevoEstado = ($estadoActual === 1) ? 0 : 1;

    $sql = "UPDATE libros SET disponible = $nuevoEstado WHERE id = $id";

    return $conexion->query($sql) === true;
}

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST['id'], $_POST['actual'])
) {
    $host = "fdb1029.awardspace.net";
    $usuario = "4796121_ecovida";
    $password = "contaminacion67";
    $baseDatos = "4796121_ecovida";

    /** @var mysqli $conexion Instancia de conexión a la base de datos */
    $conexion = new mysqli($host, $usuario, $password, $baseDatos);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    /** @var int $id Identificador único del libro */
    $id = intval($_POST['id']);
    /** @var int $estadoActual Estado actual del libro */
    $estadoActual = intval($_POST['actual']);

    if (alternarEstadoLibro($conexion, $id, $estadoActual)) {
        header("Location: index.php?updated=1");
        exit;
    } else {
        echo "Error al actualizar el estado: " . $conexion->error;
    }

    $conexion->close();
} else {
    header("Location: index.php");
    exit;
}
