<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nit = $_POST['nit'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $contacto = $_POST['contacto'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $pais = $_POST['pais'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $direccion = $_POST['direccion'] ?? '';

    // Aquí iría la lógica para guardar en Base de Datos
    
    echo "<h1>Registro Recibido</h1>";
    echo "La empresa " . htmlspecialchars($nombre) . " ha sido registrada correctamente.";
    // Redireccionar o mostrar mensaje de éxito
} else {
    header("Location: index.php");
    exit();
}
?>