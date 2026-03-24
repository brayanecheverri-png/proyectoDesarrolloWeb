<?php
// ============================================================
//  API CRUD — oferta_trabajo_tl
//  Archivo: oferta_api.php
// ============================================================

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// ---------- Incluir configuración de BD ----------
require_once '../conexion.php';

// ---------- Router ----------
$accion = $_GET['accion'] ?? ($_POST['accion'] ?? '');

try {
    $pdo = conectar();

    switch ($accion) {

        // ── LISTAR ──────────────────────────────────────────
        case 'listar':
            $stmt = $pdo->query("
                SELECT cod_oferta, cargo, perfil, salario,
                       num_vacante, cod_ciudad, nit_empresa
                FROM oferta_trabajo_tl
                ORDER BY cod_oferta DESC
            ");
            responder(true, 'OK', ['ofertas' => $stmt->fetchAll()]);

        // ── OBTENER UNO ──────────────────────────────────────
        case 'obtener':
            $id = filter_input(INPUT_GET, 'cod_oferta', FILTER_VALIDATE_INT);
            if (!$id) responder(false, 'ID inválido.');

            $stmt = $pdo->prepare("SELECT * FROM oferta_trabajo_tl WHERE cod_oferta = ?");
            $stmt->execute([$id]);
            $oferta = $stmt->fetch();

            if (!$oferta) responder(false, 'Oferta no encontrada.');
            responder(true, 'OK', ['oferta' => $oferta]);

        // ── CREAR ─────────────────────────────────────────────
        case 'crear':
            $datos = sanitizarPost([
                'cargo', 'perfil', 'salario', 'requerimientos', 'experiencia',
                'num_vacante', 'horario', 'duracion', 'nom_software_maneja',
                'nivel_manejo_software', 'nit_empresa', 'cod_discapacidad',
                'cod_contrato', 'cod_nivel_edu', 'cod_idioma', 'cod_ciudad', 'cod_titulo'
            ]);

            if (empty($datos['cargo']))       responder(false, 'El campo cargo es obligatorio.');
            if (empty($datos['nit_empresa'])) responder(false, 'El NIT de empresa es obligatorio.');

            $sql = "INSERT INTO oferta_trabajo_tl
                        (cargo, perfil, salario, requerimientos, experiencia,
                         num_vacante, horario, duracion, nom_software_maneja,
                         nivel_manejo_software, nit_empresa, cod_discapacidad,
                         cod_contrato, cod_nivel_edu, cod_idioma, cod_ciudad, cod_titulo)
                    VALUES
                        (:cargo, :perfil, :salario, :requerimientos, :experiencia,
                         :num_vacante, :horario, :duracion, :nom_software_maneja,
                         :nivel_manejo_software, :nit_empresa, :cod_discapacidad,
                         :cod_contrato, :cod_nivel_edu, :cod_idioma, :cod_ciudad, :cod_titulo)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($datos);

            responder(true, 'Oferta registrada correctamente.', ['id' => $pdo->lastInsertId()]);

        // ── ACTUALIZAR ────────────────────────────────────────
        case 'actualizar':
            $id = filter_input(INPUT_POST, 'cod_oferta', FILTER_VALIDATE_INT);
            if (!$id) responder(false, 'ID inválido.');

            $datos = sanitizarPost([
                'cargo', 'perfil', 'salario', 'requerimientos', 'experiencia',
                'num_vacante', 'horario', 'duracion', 'nom_software_maneja',
                'nivel_manejo_software', 'nit_empresa', 'cod_discapacidad',
                'cod_contrato', 'cod_nivel_edu', 'cod_idioma', 'cod_ciudad', 'cod_titulo'
            ]);

            if (empty($datos['cargo']))       responder(false, 'El campo cargo es obligatorio.');
            if (empty($datos['nit_empresa'])) responder(false, 'El NIT de empresa es obligatorio.');

            $datos['cod_oferta'] = $id;

            $sql = "UPDATE oferta_trabajo_tl SET
                        cargo                = :cargo,
                        perfil               = :perfil,
                        salario              = :salario,
                        requerimientos       = :requerimientos,
                        experiencia          = :experiencia,
                        num_vacante          = :num_vacante,
                        horario              = :horario,
                        duracion             = :duracion,
                        nom_software_maneja  = :nom_software_maneja,
                        nivel_manejo_software= :nivel_manejo_software,
                        nit_empresa          = :nit_empresa,
                        cod_discapacidad     = :cod_discapacidad,
                        cod_contrato         = :cod_contrato,
                        cod_nivel_edu        = :cod_nivel_edu,
                        cod_idioma           = :cod_idioma,
                        cod_ciudad           = :cod_ciudad,
                        cod_titulo           = :cod_titulo
                    WHERE cod_oferta = :cod_oferta";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($datos);

            responder(true, 'Oferta actualizada correctamente.');

        // ── ELIMINAR ──────────────────────────────────────────
        case 'eliminar':
            $id = filter_input(INPUT_POST, 'cod_oferta', FILTER_VALIDATE_INT);
            if (!$id) responder(false, 'ID inválido.');

            // Eliminar postulaciones relacionadas primero (integridad referencial)
            $pdo->prepare("DELETE FROM postulaciones_tl WHERE cod_oferta = ?")->execute([$id]);

            $stmt = $pdo->prepare("DELETE FROM oferta_trabajo_tl WHERE cod_oferta = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) responder(false, 'No se encontró la oferta.');
            responder(true, 'Oferta eliminada correctamente.');

        default:
            responder(false, 'Acción no reconocida: ' . htmlspecialchars($accion));
    }

} catch (PDOException $e) {
    responder(false, 'Error de base de datos: ' . $e->getMessage());
} catch (Throwable $e) {
    responder(false, 'Error inesperado: ' . $e->getMessage());
}

// ============================================================
//  Helpers
// ============================================================

/**
 * Limpia y retorna solo los campos esperados del POST.
 * Devuelve null para campos vacíos (para no guardar cadenas vacías en la BD).
 */
function sanitizarPost(array $campos): array {
    $datos = [];
    foreach ($campos as $campo) {
        $valor = $_POST[$campo] ?? '';
        $valor = trim($valor);
        $datos[$campo] = $valor !== '' ? $valor : null;
    }
    return $datos;
}