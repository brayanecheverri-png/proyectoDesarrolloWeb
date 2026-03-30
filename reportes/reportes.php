<?php
require_once '../conexion.php';

try {
    $pdo = conectar();

    // ── Totales principales ──────────────────────────────────
    $totalParticipantes = $pdo->query("SELECT COUNT(*) FROM participante")->fetchColumn();
    $totalEmpresas      = $pdo->query("SELECT COUNT(*) FROM empresa_of")->fetchColumn();
    $totalOfertas       = $pdo->query("SELECT COUNT(*) FROM oferta_trabajo_of WHERE estado='AC'")->fetchColumn();
    $totalPostulaciones = $pdo->query("SELECT COUNT(*) FROM postulacion")->fetchColumn();

    // ── Postulaciones por mes (año actual) ───────────────────
    $anioActual = date('Y');
    $stmtMeses  = $pdo->prepare("
        SELECT MONTH(fecha_postulacion) AS mes, COUNT(*) AS total
        FROM postulacion
        WHERE YEAR(fecha_postulacion) = :anio
        GROUP BY MONTH(fecha_postulacion)
        ORDER BY mes
    ");
    $stmtMeses->execute([':anio' => $anioActual]);
    $postulacionesMes = array_fill(1, 12, 0);
    foreach ($stmtMeses->fetchAll() as $row) {
        $postulacionesMes[(int)$row['mes']] = (int)$row['total'];
    }
    $maxPostulaciones = max(array_values($postulacionesMes)) ?: 1;

    // ── Distribución por ciudad (participantes) ──────────────
    $ciudades = $pdo->query("
        SELECT c.nom_ciudad, COUNT(p.num_ident) AS total
        FROM participante p
        JOIN ciudad c ON c.cod_ciudad = p.cod_ciudad
        GROUP BY c.nom_ciudad
        ORDER BY total DESC
        LIMIT 5
    ")->fetchAll();
    $totalParticipantesCiudad = array_sum(array_column($ciudades, 'total')) ?: 1;

    // ── Empresas con más ofertas ─────────────────────────────
    $empresasTop = $pdo->query("
        SELECT e.nom_empresa, e.cod_empresa,
               COUNT(o.cod_oferta)              AS total_ofertas,
               SUM(o.num_vacantes)              AS total_vacantes
        FROM empresa_of e
        LEFT JOIN oferta_trabajo_of o ON o.cod_empresa = e.cod_empresa
        GROUP BY e.cod_empresa, e.nom_empresa
        ORDER BY total_ofertas DESC
        LIMIT 8
    ")->fetchAll();

    // ── Usuarios registrados ─────────────────────────────────
    $totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuario")->fetchColumn();

    // ── Estado profesional de participantes ──────────────────
    $estadoProf = $pdo->query("
        SELECT ep.nom_estado_prof, COUNT(p.num_ident) AS total
        FROM participante p
        JOIN estado_profesional ep ON ep.cod_estado_prof = p.cod_estado_prof
        GROUP BY ep.nom_estado_prof
        ORDER BY total DESC
    ")->fetchAll();

} catch (Throwable $ex) {
    $errorBD            = $ex->getMessage();
    $totalParticipantes = $totalEmpresas = $totalOfertas = $totalPostulaciones = 0;
    $totalUsuarios      = 0;
    $postulacionesMes   = array_fill(1, 12, 0);
    $maxPostulaciones   = 1;
    $ciudades           = [];
    $empresasTop        = [];
    $estadoProf         = [];
    $totalParticipantesCiudad = 1;
}

$mesesNombres = ['','ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'];
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Informes y Analítica | Observatorio Laboral</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>body{font-family:'Public Sans',sans-serif;}</style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">

<!-- NAV -->
<nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-xl border-b border-zinc-100 shadow-sm flex justify-between items-center px-6 h-16">
    <div class="text-xl font-bold tracking-tighter text-emerald-800">Observatorio Laboral</div>
    <div class="hidden md:flex gap-8 items-center font-medium text-sm tracking-tight">
        <a class="text-zinc-500 hover:text-emerald-600 transition-colors" href="../oferta/registrar.php">Registrar Oferta</a>
        <a class="text-zinc-500 hover:text-emerald-600 transition-colors" href="../empresa/registrar.php">Registrar Empresa</a>
        <a class="text-emerald-700 font-semibold border-b-2 border-emerald-600" href="reportes.php">Informes</a>
    </div>
    <div class="flex items-center gap-3">
        <a href="../index.html" class="p-2 text-zinc-500 hover:bg-zinc-100 rounded-full transition-colors">
            <span class="material-symbols-outlined">logout</span>
        </a>
    </div>
</nav>

<!-- SIDEBAR -->
<aside class="h-screen w-64 fixed left-0 top-0 pt-16 bg-white flex flex-col gap-1 py-4 hidden lg:flex border-r border-zinc-100">
    <div class="px-6 py-4">
        <h2 class="text-lg font-black text-emerald-900">Gestión</h2>
        <p class="text-xs text-zinc-500 font-medium">Portal del Observatorio</p>
    </div>
    <nav class="flex-1 px-2 space-y-1">
        <a class="flex items-center px-4 py-3 text-zinc-500 hover:bg-emerald-50 hover:text-emerald-700 transition-all rounded-lg font-medium text-sm" href="../oferta/registrar.php">
            <span class="material-symbols-outlined mr-3">post_add</span> Registrar Oferta
        </a>
        <a class="flex items-center px-4 py-3 text-zinc-500 hover:bg-emerald-50 hover:text-emerald-700 transition-all rounded-lg font-medium text-sm" href="../verOfertaEmpleo/ver.php">
            <span class="material-symbols-outlined mr-3">list_alt</span> Ver Solicitudes
        </a>
        <a class="flex items-center bg-emerald-50 text-emerald-700 shadow-sm rounded-lg px-4 py-3 font-bold text-sm" href="reportes.php">
            <span class="material-symbols-outlined mr-3">analytics</span> Reportes
        </a>
    </nav>
    <div class="px-2 mt-auto border-t border-zinc-100 pt-4">
        <a class="flex items-center px-4 py-3 text-zinc-500 hover:bg-red-50 hover:text-red-600 transition-all rounded-lg font-medium text-sm" href="../index.html">
            <span class="material-symbols-outlined mr-3">logout</span> Cerrar Sesión
        </a>
    </div>
</aside>

<!-- MAIN -->
<main class="lg:ml-64 pt-24 px-6 pb-16 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <?php if (isset($errorBD)): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            Error de base de datos: <?= htmlspecialchars($errorBD) ?>
        </div>
        <?php endif; ?>

        <!-- ENCABEZADO -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <span class="text-[10px] tracking-widest text-emerald-700 font-bold uppercase mb-2 block">Panel Analítico</span>
                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900">Informes y Analítica</h1>
                <p class="text-slate-500 mt-2 max-w-xl text-sm leading-relaxed">Información en tiempo real sobre participantes, empresas, ofertas y postulaciones del Observatorio Laboral.</p>
            </div>
            <div class="flex items-center gap-2 bg-white p-2 rounded-xl border border-zinc-100 shadow-sm">
                <span class="material-symbols-outlined text-zinc-400 text-sm ml-2">calendar_today</span>
                <span class="text-sm font-medium text-slate-700 px-2"><?= date('d M Y') ?></span>
                <button onclick="location.reload()" class="bg-emerald-700 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-800 transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">refresh</span> Actualizar
                </button>
            </div>
        </header>

        <!-- TARJETAS DE RESUMEN -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-zinc-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <span class="material-symbols-outlined text-7xl">school</span>
                </div>
                <p class="text-[10px] tracking-widest text-zinc-500 font-bold uppercase mb-3">Participantes Registrados</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter"><?= number_format((int)$totalParticipantes) ?></h3>
                <div class="mt-4 flex items-center gap-2 text-emerald-600 text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">person</span>
                    <span>Egresados y estudiantes</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-zinc-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <span class="material-symbols-outlined text-7xl">domain</span>
                </div>
                <p class="text-[10px] tracking-widest text-zinc-500 font-bold uppercase mb-3">Empresas Registradas</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter"><?= number_format((int)$totalEmpresas) ?></h3>
                <div class="mt-4 flex items-center gap-2 text-emerald-600 text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">business</span>
                    <span>Empresas en el sistema</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-zinc-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <span class="material-symbols-outlined text-7xl">work_outline</span>
                </div>
                <p class="text-[10px] tracking-widest text-zinc-500 font-bold uppercase mb-3">Ofertas Activas</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter"><?= number_format((int)$totalOfertas) ?></h3>
                <div class="mt-4 flex items-center gap-2 text-emerald-600 text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">work</span>
                    <span>Empleos disponibles</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-zinc-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <span class="material-symbols-outlined text-7xl">send</span>
                </div>
                <p class="text-[10px] tracking-widest text-zinc-500 font-bold uppercase mb-3">Total Postulaciones</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter"><?= number_format((int)$totalPostulaciones) ?></h3>
                <div class="mt-4 flex items-center gap-2 text-emerald-600 text-xs font-bold">
                    <span class="material-symbols-outlined text-sm">how_to_reg</span>
                    <span>Candidaturas enviadas</span>
                </div>
            </div>
        </section>

        <!-- GRÁFICO DE POSTULACIONES POR MES + DISTRIBUCIÓN POR CIUDAD -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">

            <!-- Gráfico barras por mes -->
            <div class="lg:col-span-8 bg-white p-8 rounded-xl shadow-sm border border-zinc-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-bold text-slate-900">Postulaciones por Mes — <?= $anioActual ?></h4>
                        <p class="text-sm text-zinc-500">Número de candidaturas enviadas cada mes</p>
                    </div>
                    <span class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span> <?= $anioActual ?>
                    </span>
                </div>
                <div class="h-64 flex items-end gap-1 relative">
                    <div class="absolute left-0 top-0 bottom-0 flex flex-col justify-between text-[10px] text-zinc-400 font-medium pr-2" style="width:32px">
                        <?php
                        $labels = [$maxPostulaciones, round($maxPostulaciones*0.75), round($maxPostulaciones*0.5), round($maxPostulaciones*0.25), 0];
                        foreach ($labels as $l): ?>
                        <span><?= $l ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="absolute inset-0 flex flex-col justify-between pointer-events-none" style="left:32px">
                        <?php for($i=0;$i<5;$i++): ?>
                        <div class="border-b border-zinc-100 w-full"></div>
                        <?php endfor; ?>
                    </div>
                    <div class="flex-1 flex items-end justify-around gap-1 h-full z-10" style="margin-left:32px">
                        <?php for ($m = 1; $m <= 12; $m++):
                            $val = $postulacionesMes[$m];
                            $pct = $maxPostulaciones > 0 ? round(($val / $maxPostulaciones) * 100) : 0;
                        ?>
                        <div class="flex-1 flex flex-col items-center justify-end gap-1 h-full group">
                            <span class="text-[10px] text-emerald-700 font-bold opacity-0 group-hover:opacity-100 transition-opacity"><?= $val ?></span>
                            <div class="w-full rounded-t-md bg-emerald-600 hover:bg-emerald-500 transition-colors cursor-default"
                                 style="height:<?= max($pct, $val > 0 ? 2 : 0) ?>%;"
                                 title="<?= $mesesNombres[$m] ?>: <?= $val ?> postulaciones"></div>
                            <span class="text-[9px] text-zinc-400 font-bold"><?= $mesesNombres[$m] ?></span>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <?php if ($totalPostulaciones == 0): ?>
                <p class="text-center text-xs text-zinc-400 mt-4 uppercase tracking-widest">Sin postulaciones registradas en <?= $anioActual ?></p>
                <?php endif; ?>
            </div>

            <!-- Distribución por ciudad -->
            <div class="lg:col-span-4 bg-white p-8 rounded-xl shadow-sm border border-zinc-100">
                <h4 class="text-lg font-bold text-slate-900 mb-6">Participantes por Ciudad</h4>
                <?php if (empty($ciudades)): ?>
                <p class="text-sm text-zinc-400 text-center py-8">Sin datos de ciudades registrados.</p>
                <?php else: ?>
                <div class="space-y-5">
                    <?php foreach ($ciudades as $ciudad):
                        $pct = round(($ciudad['total'] / $totalParticipantesCiudad) * 100);
                    ?>
                    <div>
                        <div class="flex justify-between text-sm font-medium mb-1.5">
                            <span class="text-slate-700"><?= htmlspecialchars($ciudad['nom_ciudad']) ?></span>
                            <span class="text-zinc-500"><?= number_format($ciudad['total']) ?> (<?= $pct ?>%)</span>
                        </div>
                        <div class="w-full h-2 bg-zinc-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-600 rounded-full transition-all" style="width:<?= $pct ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- ESTADO PROFESIONAL -->
        <?php if (!empty($estadoProf)): ?>
        <section class="mb-10">
            <div class="bg-white p-8 rounded-xl shadow-sm border border-zinc-100">
                <h4 class="text-lg font-bold text-slate-900 mb-6">Estado Profesional de Participantes</h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    <?php
                    $coloresEstado = ['bg-emerald-50 text-emerald-700 border-emerald-200','bg-blue-50 text-blue-700 border-blue-200','bg-amber-50 text-amber-700 border-amber-200','bg-purple-50 text-purple-700 border-purple-200','bg-rose-50 text-rose-700 border-rose-200'];
                    foreach ($estadoProf as $i => $ep):
                        $color = $coloresEstado[$i % count($coloresEstado)];
                        $pct   = $totalParticipantes > 0 ? round(($ep['total'] / $totalParticipantes) * 100) : 0;
                    ?>
                    <div class="p-4 rounded-xl border <?= $color ?> text-center">
                        <p class="text-2xl font-black"><?= number_format($ep['total']) ?></p>
                        <p class="text-xs font-bold mt-1 uppercase tracking-wide"><?= htmlspecialchars($ep['nom_estado_prof']) ?></p>
                        <p class="text-[10px] opacity-70 mt-1"><?= $pct ?>% del total</p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- TABLA DE EMPRESAS CON MÁS OFERTAS -->
        <section>
            <div class="bg-white p-8 rounded-xl shadow-sm border border-zinc-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-bold text-slate-900">Empresas Registradas</h4>
                        <p class="text-sm text-zinc-500">Empresas con mayor actividad en ofertas de empleo</p>
                    </div>
                    <a href="../directorioEmpresa/directorio.php" class="text-emerald-700 text-sm font-bold hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">open_in_new</span> Ver todas
                    </a>
                </div>
                <?php if (empty($empresasTop)): ?>
                <div class="text-center py-12 text-zinc-400 text-sm uppercase tracking-widest">
                    No hay empresas registradas aún.
                </div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-zinc-400 text-[10px] tracking-widest uppercase border-b border-zinc-100">
                                <th class="pb-4 font-bold">Empresa</th>
                                <th class="pb-4 font-bold text-right">Ofertas Publicadas</th>
                                <th class="pb-4 font-bold text-right">Total Vacantes</th>
                                <th class="pb-4 font-bold text-right">Actividad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50">
                            <?php
                            $maxOfertas = max(array_column($empresasTop, 'total_ofertas') ?: [1]);
                            foreach ($empresasTop as $i => $emp):
                                $letra  = strtoupper(mb_substr($emp['nom_empresa'], 0, 2));
                                $bgList = ['bg-emerald-50 text-emerald-800','bg-blue-50 text-blue-800','bg-amber-50 text-amber-800','bg-purple-50 text-purple-800','bg-rose-50 text-rose-800'];
                                $bg     = $bgList[$i % count($bgList)];
                                $pctBar = $maxOfertas > 0 ? round(($emp['total_ofertas'] / $maxOfertas) * 100) : 0;
                            ?>
                            <tr class="hover:bg-zinc-50/60 transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg <?= $bg ?> flex items-center justify-center font-bold text-xs shrink-0"><?= $letra ?></div>
                                        <span class="font-semibold text-slate-800"><?= htmlspecialchars($emp['nom_empresa']) ?></span>
                                    </div>
                                </td>
                                <td class="py-4 text-right font-bold text-slate-700"><?= (int)$emp['total_ofertas'] ?></td>
                                <td class="py-4 text-right text-zinc-500"><?= (int)$emp['total_vacantes'] ?></td>
                                <td class="py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-20 h-1.5 bg-zinc-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width:<?= $pctBar ?>%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-zinc-400"><?= $pctBar ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </section>

    </div>
</main>

</body>
</html>
