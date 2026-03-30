/* =========================================
   login.js — Observatorio Laboral
   Envía el formulario por AJAX a login.php
   y redirige según el rol del usuario.
   ========================================= */

document.addEventListener('DOMContentLoaded', function () {

    var formulario    = document.getElementById('formulario-login');
    var campoUsuario  = document.getElementById('usuario');
    var campoPassword = document.getElementById('password');
    var botonEnviar   = document.getElementById('boton-login');
    var mensajeError  = document.getElementById('mensaje-error');

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();
        ocultarError();

        var usuario  = campoUsuario.value.trim();
        var password = campoPassword.value.trim();

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

        activarCarga(true);

        var datos = new FormData();
        datos.append('usuario', usuario);
        datos.append('password', password);

        fetch('login.php', { method: 'POST', body: datos })
            .then(function (respuesta) { return respuesta.json(); })
            .then(function (json) {
                if (json.exito) {
                    // Redirigir al panel según el rol devuelto por el servidor
                    window.location.href = json.redirigir;
                } else {
                    mostrarError(json.mensaje);
                }
            })
            .catch(function () {
                mostrarError('Ocurrió un error al conectar con el servidor. Intenta de nuevo.');
            })
            .finally(function () {
                activarCarga(false);
            });
    });

    function mostrarError(texto) {
        mensajeError.textContent = texto;
        mensajeError.style.display = 'block';
    }
    function ocultarError() {
        mensajeError.textContent = '';
        mensajeError.style.display = 'none';
    }
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
