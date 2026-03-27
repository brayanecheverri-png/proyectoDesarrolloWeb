<?php
// ============================================================
//  Registro de Egresado/Participante
//  Archivo: egresados/registro.php
// ============================================================
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registrar.php');
    exit;
}

try {
    $pdo = conectar();

    // --- Recoger y sanear datos ---
    $num_ident     = trim($_POST['id_number']   ?? '');
    $nombre        = trim($_POST['nombre']       ?? '');
    $apellido      = trim($_POST['apellido']     ?? '');
    $email         = trim($_POST['email']        ?? '');
    $telefono      = trim($_POST['phone']        ?? '');
    $cod_ciudad    = trim($_POST['city']         ?? '') ?: null;
    $cod_nivel_edu = trim($_POST['nivel_edu']    ?? '') ?: null;
    $cod_estado_prof = trim($_POST['estado_prof'] ?? '') ?: null;
    $cod_discapacidad = trim($_POST['discapacidad'] ?? '') ?: null;
    $username      = trim($_POST['username']     ?? '');
    $password      = trim($_POST['password']     ?? '');

    // --- Validaciones básicas ---
    if (!$num_ident || !$nombre || !$apellido || !$email || !$username || !$password) {
        header('Location: registrar.php?error=campos_requeridos');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: registrar.php?error=email_invalido');
        exit;
    }

    // --- Verificar si ya existe el participante ---
    $chk = $pdo->prepare("SELECT num_ident FROM participante WHERE num_ident = ?");
    $chk->execute([$num_ident]);
    if ($chk->fetch()) {
        header('Location: registrar.php?error=ya_registrado');
        exit;
    }

    // --- Verificar si ya existe el usuario ---
    $chkU = $pdo->prepare("SELECT cod_usuario FROM usuario WHERE num_usuario = ?");
    $chkU->execute([$username]);
    if ($chkU->fetch()) {
        header('Location: registrar.php?error=usuario_existente');
        exit;
    }

    // --- Insertar participante ---
    $stmtP = $pdo->prepare("
        INSERT INTO participante
            (num_ident, nombre, apellido, email, telefono, cod_ciudad,
             cod_nivel_educativo, cod_estado_prof, cod_discapacidad)
        VALUES
            (:num_ident, :nombre, :apellido, :email, :telefono, :cod_ciudad,
             :cod_nivel_educativo, :cod_estado_prof, :cod_discapacidad)
    ");
    $stmtP->execute([
        ':num_ident'          => $num_ident,
        ':nombre'             => $nombre,
        ':apellido'           => $apellido,
        ':email'              => $email,
        ':telefono'           => $telefono ?: null,
        ':cod_ciudad'         => $cod_ciudad,
        ':cod_nivel_educativo'=> $cod_nivel_edu,
        ':cod_estado_prof'    => $cod_estado_prof,
        ':cod_discapacidad'   => $cod_discapacidad,
    ]);

    // --- Insertar usuario ---
    $stmtU = $pdo->prepare("
        INSERT INTO usuario
            (cod_usuario, Participante_num_ident, num_usuario, password_hash, estado)
        VALUES
            (:cod_usuario, :num_ident, :num_usuario, :password_hash, b'1')
    ");
    $stmtU->execute([
        ':cod_usuario'   => 'USR-' . $num_ident,
        ':num_ident'     => $num_ident,
        ':num_usuario'   => $username,
        ':password_hash' => password_hash($password, PASSWORD_BCRYPT),
    ]);

    header('Location: registrar.php?success=1&nombre=' . urlencode($nombre . ' ' . $apellido));
    exit;

} catch (PDOException $e) {
    header('Location: registrar.php?error=bd&msg=' . urlencode($e->getMessage()));
    exit;
}
