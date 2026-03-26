<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Directorio de Graduados - Observatorio Laboral</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <link rel="stylesheet" href="styles.css">
    <script src="config-tailwind.js"></script>
</head>
<body class="bg-surface text-on-surface selection:bg-primary-fixed selection:text-on-primary-fixed">

    <nav class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center w-full px-12 h-16 max-w-none bg-slate-50 dark:bg-slate-900 font-public-sans text-sm tracking-tight border-b border-slate-200 dark:border-slate-800">
        <div class="text-xl font-bold tracking-tighter text-green-800 dark:text-green-500">Observatorio Laboral</div>
        <div class="hidden md:flex items-center space-x-8">
            <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 dark:hover:text-green-300 transition-colors" href="#">Inicio</a>
            <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 dark:hover:text-green-300 transition-colors" href="#">Ofertas de Empleo</a>
            <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 dark:hover:text-green-300 transition-colors" href="#">Empresas</a>
            <a class="text-green-700 dark:text-green-400 font-semibold border-b-2 border-green-700 dark:border-green-400 pb-1" href="#">Graduados</a>
        </div>
        <div class="flex items-center space-x-4">
            <button class="material-symbols-outlined text-slate-600 hover:bg-slate-100 p-2 rounded-full transition-all duration-200">account_circle</button>
            <button class="material-symbols-outlined text-slate-600 hover:bg-slate-100 p-2 rounded-full transition-all duration-200">logout</button>
        </div>
    </nav>

    <aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 h-screen w-64 border-r border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 font-public-sans text-sm font-medium z-40">
        <div class="px-6 mb-8">
            <p class="text-xs uppercase tracking-widest text-slate-400 mb-1">Portal del Observatorio</p>
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200">Gestión</h2>
        </div>
        <nav class="flex-1 space-y-1 pr-4">
            <a class="flex items-center px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300" href="#">
                <span class="material-symbols-outlined mr-3">post_add</span> Registrar Oferta
            </a>
            <a class="flex items-center px-6 py-3 text-green-700 dark:text-green-400 bg-white dark:bg-slate-900 rounded-r-lg shadow-sm font-bold" href="#">
                <span class="material-symbols-outlined mr-3">school</span> Registrar Graduado
            </a>
            <a class="flex items-center px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300" href="#">
                <span class="material-symbols-outlined mr-3">domain</span> Registrar Empresa
            </a>
            <a class="flex items-center px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300" href="#">
                <span class="material-symbols-outlined mr-3">description</span> Ver Solicitudes
            </a>
            <a class="flex items-center px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300" href="#">
                <span class="material-symbols-outlined mr-3">analytics</span> Reportes
            </a>
        </nav>
    </aside>

    <main class="ml-64 pt-16 min-h-screen bg-surface">
        <div class="max-w-7xl mx-auto px-12 py-12">
            <header class="mb-12">
                <h1 class="text-4xl font-extrabold tracking-tight text-on-surface mb-2">Directorio de Graduados</h1>
                <p class="text-on-surface-variant text-lg max-w-2xl">Gestión integral y visualización de perfiles profesionales certificados por el Observatorio Laboral.</p>
            </header>

            <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_12px_32px_-4px_rgba(0,34,4,0.04)] border border-outline-variant/10 flex flex-col justify-between">
                    <div>
                        <span class="label-md uppercase tracking-[0.05rem] text-primary font-semibold mb-4 block">Total Graduados</span>
                        <div class="text-5xl font-black text-on-surface">14,280</div>
                    </div>
                    <div class="mt-4 flex items-center text-primary text-sm font-medium">
                        <span class="material-symbols-outlined mr-1 text-sm">trending_up</span>
                        <span>+12% desde el último semestre</span>
                    </div>
                </div>
                <div class="bg-primary-fixed p-8 rounded-xl shadow-[0_12px_32px_-4px_rgba(0,34,4,0.08)] flex flex-col justify-between">
                    <div>
                        <span class="label-md uppercase tracking-[0.05rem] text-on-primary-fixed-variant font-semibold mb-4 block">Tasa de Empleo</span>
                        <div class="text-5xl font-black text-on-primary-fixed">84.3%</div>
                    </div>
                    <div class="mt-4 flex items-center text-on-primary-fixed-variant text-sm font-medium">
                        <span class="material-symbols-outlined mr-1 text-sm">verified</span>
                        <span>Datos validados por el Ministerio</span>
                    </div>
                </div>
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_12px_32px_-4px_rgba(0,34,4,0.04)] border border-outline-variant/10 flex flex-col justify-between">
                    <div>
                        <span class="label-md uppercase tracking-[0.05rem] text-tertiary font-semibold mb-4 block">Perfiles Verificados</span>
                        <div class="text-5xl font-black text-on-surface">9,122</div>
                    </div>
                    <div class="mt-4 flex items-center text-on-surface-variant text-sm font-medium">
                        <span class="material-symbols-outlined mr-1 text-sm">history</span>
                        <span>Actualizado hace 2 horas</span>
                    </div>
                </div>
            </section>

            <section class="bg-surface-container p-6 rounded-xl mb-8 flex flex-wrap items-end gap-4">
                <form action="procesar_graduados.php" method="GET" class="w-full flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[300px]">
                        <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2 ml-1">Búsqueda Directa</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
                            <input name="busqueda" class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-primary-fixed-dim transition-all outline-none text-on-surface" placeholder="Buscar por nombre o identificación..." type="text"/>
                        </div>
                    </div>
                    <div class="w-64">
                        <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2 ml-1">Departamento</label>
                        <select name="departamento" class="w-full px-4 py-3 bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-primary-fixed-dim transition-all outline-none text-on-surface appearance-none">
                            <option value="">Todos los departamentos</option>
                            <option value="bogota">Bogotá, D.C.</option>
                            <option value="antioquia">Antioquia</option>
                            <option value="valle">Valle del Cauca</option>
                            <option value="atlantico">Atlántico</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary px-8 py-3 rounded-lg font-semibold transition-all shadow-md active:scale-95 flex items-center">
                        <span class="material-symbols-outlined mr-2">filter_alt</span>
                        Filtrar Resultados
                    </button>
                </form>
            </section>

            <section class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden mb-8">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-container-high border-b border-outline-variant/20">
                        <tr>
                            <th class="px-8 py-5 text-xs font-black uppercase tracking-widest text-on-surface-variant">ID</th>
                            <th class="px-8 py-5 text-xs font-black uppercase tracking-widest text-on-surface-variant">Nombre Completo</th>
                            <th class="px-8 py-5 text-xs font-black uppercase tracking-widest text-on-surface-variant">Profesión / Título</th>
                            <th class="px-8 py-5 text-xs font-black uppercase tracking-widest text-on-surface-variant">Contacto</th>
                            <th class="px-8 py-5 text-xs font-black uppercase tracking-widest text-on-surface-variant">Ubicación</th>
                            <th class="px-8 py-5 text-xs font-black uppercase tracking-widest text-on-surface-variant text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container">
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="px-8 py-5 text-sm font-medium text-on-surface-variant">1.020.455.932</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold text-xs mr-3">AM</div>
                                    <span class="text-sm font-bold text-on-surface">Alejandra Morales Rico</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm text-on-surface-variant">Ingeniería de Sistemas</td>
                            <td class="px-8 py-5">
                                <div class="text-xs text-on-surface-variant">amorales@email.com</div>
                                <div class="text-xs text-outline">+57 312 456 7890</div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-container text-on-surface-variant">Bogotá, CO</span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <button class="text-primary font-bold text-sm hover:underline underline-offset-4 decoration-primary/30">Ver Perfil</button>
                            </td>
                        </tr>
                        </tbody>
                </table>
            </section>

            <footer class="flex items-center justify-between border-t border-outline-variant/30 pt-8">
                <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Mostrando 4 de 14,280 graduados</p>
                <div class="flex items-center space-x-2">
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container text-outline hover:bg-surface-container-high transition-all">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-on-primary font-bold shadow-sm">1</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container transition-all">2</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container transition-all">3</button>
                    <span class="text-outline">...</span>
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container transition-all">357</button>
                    <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container text-outline hover:bg-surface-container-high transition-all">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </footer>
        </div>
    </main>

    <footer class="ml-64 w-auto px-12 py-8 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 flex justify-between items-center font-public-sans text-xs uppercase tracking-widest">
        <div class="text-sm font-black text-slate-800 dark:text-slate-200">Observatorio Laboral</div>
        <div class="text-slate-400">© 2024 Observatorio Laboral. Todos los derechos reservados.</div>
        <div class="flex space-x-6">
            <a class="text-slate-400 hover:text-green-700 transition-colors" href="#">Política de Privacidad</a>
            <a class="text-slate-400 hover:text-green-700 transition-colors" href="#">Términos de Servicio</a>
            <a class="text-slate-400 hover:text-green-700 transition-colors" href="#">Soporte Técnico</a>
        </div>
    </footer>
</body>
</html>