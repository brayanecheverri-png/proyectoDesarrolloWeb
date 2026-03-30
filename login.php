<?php
/* =========================================
   login.php — Observatorio Laboral
   =========================================
   Recibe usuario y contraseña por POST,
   los valida contra la tabla `usuario` de
   la BD bolsa_empleo y responde con JSON.
   ========================================= */

require_once 'conexion.php';

// --- Solo aceptamos peticiones POST ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(false, 'Método no permitido.');
}

// --- Leemos y limpiamos los datos recibidos ---
$usuario  = isset($_POST['usuario'])  ? trim($_POST['usuario'])  : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// --- Validación básica en el servidor ---
if ($usuario === '' || $password === '') {
    responder(false, 'Usuario y contraseña son obligatorios.');
}

try {
    $pdo = conectar();
} catch (Throwable $e) {
    responder(false, 'Error de conexión con la base de datos.');
}

/*
   Tabla `usuario` (BD bolsa_empleo):
     - cod_usuario              (PK)
     - Participante_num_ident   (FK → participante)
     - num_usuario              (nombre de usuario, UNIQUE)
     - password_hash            (cifrado con password_hash())
     - estado                   (bit: 1=activo, 0=inactivo)
*/
$sql  = 'SELECT u.cod_usuario, u.num_usuario, u.password_hash, u.estado,
                p.nombre, p.apellido
         FROM usuario u
         JOIN participante p ON p.num_ident = u.Participante_num_ident
         WHERE u.num_usuario = :usuario
         LIMIT 1';
$stmt = $pdo->prepare($sql);
$stmt->execute([':usuario' => $usuario]);
$fila = $stmt->fetch(PDO::FETCH_ASSOC);

// --- Verificamos si el usuario existe ---
if (!$fila) {
    responder(false, 'Usuario o contraseña incorrectos.');
}

// --- Verificamos si la cuenta está activa ---
// MySQL devuelve BIT(1) como cadena binaria; cubrimos todas las formas posibles
$activo = $fila['estado'];
if ($activo !== chr(1) && $activo !== '1' && $activo !== 1 && $activo !== "\x01") {
    responder(false, 'Tu cuenta está inactiva. Contacta al soporte.');
}

// --- Comparamos la contraseña con el hash guardado ---
if (!password_verify($password, $fila['password_hash'])) {
    responder(false, 'Usuario o contraseña incorrectos.');
}

// --- Todo correcto: iniciamos la sesión ---
session_start();
$_SESSION['usuario_id']  = $fila['cod_usuario'];
$_SESSION['num_usuario'] = $fila['num_usuario'];
$_SESSION['nombre']      = $fila['nombre'] . ' ' . $fila['apellido'];

// Respondemos con éxito
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'exito'     => true,
    'mensaje'   => 'Acceso correcto.',
    'redirigir' => 'index.html'
]);
exit;
?>
