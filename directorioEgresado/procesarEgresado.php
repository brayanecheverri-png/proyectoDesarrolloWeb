<?php
// ============================================================
//  Directorio Egresados — Procesador de búsqueda/filtros
//  Archivo: directorioEgresado/procesarEgresado.php
// ============================================================
require_once '../conexion.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo        = conectar();
    $busqueda   = trim($_GET['busqueda']    ?? '');
    $ciudad     = trim($_GET['ciudad']      ?? '');
    $nivel_edu  = trim($_GET['nivel_edu']   ?? '');
    $estado_prof= trim($_GET['estado_prof'] ?? '');

    $sql = "SELECT p.num_ident, p.nombre, p.apellido, p.email, p.telefono,
                   c.nom_ciudad, pa.nom_pais,
                   n.nom_nivel_educativo,
                   ep.nom_estado_prof,
                   d.nom_discapacidad,
                   p.fecha_registro
            FROM participante p
            LEFT JOIN ciudad c          ON p.cod_ciudad          = c.cod_ciudad
            LEFT JOIN pais pa           ON c.cod_pais             = pa.cod_pais
            LEFT JOIN nivel_educativo n ON p.cod_nivel_educativo  = n.cod_nivel_educativo
            LEFT JOIN estado_profesional ep ON p.cod_estado_prof  = ep.cod_estado_prof
            LEFT JOIN discapacidad d    ON p.cod_discapacidad     = d.cod_discapacidad
            WHERE 1=1";
    $params = [];

    if ($busqueda) {
        $sql .= " AND (p.nombre LIKE :b1 OR p.apellido LIKE :b2 OR p.num_ident LIKE :b3)";
        $params[':b1'] = "%$busqueda%";
        $params[':b2'] = "%$busqueda%";
        $params[':b3'] = "%$busqueda%";
    }
    if ($ciudad) {
        $sql .= " AND p.cod_ciudad = :ciudad";
        $params[':ciudad'] = $ciudad;
    }
    if ($nivel_edu) {
        $sql .= " AND p.cod_nivel_educativo = :nivel_edu";
        $params[':nivel_edu'] = $nivel_edu;
    }
    if ($estado_prof) {
        $sql .= " AND p.cod_estado_prof = :estado_prof";
        $params[':estado_prof'] = $estado_prof;
    }

    $sql .= " ORDER BY p.apellido ASC, p.nombre ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['ok' => true, 'egresados' => $stmt->fetchAll()]);

} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'mensaje' => $e->getMessage()]);
}
