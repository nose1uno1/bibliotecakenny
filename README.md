3
* **Base de datos:** MySQL.

## Estructura del proyecto

* `index.php` — Vista principal con el formulario de registro y la tabla de inventario.
* `guardar.php` — Procesa e inserta los nuevos libros en la base de datos.
* `cambiarEstado.php` — Actualiza la disponibilidad de un libro (Prestar / Devolver).
* `styles.css` — Estilos visuales y diseño responsivo.
* `script.js` — Validación del formulario y alertas de confirmación.

## Configuración de la base de datos

Para ejecutar el proyecto en tu propio servidor, crea una tabla llamada `libros` en MySQL con la siguiente estructura:

```sql
CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    isbn VARCHAR(20) NOT NULL,
    disponible TINYINT(1) DEFAULT 1
);
