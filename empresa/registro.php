<?php
/* ============================================================
   empresa/registro.php
   Registra empresa + participante-representante + usuario EMP-
   ============================================================ */
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registrar.php');
    exit;
}

try {
    $pdo = conectar();

    // Datos de empresa
    $cod_empresa       = trim($_POST['nit']       ?? '');
    $nom_empresa       = trim($_POST['nombre']     ?? '');
    $nom_representante = trim($_POST['contacto']   ?? '') ?: null;
    $email             = trim($_POST['email']      ?? '') ?: null;
    $telefono          = trim($_POST['telefono']   ?? '') ?: null;
    $direccion         = trim($_POST['direccion']  ?? '') ?: null;
    $cod_ciudad        = trim($_POST['ciudad']     ?? '') ?: null;

    // Datos de usuario para login
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password2 = trim($_POST['password2'] ?? '');

    // Número de identificación del representante (para participante)
    $num_ident_rep = trim($_POST['num_ident'] ?? $cod_empresa);

    if (!$cod_empresa || !$nom_empresa) {
        header('Location: registrar.php?error=campos_requeridos');
        exit;
    }

    if ($username && $password && $password !== $password2) {
        header('Location: registrar.php?error=passwords_no_coinciden');
        exit;
    }

    // Verificar si empresa ya existe
    $chk = $pdo->prepare("SELECT cod_empresa FROM empresa_of WHERE cod_empresa = ?");
    $chk->execute([$cod_empresa]);
    if ($chk->fetch()) {
        header('Location: registrar.php?error=ya_registrada');
        exit;
    }

    $pdo->beginTransaction();

    // 1. Insertar empresa
    $stmtEmp = $pdo->prepare("
        INSERT INTO empresa_of
            (cod_empresa, nom_empresa, num_ruc, nom_representante, direccion, telefono, email, cod_ciudad)
        VALUES
            (:cod_empresa, :nom_empresa, :num_ruc, :nom_representante, :direccion, :telefono, :email, :cod_ciudad)
    ");
    $stmtEmp->execute([
        ':cod_empresa'       => $cod_empresa,
        ':nom_empresa'       => $nom_empresa,
        ':num_ruc'           => $cod_empresa,
        ':nom_representante' => $nom_representante,
        ':direccion'         => $direccion,
        ':telefono'          => $telefono,
        ':email'             => $email,
        ':cod_ciudad'        => $cod_ciudad,
    ]);

    // 2. Insertar participante-representante (si tiene credenciales de usuario)
    if ($username && $password && $num_ident_rep) {
        // Verificar si el participante ya existe
        $chkP = $pdo->prepare("SELECT num_ident FROM participante WHERE num_ident = ?");
        $chkP->execute([$num_ident_rep]);
        if (!$chkP->fetch()) {
            $nombreParts = explode(' ', $nom_representante ?? 'Representante Empresa', 2);
            $stmtP = $pdo->prepare("
                INSERT INTO participante (num_ident, nombre, apellido, email, telefono, cod_ciudad)
                VALUES (:ident, :nombre, :apellido, :email, :tel, :ciudad)
            ");
            $stmtP->execute([
                ':ident'    => $num_ident_rep,
                ':nombre'   => $nombreParts[0],
                ':apellido' => $nombreParts[1] ?? 'Empresa',
                ':email'    => $email,
                ':tel'      => $telefono,
                ':ciudad'   => $cod_ciudad,
            ]);
        }

        // 3. Insertar usuario con prefijo EMP-
        $cod_usuario_emp = 'EMP-' . $cod_empresa;
        $chkU = $pdo->prepare("SELECT cod_usuario FROM usuario WHERE num_usuario = ? OR cod_usuario = ?");
        $chkU->execute([$username, $cod_usuario_emp]);
        if (!$chkU->fetch()) {
            $stmtU = $pdo->prepare("
                INSERT INTO usuario (cod_usuario, Participante_num_ident, num_usuario, password_hash, estado)
                VALUES (:cod_usuario, :num_ident, :num_usuario, :password_hash, b'1')
            ");
            $stmtU->execute([
                ':cod_usuario'   => $cod_usuario_emp,
                ':num_ident'     => $num_ident_rep,
                ':num_usuario'   => $username,
                ':password_hash' => password_hash($password, PASSWORD_BCRYPT),
            ]);
        }
    }

    $pdo->commit();
    header('Location: registrar.php?success=1&nombre=' . urlencode($nom_empresa));
    exit;

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    header('Location: registrar.php?error=bd&msg=' . urlencode($e->getMessage()));
    exit;
}
?>
