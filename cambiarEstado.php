<?php
/**
 * Controlador para alternar el estado de disponibilidad de un libro.
 *
 * Procesa solicitudes POST que contienen el ID del libro y su estado actual
 * (1 = Disponible, 0 = Prestado), invierte el valor lógico y actualiza
 * el registro correspondiente en la tabla `libros`.
 *
 * @package    BibliotecaKenny
 * @subpackage Controladores
 * @author     Kenny
 * @version    1.1.0
 */

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
    /** @var int $estadoActual Estado actual del libro (1 o 0) */
    $estadoActual = intval($_POST['actual']);

    /** @var int $nuevoEstado Estado invertido tras la acción */
    $nuevoEstado = ($estadoActual === 1) ? 0 : 1;

    /** @var string $sql Consulta SQL de actualización */
    $sql = "UPDATE libros SET disponible = $nuevoEstado WHERE id = $id";

    if ($conexion->query($sql) === true) {
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
