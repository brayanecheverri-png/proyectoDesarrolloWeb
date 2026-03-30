<?php
session_start();
$nombre = htmlspecialchars($_SESSION['nombre'] ?? 'Estudiante');

require_once '../conexion.php';

$pdo = conectar();

try {
    $pdo = conectar();

    // Totales para las tarjetas de resumen
    $totalEmpresas  = $pdo->query("SELECT COUNT(*) FROM empresa_of")->fetchColumn();
    $totalVacantes  = $pdo->query("SELECT COALESCE(SUM(num_vacantes),0) FROM oferta_trabajo_of WHERE estado='AC'")->fetchColumn();

    // Listado completo de empresas con ciudad
    $empresas = $pdo->query("
        SELECT e.cod_empresa, e.nom_empresa, e.num_ruc, e.nom_representante,
               e.direccion, e.telefono, e.email, e.cod_estado,
               c.nom_ciudad
        FROM empresa_of e
        LEFT JOIN ciudad c ON c.cod_ciudad = e.cod_ciudad
        ORDER BY e.nom_empresa
    ")->fetchAll();

} catch (Throwable $ex) {
    $totalEmpresas = 0;
    $totalVacantes = 0;
    $empresas      = [];
    $errorBD       = $ex->getMessage();
}
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Directorio de Empresas | Observatorio Laboral</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <link rel="stylesheet" href="styles.css">
    <script src="config-tailwind.js"></script>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col">

    <nav class="bg-white flex justify-between items-center w-full px-10 h-16 fixed top-0 z-50 border-b border-slate-100 shadow-sm">
    <div class="text-xl font-bold tracking-tighter text-green-800">Observatorio Laboral</div>
    <div class="hidden md:flex items-center gap-3 text-sm font-medium">
        <span class="text-slate-500">Hola, <span class="font-semibold text-green-700"><?= $nombre ?></span></span>
        <span class="text-slate-300">|</span>
        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-bold uppercase tracking-wide">Egresado</span>
    </div>
    <div class="flex items-center gap-3 text-green-700">
        <a href="logout.php" title="Cerrar sesión">
            <button class="material-symbols-outlined hover:bg-slate-100 transition-all p-2 rounded-full">logout</button>
        </a>
    </div>
    </nav>

    <aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 bg-white h-screen w-64 border-r border-slate-100 hidden lg:flex">
    <div class="px-6 mb-6">
        <h2 class="text-slate-800 font-bold text-lg">Mi Portal</h2>
        <p class="text-slate-400 text-xs uppercase tracking-widest">Estudiante / Egresado</p>
    </div>
    <nav class="flex flex-col gap-1 pr-4 text-sm font-medium">
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="../directorioOfertas/directorio.php">
            <span class="material-symbols-outlined">work</span> Directorio de Ofertas
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="directorio.php">
            <span class="material-symbols-outlined">domain</span> Directorio de Empresas
        </a>
    </nav>
    </aside>

        <main class="flex-1 lg:ml-64 p-12 bg-surface">
            <header class="mb-12 flex justify-between items-end">
                <div>
                    <span class="text-primary font-bold tracking-widest text-[0.65rem] uppercase mb-2 block">Archivo / Entidades</span>
                    <h1 class="text-4xl font-extrabold tracking-tighter text-on-surface">Directorio de Empresas</h1>
                    <p class="text-on-surface-variant mt-2 max-w-2xl font-body leading-relaxed">Registro integral de socios industriales y colaboradores corporativos dentro del ecosistema del Observatorio Laboral. Filtrado por impacto económico regional.</p>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="bg-surface-container-lowest p-8 rounded-xl flex flex-col border-l-4 border-primary">
                    <span class="label-md uppercase tracking-wider text-on-surface-variant font-bold text-[0.65rem] mb-4">Total Registradas</span>
                    <span class="text-5xl font-black text-on-surface tracking-tighter"><?= number_format((int)$totalEmpresas) ?></span>
                    <span class="text-primary text-xs font-semibold mt-2">Empresas en el sistema</span>
                </div>
                <div class="bg-surface-container-lowest p-8 rounded-xl flex flex-col border-l-4 border-secondary">
                    <span class="label-md uppercase tracking-wider text-on-surface-variant font-bold text-[0.65rem] mb-4">Vacantes Activas</span>
                    <span class="text-5xl font-black text-on-surface tracking-tighter"><?= number_format((int)$totalVacantes) ?></span>
                    <span class="text-secondary text-xs font-semibold mt-2">Puestos disponibles actualmente</span>
                </div>
                <div class="bg-surface-container-lowest p-8 rounded-xl flex flex-col border-l-4 border-tertiary">
                    <span class="label-md uppercase tracking-wider text-on-surface-variant font-bold text-[0.65rem] mb-4">Empresas Activas</span>
                    <span class="text-5xl font-black text-on-surface tracking-tighter"><?= number_format((int)$totalEmpresas) ?></span>
                    <span class="text-tertiary text-xs font-semibold mt-2">Registradas en el observatorio</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-xl shadow-[0_12px_32px_-4px_rgba(0,34,4,0.04)] overflow-hidden">
                <div class="px-8 py-6 border-b border-surface-container bg-surface-container-low flex justify-between items-center">
                    <div class="flex gap-6 items-center">
                        <span class="text-sm font-bold text-on-surface">Filtrar por Departamento:</span>
                        <div class="flex gap-2">
                            <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-xs font-bold uppercase tracking-tight cursor-pointer">Todos</span>
                            <span class="bg-surface-container text-on-surface-variant px-3 py-1 rounded-full text-xs font-bold uppercase tracking-tight cursor-pointer hover:bg-secondary-container hover:text-on-secondary-container transition-colors">Bogotá</span>
                            <span class="bg-surface-container text-on-surface-variant px-3 py-1 rounded-full text-xs font-bold uppercase tracking-tight cursor-pointer hover:bg-secondary-container hover:text-on-secondary-container transition-colors">Antioquia</span>
                            <span class="bg-surface-container text-on-surface-variant px-3 py-1 rounded-full text-xs font-bold uppercase tracking-tight cursor-pointer hover:bg-secondary-container hover:text-on-secondary-container transition-colors">Valle del Cauca</span>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low text-on-surface-variant text-[0.7rem] font-black uppercase tracking-[0.1em]">
                                <th class="px-8 py-5">NIT</th>
                                <th class="px-4 py-5">Nombre</th>
                                <th class="px-4 py-5">Dirección</th>
                                <th class="px-4 py-5">Información de Contacto</th>
                                <th class="px-4 py-5">Ubicación</th>
                                <th class="px-8 py-5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-0">
                            <?php if (empty($empresas)): ?>
                            <tr>
                                <td colspan="6" class="px-8 py-16 text-center text-on-surface-variant text-sm uppercase tracking-widest">
                                    <?php if (isset($errorBD)): ?>
                                        Error al conectar con la base de datos.
                                    <?php else: ?>
                                        No hay empresas registradas aún.
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php else: foreach ($empresas as $i => $e):
                                $letra = strtoupper(mb_substr($e['nom_empresa'], 0, 1));
                                $colores = ['bg-green-100 text-green-800','bg-blue-100 text-blue-800','bg-purple-100 text-purple-800','bg-amber-100 text-amber-800','bg-rose-100 text-rose-800'];
                                $color = $colores[$i % count($colores)];
                                $estadoLabel = ($e['cod_estado'] === 'AC' || $e['cod_estado'] === null) ? 'Activa' : 'Inactiva';
                                $estadoColor = ($e['cod_estado'] !== 'IN') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                            ?>
                            <tr class="group hover:bg-surface-container-low transition-all duration-200">
                                <td class="px-8 py-6 align-top">
                                    <span class="text-sm font-mono font-medium text-primary"><?= htmlspecialchars($e['num_ruc'] ?? $e['cod_empresa']) ?></span>
                                </td>
                                <td class="px-4 py-6 align-top">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded <?= $color ?> flex items-center justify-center font-bold text-lg shrink-0"><?= $letra ?></div>
                                        <div>
                                            <p class="text-sm font-bold text-on-surface"><?= htmlspecialchars($e['nom_empresa']) ?></p>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?= $estadoColor ?>"><?= $estadoLabel ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-6 align-top">
                                    <p class="text-xs text-on-surface leading-relaxed"><?= $e['direccion'] ? htmlspecialchars($e['direccion']) : '<span class="text-slate-400">—</span>' ?></p>
                                </td>
                                <td class="px-4 py-6 align-top">
                                    <div class="flex flex-col gap-1">
                                        <?php if ($e['telefono']): ?>
                                        <div class="flex items-center gap-2 text-xs text-on-surface">
                                            <span class="material-symbols-outlined text-[14px]">call</span> <?= htmlspecialchars($e['telefono']) ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($e['email']): ?>
                                        <div class="flex items-center gap-2 text-xs text-primary underline">
                                            <span class="material-symbols-outlined text-[14px]">mail</span>
                                            <a href="mailto:<?= htmlspecialchars($e['email']) ?>"><?= htmlspecialchars($e['email']) ?></a>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($e['nom_representante']): ?>
                                        <div class="text-[10px] text-on-surface-variant font-bold mt-1">CONTACTO: <?= htmlspecialchars($e['nom_representante']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-4 py-6 align-top">
                                    <div class="text-xs">
                                        <span class="font-bold text-on-surface"><?= $e['nom_ciudad'] ? htmlspecialchars($e['nom_ciudad']) : '—' ?></span>
                                        <p class="text-on-surface-variant">Colombia</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6 align-top text-right">
                                    <button onclick="verDetalle('<?= htmlspecialchars($e['cod_empresa']) ?>')" class="text-primary hover:bg-primary-fixed-dim/20 p-2 rounded-full transition-colors" title="Ver detalle">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 bg-surface-container-low flex justify-between items-center border-t border-surface-container">
                    <p class="text-[0.65rem] font-bold text-on-surface-variant uppercase tracking-widest">Mostrando <?= count($empresas) ?> de <?= (int)$totalEmpresas ?> empresas</p>
                    <div class="flex gap-2">
                        <button class="w-10 h-10 rounded bg-surface-container-lowest flex items-center justify-center text-on-surface-variant hover:bg-primary-fixed-dim transition-colors">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <button class="w-10 h-10 rounded bg-primary text-on-primary flex items-center justify-center font-bold text-sm">1</button>
                        <button class="w-10 h-10 rounded bg-surface-container-lowest flex items-center justify-center text-on-surface-variant hover:bg-primary-fixed-dim transition-colors font-bold text-sm">2</button>
                        <button class="w-10 h-10 rounded bg-surface-container-lowest flex items-center justify-center text-on-surface-variant hover:bg-primary-fixed-dim transition-colors">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-16 bg-primary-fixed p-12 rounded-2xl relative overflow-hidden flex flex-col md:flex-row gap-8 items-center">
                <div class="relative z-10 md:w-2/3">
                    <h3 class="text-3xl font-black text-on-primary-fixed tracking-tight mb-4">¿Es representante de una empresa?</h3>
                    <p class="text-on-primary-fixed-variant text-lg font-medium max-w-xl">Registre su organización hoy para acceder a graduados de primer nivel y analíticas de mercado laboral en tiempo real curadas por el Observatorio.</p>
                    <a href="/empresa/registrar.php"> <button class="mt-8 bg-on-primary-fixed text-primary-fixed px-8 py-4 rounded-xl font-bold uppercase tracking-widest text-xs hover:scale-105 duration-200 transition-transform">Empezar Ahora</button>
                    </a>
                </div>
                <div class="relative z-10 md:w-1/3">
                    <img class="rounded-xl shadow-2xl rotate-3 scale-110 border-4 border-white/20" alt="Oficina corporativa moderna" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCAeyhgiQQ8Yr-erNC5c_sIEVPFjOGo5fPjr4rRQZAC0Ob5aQWxx7jtcgBBHh-WuXvHbWQ4PTGSqDrqYHW_6HMzZRN4eUTY3sWACKSNf5F1e6tehRaVai1fTYJcQspXk1xZKabLHc34ogWPHZJdTlRlc9EjITW9WMc316d_fkoMgjJkpSqTPPIk5s1ycOAzE2a9siDX_zNUye0SvOXIp4wGp_Avhvzpp_fo8FKmkqv6ilK8FfZSuz2-kWbzLMEEceZ9KNkxmcXzTlM"/>
                </div>
            </div>
        </main>
    </div>

    <footer class="bg-slate-50 dark:bg-slate-900 w-full px-12 flex justify-between items-center py-8 border-t border-slate-200 dark:border-slate-800 mt-auto">
        <div>
            <span class="text-sm font-black text-slate-800 dark:text-slate-200">OBSERVATORIO LABORAL</span>
            <p class="text-slate-400 font-public-sans text-xs uppercase tracking-widest mt-1">© 2024 Observatorio Laboral.</p>
        </div>
    </footer>
    <script>
    // Pasar datos de empresas a JS para modal de detalle
    const empresasData = <?= json_encode($empresas, JSON_UNESCAPED_UNICODE) ?>;

    function verDetalle(cod) {
        const e = empresasData.find(x => x.cod_empresa === cod);
        if (!e) return;
        const html = `
            <div style="position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;display:flex;align-items:center;justify-content:center;" id="modal-empresa" onclick="if(event.target===this)this.remove()">
              <div style="background:#fff;border-radius:1rem;padding:2rem;max-width:480px;width:90%;box-shadow:0 24px 64px -12px rgba(0,0,0,.25);" onclick="event.stopPropagation()">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
                  <div>
                    <p style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#64748b;">Detalle de Empresa</p>
                    <h3 style="font-size:1.25rem;font-weight:800;color:#1e293b;margin-top:.25rem;">${e.nom_empresa}</h3>
                  </div>
                  <button onclick="document.getElementById('modal-empresa').remove()" style="color:#94a3b8;font-size:1.5rem;line-height:1;background:none;border:none;cursor:pointer;">✕</button>
                </div>
                <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
                  <tr><td style="padding:.4rem 0;color:#64748b;font-weight:600;width:140px;">NIT / RUC</td><td style="color:#1e293b;">${e.num_ruc || '—'}</td></tr>
                  <tr><td style="padding:.4rem 0;color:#64748b;font-weight:600;">Representante</td><td style="color:#1e293b;">${e.nom_representante || '—'}</td></tr>
                  <tr><td style="padding:.4rem 0;color:#64748b;font-weight:600;">Dirección</td><td style="color:#1e293b;">${e.direccion || '—'}</td></tr>
                  <tr><td style="padding:.4rem 0;color:#64748b;font-weight:600;">Teléfono</td><td style="color:#1e293b;">${e.telefono || '—'}</td></tr>
                  <tr><td style="padding:.4rem 0;color:#64748b;font-weight:600;">Email</td><td style="color:#0d9488;">${e.email ? `<a href="mailto:${e.email}">${e.email}</a>` : '—'}</td></tr>
                  <tr><td style="padding:.4rem 0;color:#64748b;font-weight:600;">Ciudad</td><td style="color:#1e293b;">${e.nom_ciudad || '—'}</td></tr>
                </table>
              </div>
            </div>`;
        document.body.insertAdjacentHTML('beforeend', html);
    }
    </script>
</body>
</html>