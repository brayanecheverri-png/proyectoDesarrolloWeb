<?php
// ============================================================
//  API CRUD — oferta_trabajo_of
//  Archivo: oferta/oferta_api.php
// ============================================================

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../conexion.php';

$accion = $_GET['accion'] ?? ($_POST['accion'] ?? '');

try {
    $pdo = conectar();

    switch ($accion) {

        // ── LISTAR ──────────────────────────────────────────
        case 'listar':
            $stmt = $pdo->query("
                SELECT o.cod_oferta,
                       o.nom_puesto_trabajo  AS cargo,
                       o.descripcion         AS perfil,
                       o.salario,
                       o.num_vacantes        AS num_vacante,
                       o.horario, o.duracion, o.experiencia,
                       o.requisitos          AS requerimientos,
                       o.fecha_publicacion,
                       o.estado,
                       e.nom_empresa,
                       e.cod_empresa         AS nit_empresa,
                       c.nom_ciudad,
                       o.cod_ciudad,
                       o.cod_nivel           AS cod_nivel_edu,
                       o.cod_idioma,
                       o.cod_discapacidad
                FROM oferta_trabajo_of o
                LEFT JOIN empresa_of e ON o.cod_empresa = e.cod_empresa
                LEFT JOIN ciudad c     ON o.cod_ciudad  = c.cod_ciudad
                ORDER BY o.cod_oferta DESC
            ");
            responder(true, 'OK', ['ofertas' => $stmt->fetchAll()]);
            break;

        // ── OBTENER UNO ──────────────────────────────────────
        case 'obtener':
            $id = filter_input(INPUT_GET, 'cod_oferta', FILTER_VALIDATE_INT);
            if (!$id) responder(false, 'ID inválido.');

            $stmt = $pdo->prepare("
                SELECT o.*, e.nom_empresa, c.nom_ciudad,
                       n.nom_nivel_educativo, i.nom_idioma, d.nom_discapacidad
                FROM oferta_trabajo_of o
                LEFT JOIN empresa_of e      ON o.cod_empresa = e.cod_empresa
                LEFT JOIN ciudad c          ON o.cod_ciudad  = c.cod_ciudad
                LEFT JOIN nivel_educativo n ON o.cod_nivel   = n.cod_nivel_educativo
                LEFT JOIN idioma i          ON o.cod_idioma  = i.cod_idioma
                LEFT JOIN discapacidad d    ON o.cod_discapacidad = d.cod_discapacidad
                WHERE o.cod_oferta = ?
            ");
            $stmt->execute([$id]);
            $oferta = $stmt->fetch();
            if (!$oferta) responder(false, 'Oferta no encontrada.');
            responder(true, 'OK', ['oferta' => $oferta]);
            break;

        // ── CREAR ─────────────────────────────────────────────
        case 'crear':
            // Normalizar campos: el JS envía nombres distintos al esquema de BD
            $_POST['nom_puesto_trabajo'] = trim($_POST['cargo']         ?? $_POST['nom_puesto_trabajo'] ?? '');
            $_POST['descripcion']        = trim($_POST['perfil']        ?? $_POST['descripcion']        ?? '');
            $_POST['requisitos']         = trim($_POST['requerimientos'] ?? $_POST['requisitos']         ?? '');
            $_POST['cod_empresa']        = trim($_POST['nit_empresa']   ?? $_POST['cod_empresa']        ?? '');
            $_POST['num_vacantes']       = trim($_POST['num_vacante']   ?? $_POST['num_vacantes']       ?? '1');
            $_POST['cod_nivel']          = trim($_POST['cod_nivel_edu'] ?? $_POST['cod_nivel']          ?? '');

            $datos = sanitizarPost([
                'nom_puesto_trabajo', 'descripcion', 'requisitos', 'salario',
                'num_vacantes', 'horario', 'duracion', 'experiencia',
                'cod_empresa', 'cod_discapacidad', 'cod_idioma',
                'cod_nivel', 'cod_ciudad'
            ]);

            if (empty($datos['nom_puesto_trabajo'])) responder(false, 'El cargo es obligatorio.');
            if (empty($datos['cod_empresa']))         responder(false, 'La empresa es obligatoria.');

            // Generar num_oferta automático
            $num = 'OF-' . date('Ymd') . '-' . rand(100, 999);

            $sql = "INSERT INTO oferta_trabajo_of
                        (num_oferta, nom_puesto_trabajo, descripcion, requisitos, salario,
                         num_vacantes, horario, duracion, experiencia,
                         cod_empresa, cod_discapacidad, cod_idioma, cod_nivel, cod_ciudad, estado)
                    VALUES
                        (:num_oferta, :nom_puesto_trabajo, :descripcion, :requisitos, :salario,
                         :num_vacantes, :horario, :duracion, :experiencia,
                         :cod_empresa, :cod_discapacidad, :cod_idioma, :cod_nivel, :cod_ciudad, 'AC')";

            $datos['num_oferta'] = $num;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($datos);

            responder(true, 'Oferta registrada correctamente.', ['id' => $pdo->lastInsertId(), 'num_oferta' => $num]);
            break;

        // ── ACTUALIZAR ────────────────────────────────────────
        case 'actualizar':
            $id = filter_input(INPUT_POST, 'cod_oferta', FILTER_VALIDATE_INT);
            if (!$id) responder(false, 'ID inválido.');

            // Normalizar campos igual que en crear
            $_POST['nom_puesto_trabajo'] = trim($_POST['cargo']         ?? $_POST['nom_puesto_trabajo'] ?? '');
            $_POST['descripcion']        = trim($_POST['perfil']        ?? $_POST['descripcion']        ?? '');
            $_POST['requisitos']         = trim($_POST['requerimientos'] ?? $_POST['requisitos']         ?? '');
            $_POST['cod_empresa']        = trim($_POST['nit_empresa']   ?? $_POST['cod_empresa']        ?? '');
            $_POST['num_vacantes']       = trim($_POST['num_vacante']   ?? $_POST['num_vacantes']       ?? '1');
            $_POST['cod_nivel']          = trim($_POST['cod_nivel_edu'] ?? $_POST['cod_nivel']          ?? '');

            $datos = sanitizarPost([
                'nom_puesto_trabajo', 'descripcion', 'requisitos', 'salario',
                'num_vacantes', 'horario', 'duracion', 'experiencia',
                'cod_empresa', 'cod_discapacidad', 'cod_idioma', 'cod_nivel', 'cod_ciudad'
            ]);

            if (empty($datos['nom_puesto_trabajo'])) responder(false, 'El cargo es obligatorio.');

            $datos['cod_oferta'] = $id;
            $sql = "UPDATE oferta_trabajo_of SET
                        nom_puesto_trabajo = :nom_puesto_trabajo,
                        descripcion        = :descripcion,
                        requisitos         = :requisitos,
                        salario            = :salario,
                        num_vacantes       = :num_vacantes,
                        horario            = :horario,
                        duracion           = :duracion,
                        experiencia        = :experiencia,
                        cod_empresa        = :cod_empresa,
                        cod_discapacidad   = :cod_discapacidad,
                        cod_idioma         = :cod_idioma,
                        cod_nivel          = :cod_nivel,
                        cod_ciudad         = :cod_ciudad
                    WHERE cod_oferta = :cod_oferta";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($datos);
            responder(true, 'Oferta actualizada correctamente.');
            break;

        // ── CAMBIAR ESTADO ────────────────────────────────────
        case 'cambiar_estado':
            $id     = filter_input(INPUT_POST, 'cod_oferta', FILTER_VALIDATE_INT);
            $estado = trim($_POST['estado'] ?? '');
            if (!$id || !in_array($estado, ['AC', 'IN', 'CE'])) responder(false, 'Datos inválidos.');

            $pdo->prepare("UPDATE oferta_trabajo_of SET estado = ? WHERE cod_oferta = ?")
                ->execute([$estado, $id]);
            responder(true, 'Estado actualizado.');
            break;

        // ── ELIMINAR ──────────────────────────────────────────
        case 'eliminar':
            $id = filter_input(INPUT_POST, 'cod_oferta', FILTER_VALIDATE_INT);
            if (!$id) responder(false, 'ID inválido.');

            // Eliminar postulaciones relacionadas primero
            $pdo->prepare("DELETE FROM postulacion WHERE cod_oferta = ?")->execute([$id]);

            $stmt = $pdo->prepare("DELETE FROM oferta_trabajo_of WHERE cod_oferta = ?");
            $stmt->execute([$id]);
            if ($stmt->rowCount() === 0) responder(false, 'Oferta no encontrada.');
            responder(true, 'Oferta eliminada correctamente.');
            break;

        // ── CATÁLOGOS (para llenar selects del formulario) ───
        case 'catalogos':
            $empresas  = $pdo->query("SELECT cod_empresa, nom_empresa FROM empresa_of ORDER BY nom_empresa")->fetchAll();
            $ciudades  = $pdo->query("SELECT cod_ciudad, nom_ciudad FROM ciudad ORDER BY nom_ciudad")->fetchAll();
            $niveles   = $pdo->query("SELECT cod_nivel_educativo AS cod, nom_nivel_educativo AS nom FROM nivel_educativo")->fetchAll();
            $idiomas   = $pdo->query("SELECT cod_idioma AS cod, nom_idioma AS nom FROM idioma")->fetchAll();
            $discapac  = $pdo->query("SELECT cod_discapacidad AS cod, nom_discapacidad AS nom FROM discapacidad")->fetchAll();
            responder(true, 'OK', compact('empresas', 'ciudades', 'niveles', 'idiomas', 'discapac'));
            break;

        default:
            responder(false, 'Acción no reconocida: ' . htmlspecialchars($accion));
    }

} catch (PDOException $e) {
    responder(false, 'Error de base de datos: ' . $e->getMessage());
} catch (Throwable $e) {
    responder(false, 'Error inesperado: ' . $e->getMessage());
}

// ── Helpers ─────────────────────────────────────────────────
function sanitizarPost(array $campos): array {
    $datos = [];
    foreach ($campos as $campo) {
        $valor = trim($_POST[$campo] ?? '');
        $datos[$campo] = $valor !== '' ? $valor : null;
    }
    return $datos;
}
