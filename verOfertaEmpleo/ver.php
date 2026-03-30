<?php
session_start();
$nombre = htmlspecialchars($_SESSION['nombre'] ?? 'Empresa');

require_once '../conexion.php';

$pdo = conectar();

try {
    $pdo = conectar();

    // Filtros opcionales
    $busqueda = trim($_GET['q']    ?? '');
    $filtroEmpresa = trim($_GET['empresa'] ?? '');

    // Ofertas con empresa, ciudad y conteo de postulaciones
    $sql = "
        SELECT o.cod_oferta,
               o.nom_puesto_trabajo,
               o.num_vacantes,
               o.salario,
               o.estado,
               o.fecha_publicacion,
               o.horario,
               o.descripcion,
               e.cod_empresa,
               e.nom_empresa,
               e.telefono      AS empresa_tel,
               e.email         AS empresa_email,
               e.nom_representante,
               c.nom_ciudad,
               (SELECT COUNT(*) FROM postulacion p WHERE p.cod_oferta = o.cod_oferta) AS total_postulaciones
        FROM oferta_trabajo_of o
        LEFT JOIN empresa_of e ON e.cod_empresa = o.cod_empresa
        LEFT JOIN ciudad c     ON c.cod_ciudad  = o.cod_ciudad
        WHERE 1=1
    ";

    $params = [];
    if ($busqueda !== '') {
        $sql .= " AND (o.nom_puesto_trabajo LIKE :q OR e.nom_empresa LIKE :q2)";
        $params[':q']  = '%' . $busqueda . '%';
        $params[':q2'] = '%' . $busqueda . '%';
    }
    if ($filtroEmpresa !== '') {
        $sql .= " AND e.cod_empresa = :empresa";
        $params[':empresa'] = $filtroEmpresa;
    }

    $sql .= " ORDER BY o.fecha_publicacion DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $ofertas = $stmt->fetchAll();

    // Lista de empresas para el filtro dropdown
    $listaEmpresas = $pdo->query("SELECT cod_empresa, nom_empresa FROM empresa_of ORDER BY nom_empresa")->fetchAll();

    // Totales para cabecera
    $totalOfertas     = count($ofertas);
    $totalActivas     = count(array_filter($ofertas, fn($o) => $o['estado'] === 'AC'));
    $totalPostuladas  = array_sum(array_column($ofertas, 'total_postulaciones'));

} catch (Throwable $ex) {
    $errorBD       = $ex->getMessage();
    $ofertas       = [];
    $listaEmpresas = [];
    $totalOfertas  = $totalActivas = $totalPostuladas = 0;
}

$estadoLabel = ['AC' => 'Activa', 'IN' => 'Inactiva', 'CE' => 'Cerrada'];
$estadoColor = [
    'AC' => 'bg-emerald-100 text-emerald-700',
    'IN' => 'bg-zinc-100 text-zinc-500',
    'CE' => 'bg-red-100 text-red-600',
];
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Solicitudes de Empresas | Observatorio Laboral</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>body{font-family:'Public Sans',sans-serif;}</style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">

<!-- NAV -->
<nav class="bg-white flex justify-between items-center w-full px-10 h-16 fixed top-0 z-50 border-b border-slate-100 shadow-sm">
    <div class="text-xl font-bold tracking-tighter text-green-800">Observatorio Laboral</div>
    <div class="hidden md:flex items-center gap-3 text-sm font-medium">
        <span class="text-slate-500">Hola, <span class="font-semibold text-green-700"><?= $nombre ?></span></span>
        <span class="text-slate-300">|</span>
        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-bold uppercase tracking-wide">Empresa</span>
    </div>
    <div class="flex items-center gap-3 text-green-700">
        <a href="logout.php" title="Cerrar sesión">
            <button class="material-symbols-outlined hover:bg-slate-100 transition-all p-2 rounded-full">logout</button>
        </a>
    </div>
</nav>

<!-- SIDEBAR -->
<aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 bg-white h-screen w-64 border-r border-slate-100 hidden lg:flex">
    <div class="px-6 mb-6">
        <h2 class="text-slate-800 font-bold text-lg">Gestión Empresa</h2>
        <p class="text-slate-400 text-xs uppercase tracking-widest">Portal Empresarial</p>
    </div>
    <nav class="flex flex-col gap-1 pr-4 text-sm font-medium">
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="../oferta/registrar.php">
            <span class="material-symbols-outlined">school</span> Publicar Oferta Empleo
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="../directorioEgresado/directorio.php">
            <span class="material-symbols-outlined">school</span> Directorio Egresados
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="ver.php">
            <span class="material-symbols-outlined">description</span> Mis Postulaciones
        </a>
    </nav>
</aside>

<!-- MAIN -->
<main class="mt-16 lg:ml-64 flex-grow p-8">

    <?php if (isset($errorBD)): ?>
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-center gap-3">
        <span class="material-symbols-outlined">error</span>
        Error de base de datos: <?= htmlspecialchars($errorBD) ?>
    </div>
    <?php endif; ?>

    <!-- ENCABEZADO -->
    <header class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <span class="h-1 w-8 bg-green-600 rounded-full"></span>
            <span class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Solicitudes Activas</span>
        </div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Solicitudes de Empresas</h1>
        <p class="mt-2 text-slate-500 max-w-2xl text-sm leading-relaxed">
            Ofertas de empleo publicadas por las empresas registradas en el Observatorio Laboral.
        </p>
    </header>

    <!-- TARJETAS RESUMEN -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-green-700">work</span>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900"><?= number_format($totalOfertas) ?></p>
                <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Total Ofertas</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-emerald-700">check_circle</span>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900"><?= number_format($totalActivas) ?></p>
                <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Ofertas Activas</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-blue-700">send</span>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900"><?= number_format($totalPostuladas) ?></p>
                <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Postulaciones</p>
            </div>
        </div>
    </div>

    <!-- FILTROS -->
    <form method="GET" action="ver.php" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-3">
            <span class="material-symbols-outlined text-slate-400 text-lg">search</span>
            <input name="q" type="text" placeholder="Buscar cargo o empresa…"
                   value="<?= htmlspecialchars($busqueda) ?>"
                   class="w-full bg-transparent border-none focus:ring-0 text-sm text-slate-700 placeholder:text-slate-400"/>
        </div>
        <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-3">
            <span class="material-symbols-outlined text-slate-400 text-lg">domain</span>
            <select name="empresa" class="w-full bg-transparent border-none focus:ring-0 text-sm text-slate-700">
                <option value="">Todas las empresas</option>
                <?php foreach ($listaEmpresas as $emp): ?>
                <option value="<?= htmlspecialchars($emp['cod_empresa']) ?>"
                    <?= $filtroEmpresa === $emp['cod_empresa'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($emp['nom_empresa']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-green-700 text-white rounded-xl font-semibold text-sm hover:bg-green-800 transition-colors flex items-center justify-center gap-2 py-3">
                <span class="material-symbols-outlined text-sm">filter_alt</span> Filtrar
            </button>
            <?php if ($busqueda || $filtroEmpresa): ?>
            <a href="ver.php" class="px-4 bg-slate-100 text-slate-600 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors flex items-center justify-center">
                <span class="material-symbols-outlined text-sm">close</span>
            </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- TABLA -->
    <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-green-700 text-white">
                        <th class="py-4 px-5 font-bold uppercase tracking-wide text-xs">Cargo</th>
                        <th class="py-4 px-5 font-bold uppercase tracking-wide text-xs">Empresa</th>
                        <th class="py-4 px-5 font-bold uppercase tracking-wide text-xs">Vacantes</th>
                        <th class="py-4 px-5 font-bold uppercase tracking-wide text-xs">Ciudad</th>
                        <th class="py-4 px-5 font-bold uppercase tracking-wide text-xs">Postulaciones</th>
                        <th class="py-4 px-5 font-bold uppercase tracking-wide text-xs">Estado</th>
                        <th class="py-4 px-5 font-bold uppercase tracking-wide text-xs">Publicada</th>
                        <th class="py-4 px-5 font-bold uppercase tracking-wide text-xs text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-50">
                    <?php if (empty($ofertas)): ?>
                    <tr>
                        <td colspan="8" class="py-16 text-center text-slate-400 text-xs uppercase tracking-widest">
                            <?php if (isset($errorBD)): ?>
                                Error al cargar los datos.
                            <?php elseif ($busqueda || $filtroEmpresa): ?>
                                No se encontraron resultados para los filtros aplicados.
                            <?php else: ?>
                                No hay solicitudes de empresas registradas aún.
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php else: foreach ($ofertas as $i => $o):
                        $estadoKey = $o['estado'] ?? 'IN';
                        $bgRow = $i % 2 === 0 ? 'bg-white' : 'bg-slate-50/50';
                    ?>
                    <tr class="<?= $bgRow ?> hover:bg-green-50/40 transition-colors">
                        <td class="py-4 px-5 font-semibold text-green-700">
                            <?= htmlspecialchars($o['nom_puesto_trabajo'] ?? '—') ?>
                        </td>
                        <td class="py-4 px-5">
                            <div class="font-semibold text-slate-800"><?= htmlspecialchars($o['nom_empresa'] ?? '—') ?></div>
                            <?php if ($o['empresa_email']): ?>
                            <div class="text-[11px] text-slate-400"><?= htmlspecialchars($o['empresa_email']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-5">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                <?= str_pad((int)$o['num_vacantes'], 2, '0', STR_PAD_LEFT) ?>
                            </span>
                        </td>
                        <td class="py-4 px-5 text-slate-600"><?= htmlspecialchars($o['nom_ciudad'] ?? '—') ?></td>
                        <td class="py-4 px-5">
                            <?php $cant = (int)$o['total_postulaciones']; ?>
                            <span class="<?= $cant > 0 ? 'text-blue-600 font-bold' : 'text-slate-400' ?>">
                                <?= $cant ?> <?= $cant === 1 ? 'candidato' : 'candidatos' ?>
                            </span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="px-3 py-1 rounded-full text-xs font-bold <?= $estadoColor[$estadoKey] ?? 'bg-zinc-100 text-zinc-500' ?>">
                                <?= $estadoLabel[$estadoKey] ?? $estadoKey ?>
                            </span>
                        </td>
                        <td class="py-4 px-5 text-slate-500 text-xs">
                            <?= $o['fecha_publicacion'] ? date('d/m/Y', strtotime($o['fecha_publicacion'])) : '—' ?>
                        </td>
                        <td class="py-4 px-5 text-center">
                            <button onclick="verDetalle(<?= $o['cod_oferta'] ?>)"
                                class="text-green-700 hover:bg-green-100 border border-green-300 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide transition-colors">
                                Detalles
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
            <span class="text-xs font-bold uppercase tracking-widest text-slate-400">
                <?= count($ofertas) ?> resultado<?= count($ofertas) !== 1 ? 's' : '' ?> encontrado<?= count($ofertas) !== 1 ? 's' : '' ?>
            </span>
            <a href="../oferta/registrar.php" class="bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-green-800 transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">add</span> Nueva Oferta
            </a>
        </div>
    </div>
</main>

<!-- MODAL DETALLE -->
<div id="modal-detalle" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" onclick="if(event.target===this)cerrarModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex justify-between items-start p-6 border-b border-slate-100">
            <div>
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Detalle de Oferta</p>
                <h3 id="modal-cargo" class="text-xl font-black text-slate-900 mt-1"></h3>
            </div>
            <button onclick="cerrarModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div id="modal-body" class="p-6 space-y-4 text-sm"></div>
    </div>
</div>

<script>
// Pasar datos de ofertas al JS para el modal
const ofertasData = <?= json_encode($ofertas, JSON_UNESCAPED_UNICODE) ?>;

function verDetalle(id) {
    const o = ofertasData.find(x => parseInt(x.cod_oferta) === id);
    if (!o) return;

    document.getElementById('modal-cargo').textContent = o.nom_puesto_trabajo || '—';

    const estadoLabels = {AC:'Activa', IN:'Inactiva', CE:'Cerrada'};
    const fields = [
        ['Empresa',        o.nom_empresa],
        ['Representante',  o.nom_representante],
        ['Email empresa',  o.empresa_email],
        ['Teléfono',       o.empresa_tel],
        ['Ciudad',         o.nom_ciudad],
        ['Vacantes',       o.num_vacantes],
        ['Salario',        o.salario],
        ['Horario',        o.horario],
        ['Estado',         estadoLabels[o.estado] || o.estado],
        ['Publicada',      o.fecha_publicacion ? o.fecha_publicacion.substring(0,10) : '—'],
        ['Postulaciones',  o.total_postulaciones],
        ['Descripción',    o.descripcion],
    ];

    document.getElementById('modal-body').innerHTML = fields
        .filter(([,v]) => v && v !== '0')
        .map(([k,v]) => `
            <div class="flex gap-3">
                <span class="text-slate-400 font-bold w-32 shrink-0">${k}</span>
                <span class="text-slate-700 flex-1">${escHtml(String(v))}</span>
            </div>`)
        .join('');

    document.getElementById('modal-detalle').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-detalle').classList.add('hidden');
}

function escHtml(s) {
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>

<footer class="bg-white w-full px-10 py-6 border-t border-slate-100 flex justify-between items-center">
    <span class="text-xs font-black text-slate-600 uppercase">Observatorio Laboral</span>
    <span class="text-slate-400 text-xs">© <?= date('Y') ?> Observatorio Laboral</span>
</footer>

</body>
</html>
