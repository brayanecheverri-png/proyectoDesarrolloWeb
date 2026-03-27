<?php
// ============================================================
//  Registro de Empresa
//  Archivo: empresa/registro.php
// ============================================================
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registrar.php');
    exit;
}

try {
    $pdo = conectar();

    $cod_empresa      = trim($_POST['nit']          ?? '');
    $nom_empresa      = trim($_POST['nombre']        ?? '');
    $nom_representante= trim($_POST['contacto']      ?? '') ?: null;
    $email            = trim($_POST['email']         ?? '') ?: null;
    $telefono         = trim($_POST['telefono']      ?? '') ?: null;
    $direccion        = trim($_POST['direccion']     ?? '') ?: null;
    $cod_ciudad       = trim($_POST['ciudad']        ?? '') ?: null;

    if (!$cod_empresa || !$nom_empresa) {
        header('Location: registrar.php?error=campos_requeridos');
        exit;
    }

    // Verificar si ya existe
    $chk = $pdo->prepare("SELECT cod_empresa FROM empresa_of WHERE cod_empresa = ?");
    $chk->execute([$cod_empresa]);
    if ($chk->fetch()) {
        header('Location: registrar.php?error=ya_registrada');
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO empresa_of
            (cod_empresa, nom_empresa, num_ruc, nom_representante,
             direccion, telefono, email, cod_ciudad)
        VALUES
            (:cod_empresa, :nom_empresa, :num_ruc, :nom_representante,
             :direccion, :telefono, :email, :cod_ciudad)
    ");
    $stmt->execute([
        ':cod_empresa'       => $cod_empresa,
        ':nom_empresa'       => $nom_empresa,
        ':num_ruc'           => $cod_empresa,   // NIT también como RUC
        ':nom_representante' => $nom_representante,
        ':direccion'         => $direccion,
        ':telefono'          => $telefono,
        ':email'             => $email,
        ':cod_ciudad'        => $cod_ciudad,
    ]);

    header('Location: registrar.php?success=1&nombre=' . urlencode($nom_empresa));
    exit;

} catch (PDOException $e) {
    header('Location: registrar.php?error=bd&msg=' . urlencode($e->getMessage()));
    exit;
}
