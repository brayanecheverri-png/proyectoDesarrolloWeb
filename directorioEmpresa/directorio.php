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

    <nav class="bg-slate-50 dark:bg-slate-900 flex justify-between items-center w-full px-12 h-16 max-w-none fixed top-0 z-50">
        <div class="text-xl font-bold tracking-tighter text-green-800 dark:text-green-500">Observatorio Laboral</div>
        <div class="hidden md:flex items-center space-x-8">
            <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 dark:hover:text-green-300 transition-colors font-public-sans text-sm tracking-tight" href="#">Inicio</a>
            <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 dark:hover:text-green-300 transition-colors font-public-sans text-sm tracking-tight" href="#">Ofertas de Empleo</a>
            <a class="text-green-700 dark:text-green-400 font-semibold border-b-2 border-green-700 dark:border-green-400 pb-1 font-public-sans text-sm tracking-tight" href="#">Empresas</a>
            <a class="text-slate-600 dark:text-slate-400 hover:text-green-600 dark:hover:text-green-300 transition-colors font-public-sans text-sm tracking-tight" href="#">Graduados</a>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input class="pl-10 pr-4 py-1.5 bg-surface-container-lowest border-none rounded-full text-sm focus:ring-2 focus:ring-primary-fixed-dim w-64" placeholder="Buscar en el directorio..." type="text"/>
            </div>
            <button class="text-slate-600 hover:text-green-700 transition-all duration-200 active:scale-95">
                <span class="material-symbols-outlined">account_circle</span>
            </button>
            <button class="text-slate-600 hover:text-green-700 transition-all duration-200 active:scale-95">
                <span class="material-symbols-outlined">logout</span>
            </button>
        </div>
    </nav>

    <div class="flex flex-1 pt-16">
        <aside class="bg-slate-100 dark:bg-slate-950 fixed left-0 top-16 bottom-0 flex flex-col py-6 h-screen w-64 border-r border-slate-200 dark:border-slate-800 hidden lg:flex">
            <div class="px-6 mb-8">
                <h2 class="text-green-700 dark:text-green-400 font-public-sans text-sm font-bold uppercase tracking-widest">Gestión</h2>
                <p class="text-slate-500 text-xs">Portal del Observatorio</p>
            </div>
            <nav class="flex-1 space-y-1 pr-4">
                <a class="flex items-center px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300 font-public-sans text-sm font-medium" href="#">
                    <span class="material-symbols-outlined mr-3">post_add</span> Registrar Oferta
                </a>
                <a class="flex items-center px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300 font-public-sans text-sm font-medium" href="#">
                    <span class="material-symbols-outlined mr-3">school</span> Registrar Graduado
                </a>
                <a class="flex items-center px-6 py-3 text-green-700 dark:text-green-400 bg-white dark:bg-slate-900 rounded-r-lg shadow-sm font-bold font-public-sans text-sm" href="#">
                    <span class="material-symbols-outlined mr-3">domain</span> Directorio de Empresas
                </a>
                <a class="flex items-center px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300 font-public-sans text-sm font-medium" href="#">
                    <span class="material-symbols-outlined mr-3">description</span> Ver Solicitudes
                </a>
                <a class="flex items-center px-6 py-3 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 hover:pl-8 transition-all duration-300 font-public-sans text-sm font-medium" href="#">
                    <span class="material-symbols-outlined mr-3">analytics</span> Reportes
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
                <div class="flex gap-4">
                    <form action="procesar_empresa.php" method="POST">
                        <button type="submit" name="accion" value="agregar" class="bg-primary text-on-primary px-6 py-2.5 rounded-md text-sm font-semibold hover:bg-primary-container transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">add</span> Agregar Empresa
                        </button>
                    </form>
                    <button class="text-primary font-medium text-sm hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">download</span> Exportar PDF
                    </button>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="bg-surface-container-lowest p-8 rounded-xl flex flex-col border-l-4 border-primary">
                    <span class="label-md uppercase tracking-wider text-on-surface-variant font-bold text-[0.65rem] mb-4">Total Registradas</span>
                    <span class="text-5xl font-black text-on-surface tracking-tighter">1,284</span>
                    <span class="text-primary text-xs font-semibold mt-2">+12% desde el último trimestre</span>
                </div>
                <div class="bg-surface-container-lowest p-8 rounded-xl flex flex-col border-l-4 border-secondary">
                    <span class="label-md uppercase tracking-wider text-on-surface-variant font-bold text-[0.65rem] mb-4">Vacantes Activas</span>
                    <span class="text-5xl font-black text-on-surface tracking-tighter">452</span>
                    <span class="text-secondary text-xs font-semibold mt-2">En 14 departamentos</span>
                </div>
                <div class="bg-surface-container-lowest p-8 rounded-xl flex flex-col border-l-4 border-tertiary">
                    <span class="label-md uppercase tracking-wider text-on-surface-variant font-bold text-[0.65rem] mb-4">Tasa de Verificación</span>
                    <span class="text-5xl font-black text-on-surface tracking-tighter">98.2%</span>
                    <span class="text-tertiary text-xs font-semibold mt-2">Cumplimiento fiscal validado</span>
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
                            <tr class="group hover:bg-surface-container-low transition-all duration-200">
                                <td class="px-8 py-6 align-top">
                                    <span class="text-sm font-mono font-medium text-primary">900.123.456-7</span>
                                </td>
                                <td class="px-4 py-6 align-top">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded bg-primary-fixed flex items-center justify-center text-on-primary-fixed font-bold text-lg">T</div>
                                        <div>
                                            <p class="text-sm font-bold text-on-surface">TechNova Solutions S.A.S</p>
                                            <p class="text-xs text-on-surface-variant">Desarrollo de Software</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-6 align-top">
                                    <p class="text-xs text-on-surface leading-relaxed">Calle 100 #15-32<br/>Edificio Citicorp</p>
                                </td>
                                <td class="px-4 py-6 align-top">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-xs text-on-surface">
                                            <span class="material-symbols-outlined text-[14px]">call</span> +57 601 345 6789
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-primary underline">
                                            <span class="material-symbols-outlined text-[14px]">mail</span> contacto@technova.co
                                        </div>
                                        <div class="text-[10px] text-on-surface-variant font-bold mt-1">CONTACTO: Elena Rodríguez</div>
                                    </div>
                                </td>
                                <td class="px-4 py-6 align-top">
                                    <div class="text-xs">
                                        <span class="font-bold text-on-surface">Bogotá, D.C.</span>
                                        <p class="text-on-surface-variant">Cundinamarca, Colombia</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6 align-top text-right">
                                    <button class="text-primary hover:bg-primary-fixed-dim/20 p-2 rounded-full transition-colors">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 bg-surface-container-low flex justify-between items-center border-t border-surface-container">
                    <p class="text-[0.65rem] font-bold text-on-surface-variant uppercase tracking-widest">Mostrando 1-10 de 1,284 resultados</p>
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
                    <button class="mt-8 bg-on-primary-fixed text-primary-fixed px-8 py-4 rounded-xl font-bold uppercase tracking-widest text-xs hover:scale-105 duration-200 transition-transform">Empezar Ahora</button>
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
            <p class="text-slate-400 font-public-sans text-xs uppercase tracking-widest mt-1">© 2024 Observatorio Laboral. Todos los derechos reservados.</p>
        </div>
        <div class="flex gap-8">
            <a class="text-slate-400 hover:text-slate-600 dark:text-slate-500 font-public-sans text-xs uppercase tracking-widest transition-colors" href="#">Privacidad</a>
            <a class="text-slate-400 hover:text-slate-600 dark:text-slate-500 font-public-sans text-xs uppercase tracking-widest transition-colors" href="#">Términos</a>
            <a class="text-slate-400 hover:text-slate-600 dark:text-slate-500 font-public-sans text-xs uppercase tracking-widest transition-colors" href="#">Soporte</a>
        </div>
    </footer>
</body>
</html>