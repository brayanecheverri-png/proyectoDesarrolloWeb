<?php
/* =========================================
   login.php — Observatorio Laboral
   =========================================
   Detecta rol (egresado/empresa) y guarda
   el tipo en sesión para control de acceso.
   ========================================= */

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(false, 'Método no permitido.');
}

$usuario  = isset($_POST['usuario'])  ? trim($_POST['usuario'])  : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if ($usuario === '' || $password === '') {
    responder(false, 'Usuario y contraseña son obligatorios.');
}

try {
    $pdo = conectar();
} catch (Throwable $e) {
    responder(false, 'Error de conexión con la base de datos.');
}

$sql  = 'SELECT u.cod_usuario, u.num_usuario, u.password_hash, u.estado,
                p.nombre, p.apellido, p.num_ident, p.email
         FROM usuario u
         JOIN participante p ON p.num_ident = u.Participante_num_ident
         WHERE u.num_usuario = :usuario
         LIMIT 1';
$stmt = $pdo->prepare($sql);
$stmt->execute([':usuario' => $usuario]);
$fila = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fila) {
    responder(false, 'Usuario o contraseña incorrectos.');
}

$activo = $fila['estado'];
if ($activo !== chr(1) && $activo !== '1' && $activo !== 1 && $activo !== "\x01") {
    responder(false, 'Tu cuenta está inactiva. Contacta al soporte.');
}

if (!password_verify($password, $fila['password_hash'])) {
    responder(false, 'Usuario o contraseña incorrectos.');
}

// --- Detectar rol ---
// Convención: cod_usuario empieza con 'EMP-' para empresas
$cod_empresa  = null;
$tipo_usuario = 'egresado';

if (strpos($fila['cod_usuario'], 'EMP-') === 0) {
    $tipo_usuario = 'empresa';
    $cod_empresa  = substr($fila['cod_usuario'], 4);
} else {
    // Fallback: buscar empresa cuyo email coincida con el participante
    $stmtE = $pdo->prepare(
        "SELECT cod_empresa FROM empresa_of WHERE email = :email LIMIT 1"
    );
    $stmtE->execute([':email' => $fila['email']]);
    $emp = $stmtE->fetch(PDO::FETCH_ASSOC);
    if ($emp) {
        $tipo_usuario = 'empresa';
        $cod_empresa  = $emp['cod_empresa'];
    }
}

// --- Iniciar sesión ---
session_start();
$_SESSION['usuario_id']   = $fila['cod_usuario'];
$_SESSION['num_usuario']  = $fila['num_usuario'];
$_SESSION['nombre']       = $fila['nombre'] . ' ' . $fila['apellido'];
$_SESSION['num_ident']    = $fila['num_ident'];
$_SESSION['tipo_usuario'] = $tipo_usuario;   // 'egresado' | 'empresa'
$_SESSION['cod_empresa']  = $cod_empresa;    // null si es egresado

// Redirigir al panel correcto
$destino = ($tipo_usuario === 'empresa') ? 'panel_empresa.php' : 'panel_egresado.php';

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'exito'        => true,
    'mensaje'      => 'Acceso correcto.',
    'tipo_usuario' => $tipo_usuario,
    'redirigir'    => $destino
]);
exit;
?>
