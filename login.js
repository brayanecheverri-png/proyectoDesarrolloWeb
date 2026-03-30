/* =========================================
   login.js — Labor Observatory Login
   =========================================
   Envía el formulario por AJAX al archivo
   login.php y muestra el resultado al usuario.
   ========================================= */

// Esperamos a que el HTML esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {

    // --- Elementos del DOM ---
    var formulario     = document.getElementById('formulario-login');
    var campoUsuario   = document.getElementById('usuario');
    var campoPassword  = document.getElementById('password');
    var botonEnviar    = document.getElementById('boton-login');
    var mensajeError   = document.getElementById('mensaje-error');

    // --- Envío del formulario ---
    formulario.addEventListener('submit', function (evento) {

        // Evitamos que la página se recargue
        evento.preventDefault();

        // Limpiamos mensajes anteriores
        ocultarError();

        // Leemos los valores escritos por el usuario
        var usuario  = campoUsuario.value.trim();
        var password = campoPassword.value.trim();

        // --- Validación básica en el cliente ---
        if (usuario === '') {
            mostrarError('Por favor ingresa tu usuario.');
            campoUsuario.focus();
            return;
        }

        if (password === '') {
            mostrarError('Por favor ingresa tu contraseña.');
            campoPassword.focus();
            return;
        }

        // Mostramos el spinner y deshabilitamos el botón
        activarCarga(true);

        // --- Petición AJAX a login.php ---
        var datos = new FormData();
        datos.append('usuario', usuario);
        datos.append('password', password);

        fetch('login.php', {
            method: 'POST',
            body: datos
        })
        .then(function (respuesta) {
            // Convertimos la respuesta JSON de PHP
            return respuesta.json();
        })
        .then(function (json) {

            if (json.exito) {
                // Login correcto → redirigimos al dashboard
                window.location.href = json.redirigir;
            } else {
                // Login incorrecto → mostramos el mensaje de PHP
                mostrarError(json.mensaje);
            }

        })
        .catch(function () {
            // Error de red o de servidor
            mostrarError('Ocurrió un error al conectar con el servidor. Intenta de nuevo.');
        })
        .finally(function () {
            // Siempre quitamos el spinner al terminar
            activarCarga(false);
        });

    });

    /* --------------------------------------------------
       Funciones auxiliares
    -------------------------------------------------- */

    // Muestra el div de error con un mensaje
    function mostrarError(texto) {
        mensajeError.textContent = texto;
        mensajeError.style.display = 'block';
    }

    // Oculta el div de error
    function ocultarError() {
        mensajeError.textContent = '';
        mensajeError.style.display = 'none';
    }

    // Activa o desactiva el estado de carga del botón
    function activarCarga(cargando) {
        if (cargando) {
            botonEnviar.disabled = true;
            botonEnviar.innerHTML = '<span class="spinner"></span><span>Verificando...</span>';
        } else {
            botonEnviar.disabled = false;
            botonEnviar.innerHTML = '<span>Iniciar Sesión</span><span class="material-symbols-outlined text-lg">login</span>';
        }
    }

});
