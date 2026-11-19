/**
 * @fileoverview Este script gestiona las validaciones del formulario de libros
 * y las notificaciones de estado en la interfaz de biblioteca.
 * @version 1.1.0
 * @author Kenny <kenny@ejemplo.com>
 */

/**
 * Representa los datos ingresados para un libro en el formulario.
 * @typedef {Object} LibroForm
 * @property {string} titulo - El título del libro.
 * @property {string} autor - El nombre del autor.
 * @property {string} isbn - El código identificador ISBN.
 */

/**
 * Valida que los campos de un libro no estén vacíos y que el ISBN
 * tenga al menos 10 caracteres de longitud.
 *
 * @param {LibroForm} libro - El objeto con los datos del formulario a evaluar.
 * @returns {boolean} True si los datos son válidos para enviarse al servidor.
 * @throws {Error} Si algún campo está vacío o si el ISBN es menor a 10 caracteres.
 */
function validarDatosLibro(libro) {
  if (libro.titulo === '' || libro.autor === '' || libro.isbn === '') {
    throw new Error('Por favor, rellena todos los campos antes de guardar.');
  }

  if (libro.isbn.length < 10) {
    throw new Error('El campo ISBN parece demasiado corto o inválido.');
  }

  return true;
}

/**
 * Verifica los parámetros de la URL para mostrar una alerta si el libro
 * fue registrado correctamente y limpia la dirección del navegador.
 *
 * @returns {void}
 */
function verificarNotificacionRegistro() {
  /** @type {URLSearchParams} */
  const urlParams = new URLSearchParams(window.location.search);

  if (urlParams.get('success') === '1') {
    window.history.replaceState({}, document.title, window.location.pathname);
    alert('¡Libro registrado con éxito en el inventario!');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  /** @type {HTMLFormElement|null} */
  const formLibro = document.getElementById('formLibro');

  if (formLibro) {
    formLibro.addEventListener('submit', (event) => {
      /** @type {LibroForm} */
      const datosLibro = {
        titulo: document.getElementById('titulo').value.trim(),
        autor: document.getElementById('autor').value.trim(),
        isbn: document.getElementById('isbn').value.trim(),
      };

      try {
        // Tu editor (IDE) mostrará ayuda contextual al invocar esta función:
        validarDatosLibro(datosLibro);
      } catch (error) {
        event.preventDefault();
        alert(error.message);
      }
    });
  }

  verificarNotificacionRegistro();
});
