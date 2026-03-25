<?php
// registro.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturamos los datos enviados por el formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellido = htmlspecialchars($_POST['apellido']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Aquí podrías añadir la conexión a tu base de datos (MySQL)
    
    // Por ahora, simularemos un éxito:
    echo "<h2>¡Registro Procesado!</h2>";
    echo "Se ha registrado a: " . $nombre . " " . $apellido . "<br>";
    echo "Correo de contacto: " . $email;
    
    echo "<br><br><a href='index.php'>Regresar al formulario</a>";
} else {
    // Si intentan entrar directo al archivo sin enviar datos, los mandamos al inicio
    header("Location: index.php");
    exit();
}
?>