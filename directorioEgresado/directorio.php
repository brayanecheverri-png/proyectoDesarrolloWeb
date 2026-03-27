<?php
// ============================================================
//  Directorio de Egresados — con datos reales de bolsa_empleo
// ============================================================
require_once '../conexion.php';

$pdo = conectar();

// Cargar catálogos para filtros
$ciudades  = $pdo->query("SELECT cod_ciudad, nom_ciudad FROM ciudad ORDER BY nom_ciudad")->fetchAll();
$niveles   = $pdo->query("SELECT cod_nivel_educativo, nom_nivel_educativo FROM nivel_educativo")->fetchAll();
$estados_p = $pdo->query("SELECT cod_estado_prof, nom_estado_prof FROM estado_profesional")->fetchAll();

// Filtros activos
$busqueda    = trim($_GET['busqueda']    ?? '');
$cod_ciudad  = trim($_GET['ciudad']      ?? '');
$cod_nivel   = trim($_GET['nivel_edu']   ?? '');
$cod_estado  = trim($_GET['estado_prof'] ?? '');

// Consulta principal
$sql    = "SELECT p.num_ident, p.nombre, p.apellido, p.email, p.telefono,
                  c.nom_ciudad, n.nom_nivel_educativo, ep.nom_estado_prof, p.fecha_registro
           FROM participante p
           LEFT JOIN ciudad c              ON p.cod_ciudad          = c.cod_ciudad
           LEFT JOIN nivel_educativo n     ON p.cod_nivel_educativo = n.cod_nivel_educativo
           LEFT JOIN estado_profesional ep ON p.cod_estado_prof     = ep.cod_estado_prof
           WHERE 1=1";
$params = [];

if ($busqueda) {
    $sql .= " AND (p.nombre LIKE :b1 OR p.apellido LIKE :b2 OR p.num_ident LIKE :b3)";
    $params[':b1'] = $params[':b2'] = $params[':b3'] = "%$busqueda%";
}
if ($cod_ciudad) { $sql .= " AND p.cod_ciudad = :ciudad";       $params[':ciudad']      = $cod_ciudad; }
if ($cod_nivel)  { $sql .= " AND p.cod_nivel_educativo = :niv"; $params[':niv']         = $cod_nivel;  }
if ($cod_estado) { $sql .= " AND p.cod_estado_prof = :est";     $params[':est']         = $cod_estado; }
$sql .= " ORDER BY p.apellido, p.nombre";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$egresados = $stmt->fetchAll();

// KPIs
$total_egresados = $pdo->query("SELECT COUNT(*) FROM participante")->fetchColumn();
$tasa_empleo     = $total_egresados > 0
    ? round($pdo->query("SELECT COUNT(*) FROM participante WHERE cod_estado_prof='EM'")->fetchColumn() / $total_egresados * 100, 1)
    : 0;
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Directorio de Egresados - Observatorio Laboral</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

<nav class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center w-full px-12 h-16 bg-white border-b border-slate-200 text-sm">
    <div class="text-xl font-bold tracking-tighter text-green-800">Observatorio Laboral</div>
    <div class="hidden md:flex items-center space-x-8">
        <a class="text-slate-600 hover:text-green-600 transition-colors" href="../index.html">Inicio</a>
        <a class="text-slate-600 hover:text-green-600 transition-colors" href="../directorioOfertas/directorio.php">Ofertas</a>
        <a class="text-slate-600 hover:text-green-600 transition-colors" href="../directorioEmpresa/directorio.php">Empresas</a>
        <a class="text-green-700 font-semibold border-b-2 border-green-700 pb-1" href="directorio.php">Egresados</a>
    </div>
</nav>

<aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 w-64 border-r border-slate-200 bg-slate-100 text-sm font-medium z-40">
    <div class="px-6 mb-8">
        <h2 class="font-bold text-lg text-slate-800">Gestión</h2>
        <p class="text-slate-500 text-xs">Portal del Observatorio</p>
    </div>
    <nav class="flex-1 space-y-1 pr-4">
        <a class="flex items-center px-6 py-3 text-slate-500 hover:bg-slate-200 transition-all" href="../oferta/registrar.php"><span class="material-symbols-outlined mr-3">post_add</span> Registrar Oferta</a>
        <a class="flex items-center px-6 py-3 text-green-700 bg-white rounded-r-lg shadow-sm font-bold" href="../egresados/registrar.php"><span class="material-symbols-outlined mr-3">school</span> Registrar Egresado</a>
        <a class="flex items-center px-6 py-3 text-slate-500 hover:bg-slate-200 transition-all" href="../empresa/registrar.php"><span class="material-symbols-outlined mr-3">domain</span> Registrar Empresa</a>
        <a class="flex items-center px-6 py-3 text-slate-500 hover:bg-slate-200 transition-all" href="../verOfertaEmpleo/ver.php"><span class="material-symbols-outlined mr-3">description</span> Ver Postulaciones</a>
        <a class="flex items-center px-6 py-3 text-slate-500 hover:bg-slate-200 transition-all" href="../reportes/reportes.php"><span class="material-symbols-outlined mr-3">analytics</span> Reportes</a>
    </nav>
</aside>

<main class="ml-64 mt-16 min-h-screen p-12">
    <header class="mb-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-slate-800 mb-2">Directorio de Egresados</h1>
        <p class="text-slate-500 text-lg">Perfiles profesionales registrados en el Observatorio Laboral.</p>
    </header>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl border border-slate-200">
            <p class="text-xs font-bold uppercase tracking-wider text-green-700 mb-1">Total Egresados</p>
            <p class="text-4xl font-black text-slate-800"><?= number_format($total_egresados) ?></p>
        </div>
        <div class="bg-green-700 p-6 rounded-xl text-white">
            <p class="text-xs font-bold uppercase tracking-wider text-green-200 mb-1">Tasa de Empleo</p>
            <p class="text-4xl font-black"><?= $tasa_empleo ?>%</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Resultados Actuales</p>
            <p class="text-4xl font-black text-slate-800"><?= count($egresados) ?></p>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" action="directorio.php" class="bg-white p-6 rounded-xl border border-slate-200 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <input name="busqueda" value="<?= htmlspecialchars($busqueda) ?>" class="border border-slate-300 rounded-lg px-4 py-2 text-sm col-span-2 focus:ring-2 focus:ring-green-500 focus:outline-none" placeholder="Buscar por nombre, apellido o documento..."/>
        <select name="ciudad" class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">Todas las ciudades</option>
            <?php foreach ($ciudades as $c): ?>
            <option value="<?= $c['cod_ciudad'] ?>" <?= $cod_ciudad === $c['cod_ciudad'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nom_ciudad']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="nivel_edu" class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">Nivel educativo</option>
            <?php foreach ($niveles as $n): ?>
            <option value="<?= $n['cod_nivel_educativo'] ?>" <?= $cod_nivel === $n['cod_nivel_educativo'] ? 'selected' : '' ?>><?= htmlspecialchars($n['nom_nivel_educativo']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="estado_prof" class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">Estado profesional</option>
            <?php foreach ($estados_p as $ep): ?>
            <option value="<?= $ep['cod_estado_prof'] ?>" <?= $cod_estado === $ep['cod_estado_prof'] ? 'selected' : '' ?>><?= htmlspecialchars($ep['nom_estado_prof']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-800 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">search</span> Filtrar
        </button>
        <a href="directorio.php" class="text-slate-500 px-4 py-2 rounded-lg hover:bg-slate-100 text-sm flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">restart_alt</span> Limpiar
        </a>
    </form>

    <!-- Tabla -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-xs">Nombre</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-xs">Documento</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-xs">Contacto</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-xs">Ciudad</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-xs">Nivel Edu.</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-xs">Estado</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600 uppercase tracking-wider text-xs">Registro</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($egresados)): ?>
                <tr><td colspan="7" class="text-center py-16 text-slate-400">
                    <span class="material-symbols-outlined text-5xl block mb-2">search_off</span>
                    No se encontraron egresados con los filtros aplicados.
                </td></tr>
                <?php else: ?>
                <?php foreach ($egresados as $e): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-semibold"><?= htmlspecialchars($e['nombre'] . ' ' . $e['apellido']) ?></td>
                    <td class="px-6 py-4 text-slate-500"><?= htmlspecialchars($e['num_ident']) ?></td>
                    <td class="px-6 py-4">
                        <div class="text-slate-700"><?= htmlspecialchars($e['email'] ?? '—') ?></div>
                        <div class="text-slate-400 text-xs"><?= htmlspecialchars($e['telefono'] ?? '') ?></div>
                    </td>
                    <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($e['nom_ciudad'] ?? '—') ?></td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-full"><?= htmlspecialchars($e['nom_nivel_educativo'] ?? '—') ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full"><?= htmlspecialchars($e['nom_estado_prof'] ?? '—') ?></span>
                    </td>
                    <td class="px-6 py-4 text-slate-400 text-xs"><?= $e['fecha_registro'] ? date('d/m/Y', strtotime($e['fecha_registro'])) : '—' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
