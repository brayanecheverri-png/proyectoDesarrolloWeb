<?php
// ============================================================
//  Directorio de Ofertas — con datos reales de bolsa_empleo
// ============================================================
require_once '../conexion.php';

$pdo = conectar();

$ciudades = $pdo->query("SELECT cod_ciudad, nom_ciudad FROM ciudad ORDER BY nom_ciudad")->fetchAll();
$niveles  = $pdo->query("SELECT cod_nivel_educativo, nom_nivel_educativo FROM nivel_educativo")->fetchAll();
$idiomas  = $pdo->query("SELECT cod_idioma, nom_idioma FROM idioma")->fetchAll();

$query      = trim($_GET['query']   ?? '');
$cod_ciudad = trim($_GET['ciudad']  ?? '');
$cod_nivel  = trim($_GET['nivel']   ?? '');
$cod_idioma = trim($_GET['idioma']  ?? '');

$sql    = "SELECT o.cod_oferta, o.nom_puesto_trabajo, o.descripcion, o.salario,
                  o.num_vacantes, o.horario, o.experiencia, o.fecha_publicacion,
                  e.nom_empresa, c.nom_ciudad,
                  n.nom_nivel_educativo, i.nom_idioma
           FROM oferta_trabajo_of o
           LEFT JOIN empresa_of e      ON o.cod_empresa = e.cod_empresa
           LEFT JOIN ciudad c          ON o.cod_ciudad  = c.cod_ciudad
           LEFT JOIN nivel_educativo n ON o.cod_nivel   = n.cod_nivel_educativo
           LEFT JOIN idioma i          ON o.cod_idioma  = i.cod_idioma
           WHERE o.estado = 'AC'";
$params = [];

if ($query) {
    $sql .= " AND (o.nom_puesto_trabajo LIKE :q1 OR e.nom_empresa LIKE :q2)";
    $params[':q1'] = $params[':q2'] = "%$query%";
}
if ($cod_ciudad) { $sql .= " AND o.cod_ciudad = :ciudad";     $params[':ciudad'] = $cod_ciudad; }
if ($cod_nivel)  { $sql .= " AND o.cod_nivel = :nivel";       $params[':nivel']  = $cod_nivel; }
if ($cod_idioma) { $sql .= " AND o.cod_idioma = :idioma";     $params[':idioma'] = $cod_idioma; }

$sql .= " ORDER BY o.fecha_publicacion DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ofertas = $stmt->fetchAll();

$total_activas   = $pdo->query("SELECT COUNT(*) FROM oferta_trabajo_of WHERE estado='AC'")->fetchColumn();
$total_vacantes  = $pdo->query("SELECT SUM(num_vacantes) FROM oferta_trabajo_of WHERE estado='AC'")->fetchColumn();
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Ofertas de Empleo - Observatorio Laboral</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

<nav class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center w-full px-12 h-16 bg-white border-b border-slate-200 text-sm">
    <div class="text-xl font-bold tracking-tighter text-green-800">Observatorio Laboral</div>
    <div class="hidden md:flex items-center space-x-8">
        <a class="text-slate-600 hover:text-green-600 transition-colors" href="/inicio/index.html">Inicio</a>
        <a class="text-green-700 font-semibold border-b-2 border-green-700 pb-1" href="directorio.php">Ofertas</a>
        <a class="text-slate-600 hover:text-green-600 transition-colors" href="../directorioEmpresa/directorio.php">Empresas</a>
        <a class="text-slate-600 hover:text-green-600 transition-colors" href="../directorioEgresado/directorio.php">Egresados</a>
    </div>
</nav>

<aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 w-64 border-r border-slate-200 bg-slate-100 text-sm font-medium z-40">
    <div class="px-6 mb-8">
        <h2 class="font-bold text-lg text-slate-800">Gestión</h2>
        <p class="text-slate-500 text-xs">Portal del Observatorio</p>
    </div>
    <nav class="flex-1 space-y-1 pr-4">
        <a class="flex items-center px-6 py-3 text-slate-500 hover:bg-slate-200 transition-all" href="../oferta/registrar.php"><span class="material-symbols-outlined mr-3">post_add</span> Registrar Oferta</a>
        <a class="flex items-center px-6 py-3 text-green-700 bg-white rounded-r-lg shadow-sm font-bold" href="../verOfertaEmpleo/ver.php"><span class="material-symbols-outlined mr-3">description</span> Ver Postulaciones</a>
        <a class="flex items-center px-6 py-3 text-slate-500 hover:bg-slate-200 transition-all" href="../reportes/reportes.php"><span class="material-symbols-outlined mr-3">analytics</span> Reportes</a>
    </nav>
</aside>

<main class="ml-64 mt-16 min-h-screen p-12">
    <header class="mb-8">
        <h1 class="text-5xl font-black tracking-tight text-slate-800 mb-2">Ofertas de Empleo</h1>
        <p class="text-slate-500 text-lg">Oportunidades profesionales activas en el Observatorio Laboral.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl border border-slate-200">
            <p class="text-xs font-bold uppercase tracking-wider text-green-700 mb-1">Ofertas Activas</p>
            <p class="text-4xl font-black text-slate-800"><?= number_format($total_activas) ?></p>
        </div>
        <div class="bg-green-700 p-6 rounded-xl text-white">
            <p class="text-xs font-bold uppercase tracking-wider text-green-200 mb-1">Vacantes Disponibles</p>
            <p class="text-4xl font-black"><?= number_format($total_vacantes ?? 0) ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Resultados Actuales</p>
            <p class="text-4xl font-black text-slate-800"><?= count($ofertas) ?></p>
        </div>
    </div>

    <form method="GET" action="directorio.php" class="bg-white p-6 rounded-xl border border-slate-200 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="col-span-2 flex items-center border border-slate-300 rounded-lg px-4 gap-2 bg-white">
            <span class="material-symbols-outlined text-slate-400 text-sm">search</span>
            <input name="query" value="<?= htmlspecialchars($query) ?>" class="w-full py-2 text-sm border-none focus:ring-0 focus:outline-none" placeholder="Buscar por cargo o empresa..."/>
        </div>
        <select name="ciudad" class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">Todas las ciudades</option>
            <?php foreach ($ciudades as $c): ?>
            <option value="<?= $c['cod_ciudad'] ?>" <?= $cod_ciudad === $c['cod_ciudad'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nom_ciudad']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="nivel" class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">Nivel educativo</option>
            <?php foreach ($niveles as $n): ?>
            <option value="<?= $n['cod_nivel_educativo'] ?>" <?= $cod_nivel === $n['cod_nivel_educativo'] ? 'selected' : '' ?>><?= htmlspecialchars($n['nom_nivel_educativo']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="idioma" class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">Idioma requerido</option>
            <?php foreach ($idiomas as $i): ?>
            <option value="<?= $i['cod_idioma'] ?>" <?= $cod_idioma == $i['cod_idioma'] ? 'selected' : '' ?>><?= htmlspecialchars($i['nom_idioma']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-800 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">filter_list</span> Aplicar Filtros
        </button>
        <a href="directorio.php" class="text-slate-500 px-4 py-2 rounded-lg hover:bg-slate-100 text-sm flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">restart_alt</span> Limpiar
        </a>
    </form>

    <!-- Tarjetas de ofertas -->
    <?php if (empty($ofertas)): ?>
    <div class="text-center py-20 text-slate-400">
        <span class="material-symbols-outlined text-6xl block mb-3">work_off</span>
        No se encontraron ofertas con los filtros aplicados.
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($ofertas as $o): ?>
        <div class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col gap-4 hover:border-green-300 hover:shadow-md transition-all">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-green-700 mb-1"><?= htmlspecialchars($o['nom_empresa'] ?? '—') ?></p>
                <h3 class="text-lg font-bold text-slate-800"><?= htmlspecialchars($o['nom_puesto_trabajo']) ?></h3>
                <?php if ($o['descripcion']): ?>
                <p class="text-slate-500 text-sm mt-1 line-clamp-2"><?= htmlspecialchars(substr($o['descripcion'], 0, 120)) ?>...</p>
                <?php endif; ?>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php if ($o['nom_ciudad']): ?>
                <span class="flex items-center gap-1 text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-full"><span class="material-symbols-outlined text-xs">location_on</span><?= htmlspecialchars($o['nom_ciudad']) ?></span>
                <?php endif; ?>
                <?php if ($o['salario']): ?>
                <span class="flex items-center gap-1 text-xs text-green-700 bg-green-50 px-2 py-1 rounded-full font-semibold"><span class="material-symbols-outlined text-xs">payments</span><?= htmlspecialchars($o['salario']) ?></span>
                <?php endif; ?>
                <?php if ($o['num_vacantes']): ?>
                <span class="text-xs text-blue-700 bg-blue-50 px-2 py-1 rounded-full"><?= $o['num_vacantes'] ?> vacante(s)</span>
                <?php endif; ?>
                <?php if ($o['nom_nivel_educativo']): ?>
                <span class="text-xs text-purple-700 bg-purple-50 px-2 py-1 rounded-full"><?= htmlspecialchars($o['nom_nivel_educativo']) ?></span>
                <?php endif; ?>
            </div>
            <div class="flex justify-between items-center mt-auto pt-2 border-t border-slate-100">
                <span class="text-xs text-slate-400"><?= $o['fecha_publicacion'] ? date('d/m/Y', strtotime($o['fecha_publicacion'])) : '' ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>
</body>
</html>
