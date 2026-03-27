<?php
// ============================================================
//  Directorio Ofertas — Procesador de búsqueda/filtros
//  Archivo: directorioOfertas/procesar.php
// ============================================================
require_once '../conexion.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo     = conectar();
    $query   = trim($_GET['query'] ?? '');
    $ciudad  = trim($_GET['ciudad'] ?? '');
    $nivel   = trim($_GET['nivel']  ?? '');
    $idioma  = trim($_GET['idioma'] ?? '');

    $sql = "SELECT o.cod_oferta, o.nom_puesto_trabajo, o.descripcion,
                   o.salario, o.num_vacantes, o.horario, o.experiencia,
                   o.fecha_publicacion, o.estado,
                   e.nom_empresa, e.email AS email_empresa,
                   c.nom_ciudad,
                   n.nom_nivel_educativo,
                   i.nom_idioma,
                   d.nom_discapacidad
            FROM oferta_trabajo_of o
            LEFT JOIN empresa_of e      ON o.cod_empresa = e.cod_empresa
            LEFT JOIN ciudad c          ON o.cod_ciudad  = c.cod_ciudad
            LEFT JOIN nivel_educativo n ON o.cod_nivel   = n.cod_nivel_educativo
            LEFT JOIN idioma i          ON o.cod_idioma  = i.cod_idioma
            LEFT JOIN discapacidad d    ON o.cod_discapacidad = d.cod_discapacidad
            WHERE o.estado = 'AC'";
    $params = [];

    if ($query) {
        $sql .= " AND (o.nom_puesto_trabajo LIKE :q1 OR e.nom_empresa LIKE :q2 OR o.descripcion LIKE :q3)";
        $params[':q1'] = "%$query%";
        $params[':q2'] = "%$query%";
        $params[':q3'] = "%$query%";
    }
    if ($ciudad) {
        $sql .= " AND o.cod_ciudad = :ciudad";
        $params[':ciudad'] = $ciudad;
    }
    if ($nivel) {
        $sql .= " AND o.cod_nivel = :nivel";
        $params[':nivel'] = $nivel;
    }
    if ($idioma) {
        $sql .= " AND o.cod_idioma = :idioma";
        $params[':idioma'] = $idioma;
    }

    $sql .= " ORDER BY o.fecha_publicacion DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['ok' => true, 'ofertas' => $stmt->fetchAll()]);

} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'mensaje' => $e->getMessage()]);
}
