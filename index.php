<?php
/**
 * Vista principal del Sistema de Control de Biblioteca.
 *
 * Establece la conexión con la base de datos MySQL en AwardSpace,
 * consulta el inventario de libros ordenado por ID descendente
 * y renderiza la interfaz de registro y listado.
 *
 * @package    BibliotecaKenny
 * @subpackage Vistas
 * @author     Kenny <kenny@ejemplo.com>
 * @version    1.1.0
 */

/**
 * Crea y verifica la conexión con el servidor de base de datos MySQL.
 *
 * @param string $host      Dirección del servidor de base de datos.
 * @param string $usuario   Nombre de usuario de la base de datos.
 * @param string $password  Contraseña de acceso a la base de datos.
 * @param string $baseDatos Nombre de la base de datos a seleccionar.
 * @return mysqli Objeto de conexión activa a MySQL.
 */
function conectarBaseDatos(string $host, string $usuario, string $password, string $baseDatos): mysqli
{
    $conexion = new mysqli($host, $usuario, $password, $baseDatos);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    return $conexion;
}

/**
 * Obtiene todos los libros registrados ordenados del más reciente al más antiguo.
 *
 * @param mysqli $conexion Conexión activa a la base de datos.
 * @return mysqli_result|bool Conjunto de resultados con los registros de libros.
 */
function obtenerInventarioLibros(mysqli $conexion)
{
    $sql = "SELECT * FROM libros ORDER BY id DESC";
    return $conexion->query($sql);
}

$host = "fdb1029.awardspace.net";
$usuario = "4796121_ecovida";
$password = "contaminacion67";
$baseDatos = "4796121_ecovida";

/** @var mysqli $conexion Instancia activa de conexión */
$conexion = conectarBaseDatos($host, $usuario, $password, $baseDatos);

/** @var mysqli_result|bool $resultado Listado de libros en inventario */
$resultado = obtenerInventarioLibros($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Biblioteca</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
        <h1>Sistema de Control de Biblioteca</h1>
    </header>

    <main class="container">

        <!-- Formulario para agregar libros -->
        <section class="card">
            <h2>Registrar Nuevo Libro</h2>

            <form id="formLibro" action="guardar.php" method="POST">
                <div class="form-group">
                    <label for="titulo">Título del Libro:</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>

                <div class="form-group">
                    <label for="autor">Autor:</label>
                    <input type="text" id="autor" name="autor" required>
                </div>

                <div class="form-group">
                    <label for="isbn">ISBN:</label>
                    <input type="text" id="isbn" name="isbn" required>
                </div>

                <button type="submit" class="btn">Guardar Libro</button>
            </form>
        </section>

        <!-- Tabla de libros en la Base de Datos -->
        <section class="card table-container">
            <h2>Inventario de Libros</h2>

            <table id="tablaLibros">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $fila['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($fila['titulo']); ?></strong></td>
                                <td><?php echo htmlspecialchars($fila['autor']); ?></td>
                                <td><?php echo htmlspecialchars($fila['isbn']); ?></td>
                                <td>
                                    <span class="badge <?php echo $fila['disponible'] ? 'dispo' : 'prestado'; ?>">
                                        <?php echo $fila['disponible'] ? 'Disponible' : 'Prestado'; ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="cambiarEstado.php" method="POST" style="margin: 0;">
                                        <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                                        <input type="hidden" name="actual" value="<?php echo $fila['disponible']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-action <?php echo $fila['disponible'] ? 'btn-prestar' : 'btn-devolver'; ?>"
                                        >
                                            <?php echo $fila['disponible'] ? 'Prestar' : 'Devolver'; ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No hay libros registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </main>

    <script src="script.js"></script>

</body>
</html>
<?php $conexion->close(); ?>
