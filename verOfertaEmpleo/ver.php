<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Ofertas de Empleo | Observatorio Laboral</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <link rel="stylesheet" href="styles.css">
    <script src="tailwind-config.js"></script>
</head>
<body class="bg-background text-on-surface min-h-screen flex flex-col">

<nav class="bg-slate-50 dark:bg-slate-900 flex justify-between items-center w-full px-12 h-16 max-w-none fixed top-0 z-50">
    <div class="text-xl font-bold tracking-tighter text-green-800 dark:text-green-500">Observatorio Laboral</div>
    <div class="hidden md:flex items-center gap-8 font-public-sans text-sm tracking-tight">
        <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 dark:hover:text-green-300 transition-colors" href="../index.html">Inicio</a>
        <a class="text-green-700 dark:text-green-400 font-semibold border-b-2 border-green-700 dark:border-green-400 pb-1" href="ver.php">Ofertas de Empleo</a>
        <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 dark:hover:text-green-300 transition-colors" href="../directorioEmpresa/directorio.php">Empresas</a>
        <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 dark:hover:text-green-300 transition-colors" href="../directorioEgresado/directorio.php">Graduados</a>
    </div>
    <div class="flex items-center gap-4 text-green-700 dark:text-green-400">
        <button class="material-symbols-outlined hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200 p-2 rounded-full">account_circle</button>
        <button class="material-symbols-outlined hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200 p-2 rounded-full">logout</button>
    </div>
</nav>

<aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 bg-slate-100 dark:bg-slate-950 h-screen w-64 border-r border-slate-200 dark:border-slate-800 hidden lg:flex">
    <div class="px-6 mb-8">
        <h2 class="text-slate-800 dark:text-slate-200 font-bold text-lg">Gestión</h2>
        <p class="text-slate-500 text-xs uppercase tracking-widest">Portal del Observatorio</p>
    </div>
    <nav class="flex flex-col gap-1 pr-4 font-public-sans text-sm font-medium">
        <a class="flex items-center gap-3 px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300" href="../oferta/registrar.php">
            <span class="material-symbols-outlined">post_add</span> Registrar Oferta
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300" href="../egresados/registrar.php">
            <span class="material-symbols-outlined">school</span> Registrar Graduado
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300" href="../empresa/registrar.php">
            <span class="material-symbols-outlined">domain</span> Registrar Empresa
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-green-700 dark:text-green-400 bg-white dark:bg-slate-900 rounded-r-lg shadow-sm font-bold" href="#">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">description</span> Ver Solicitudes
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300" href="#">
            <span class="material-symbols-outlined">analytics</span> Reportes
        </a>
    </nav>
</aside>

<main class="mt-16 lg:ml-64 flex-grow p-12">
    <header class="mb-12">
        <div class="flex items-center gap-3 mb-2">
            <span class="h-1 w-8 bg-primary rounded-full"></span>
            <span class="text-label-md uppercase tracking-[0.05rem] text-on-surface-variant font-semibold">Directorios Activos</span>
        </div>
        <h1 class="text-5xl font-black text-on-surface tracking-tight font-headline">Ofertas de Empleo</h1>
        <p class="mt-4 text-on-surface-variant max-w-2xl text-lg leading-relaxed">
            Explore un catálogo exhaustivo de oportunidades profesionales actualizadas en tiempo real. Nuestro observatorio sigue el pulso del mercado laboral nacional en todos los sectores.
        </p>
    </header>

    <form action="procesar.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="md:col-span-2 bg-surface-container-lowest p-6 rounded-xl flex items-center gap-4 border border-outline-variant/10">
            <span class="material-symbols-outlined text-outline">search</span>
            <input name="q" class="w-full bg-transparent border-none focus:ring-0 text-on-surface placeholder:text-outline-variant" placeholder="Buscar por cargo o empresa..." type="text"/>
        </div>
        <div class="bg-surface-container p-6 rounded-xl flex items-center justify-between">
            <select name="pais" class="bg-transparent border-none w-full text-sm font-semibold focus:ring-0 cursor-pointer appearance-none">
                <option value="co">Colombia</option>
                <option value="int">Internacional</option>
            </select>
            <span class="material-symbols-outlined">expand_more</span>
        </div>
        <button type="submit" class="bg-primary text-on-primary p-6 rounded-xl flex items-center justify-center font-bold transition-all hover:opacity-90 active:scale-95">
            Aplicar Filtros
        </button>
    </form>

    <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-primary text-on-primary">
                    <th class="py-5 px-6 font-bold uppercase tracking-wider text-xs">Cargo</th>
                    <th class="py-5 px-6 font-bold uppercase tracking-wider text-xs">Empresa</th>
                    <th class="py-5 px-6 font-bold uppercase tracking-wider text-xs">Vacantes</th>
                    <th class="py-5 px-6 font-bold uppercase tracking-wider text-xs">Ciudad</th>
                    <th class="py-5 px-6 font-bold uppercase tracking-wider text-xs">Departamento</th>
                    <th class="py-5 px-6 font-bold uppercase tracking-wider text-xs">País</th>
                    <th class="py-5 px-6 font-bold uppercase tracking-wider text-xs">Acción</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <tr class="bg-white hover:bg-surface-container transition-colors duration-150 group">
                    <td class="py-4 px-6 font-semibold text-primary">Analista de Datos Senior</td>
                    <td class="py-4 px-6 text-on-surface">TechMetrics Global</td>
                    <td class="py-4 px-6"><span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-xs font-bold">03</span></td>
                    <td class="py-4 px-6">Bogotá</td>
                    <td class="py-4 px-6">Cundinamarca</td>
                    <td class="py-4 px-6">Colombia</td>
                    <td class="py-4 px-6">
                        <button class="text-primary hover:underline font-bold text-xs uppercase tracking-tighter">Detalles</button>
                    </td>
                </tr>
                <tr class="bg-slate-50 hover:bg-surface-container transition-colors duration-150 group">
                    <td class="py-4 px-6 font-semibold text-primary">Director de Operaciones</td>
                    <td class="py-4 px-6 text-on-surface">Manufacturas Estrella</td>
                    <td class="py-4 px-6"><span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-xs font-bold">01</span></td>
                    <td class="py-4 px-6">Medellín</td>
                    <td class="py-4 px-6">Antioquia</td>
                    <td class="py-4 px-6">Colombia</td>
                    <td class="py-4 px-6">
                        <button class="text-primary hover:underline font-bold text-xs uppercase tracking-tighter">Detalles</button>
                    </td>
                </tr>
                </tbody>
        </table>
    </div>

    <div class="mt-8 flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-widest text-outline">Mostrando 1 a 6 de 142 resultados</span>
        <div class="flex gap-2">
            <button class="w-10 h-10 rounded-lg flex items-center justify-center bg-surface-container-highest text-on-surface hover:bg-primary-fixed-dim transition-colors">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button class="w-10 h-10 rounded-lg flex items-center justify-center bg-primary text-on-primary font-bold">1</button>
            <button class="w-10 h-10 rounded-lg flex items-center justify-center bg-surface-container-highest text-on-surface hover:bg-primary-fixed-dim transition-colors font-bold">2</button>
            <button class="w-10 h-10 rounded-lg flex items-center justify-center bg-surface-container-highest text-on-surface hover:bg-primary-fixed-dim transition-colors font-bold">3</button>
            <button class="w-10 h-10 rounded-lg flex items-center justify-center bg-surface-container-highest text-on-surface hover:bg-primary-fixed-dim transition-colors">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>
    </div>
</main>

<footer class="bg-slate-50 dark:bg-slate-900 w-full px-12 py-8 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase">Observatorio Laboral</div>
    <div class="text-slate-400 font-public-sans text-xs uppercase tracking-widest">© 2024 Observatorio Laboral. Todos los derechos reservados.</div>
    <div class="flex gap-6 font-public-sans text-xs uppercase tracking-widest">
        <a class="text-slate-400 hover:text-green-700 transition-colors" href="#">Privacidad</a>
        <a class="text-slate-400 hover:text-green-700 transition-colors" href="#">Términos</a>
        <a class="text-slate-400 hover:text-green-700 transition-colors" href="#">Soporte</a>
    </div>
</footer>

</body>
</html>