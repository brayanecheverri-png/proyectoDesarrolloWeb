<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Informes y Analítica | Observatorio Laboral</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
</head>
<body class="bg-surface text-on-surface">
<nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border-b border-zinc-100 dark:border-zinc-800 shadow-sm dark:shadow-none flex justify-between items-center px-6 h-16">
    <div class="text-xl font-bold tracking-tighter text-emerald-800 dark:text-emerald-100">
        Observatorio Laboral
    </div>
    <div class="hidden md:flex gap-8 items-center font-public-sans text-sm tracking-tight">
        <a class="text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 transition-colors" href="#">Registrar Oferta</a>
        <a class="text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 transition-colors" href="#">Registrar Graduado</a>
        <a class="text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 transition-colors" href="#">Registrar Empresa</a>
        <a class="text-emerald-700 dark:text-emerald-400 font-semibold border-b-2 border-emerald-600 transition-colors" href="#">Informes</a>
    </div>
    <div class="flex items-center gap-4">
        <button class="p-2 text-zinc-600 hover:bg-zinc-50 rounded-full transition-colors active:scale-95 duration-200">
            <span class="material-symbols-outlined">notifications</span>
        </button>
        <button class="p-2 text-zinc-600 hover:bg-zinc-50 rounded-full transition-colors active:scale-95 duration-200">
            <span class="material-symbols-outlined">help</span>
        </button>
        <div class="h-8 w-8 rounded-full bg-primary-fixed flex items-center justify-center overflow-hidden">
            <img alt="Perfil de usuario" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAPmC98eGx_JR9dpdJUE-5hvokDmW1jWr9ow6bydSoliLAiBxpGiOzqaGg6cY4lwNtRPvMjU3b-NIg226R1tsUTjupf0EHnDq1A552iVNGKa4kfEPaph77r3Y-exQ0ctas-9gUhfSAdcj-GmJCr9t3t7WEx_2aBHEibIcRJd5pTQw23Xk_JQJbRruPUFZaCgHVJKI16o2h-m2K2tFisTC61ZhdYLxeJ1MmO5NHo9UFAUJaYxv9smxk0YGUeLIezzFa4k9tcTKOGksk"/>
        </div>
    </div>
</nav>

<aside class="h-screen w-64 fixed left-0 top-0 pt-16 bg-zinc-50 dark:bg-zinc-950 flex flex-col gap-1 py-4 hidden lg:flex border-r border-zinc-100">
    <div class="px-6 py-4">
        <h2 class="text-lg font-black text-emerald-900 dark:text-emerald-50">Gestión</h2>
        <p class="text-xs text-zinc-500 font-medium">Portal del Mercado Laboral</p>
    </div>
    <nav class="flex-1 px-2 space-y-1">
        <a class="flex items-center px-4 py-3 text-zinc-500 hover:bg-emerald-50/50 hover:text-emerald-700 transition-all duration-300 font-medium text-sm rounded-lg" href="#">
            <span class="material-symbols-outlined mr-3">post_add</span> Registrar Oferta
        </a>
        <a class="flex items-center px-4 py-3 text-zinc-500 hover:bg-emerald-50/50 hover:text-emerald-700 transition-all duration-300 font-medium text-sm rounded-lg" href="#">
            <span class="material-symbols-outlined mr-3">school</span> Registrar Graduado
        </a>
        <a class="flex items-center px-4 py-3 text-zinc-500 hover:bg-emerald-50/50 hover:text-emerald-700 transition-all duration-300 font-medium text-sm rounded-lg" href="#">
            <span class="material-symbols-outlined mr-3">domain</span> Registrar Empresa
        </a>
        <a class="flex items-center px-4 py-3 text-zinc-500 hover:bg-emerald-50/50 hover:text-emerald-700 transition-all duration-300 font-medium text-sm rounded-lg" href="#">
            <span class="material-symbols-outlined mr-3">list_alt</span> Ver Postulaciones
        </a>
        <a class="flex items-center bg-white dark:bg-zinc-900 text-emerald-700 dark:text-emerald-400 shadow-sm rounded-lg px-4 py-3 transition-all duration-300 font-medium text-sm" href="#">
            <span class="material-symbols-outlined mr-3">analytics</span> Informes
        </a>
    </nav>
    <div class="px-2 mt-auto border-t border-zinc-100 pt-4">
        <a class="flex items-center px-4 py-3 text-zinc-500 hover:bg-emerald-50/50 hover:text-emerald-700 transition-all duration-300 font-medium text-sm rounded-lg" href="#">
            <span class="material-symbols-outlined mr-3">settings</span> Configuración
        </a>
        <a class="flex items-center px-4 py-3 text-zinc-500 hover:bg-emerald-50/50 hover:text-emerald-700 transition-all duration-300 font-medium text-sm rounded-lg" href="#">
            <span class="material-symbols-outlined mr-3">logout</span> Cerrar Sesión
        </a>
    </div>
</aside>

<main class="lg:ml-64 pt-24 px-6 pb-12 min-h-screen bg-surface">
    <div class="max-w-7xl mx-auto">
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <span class="label-md text-[10px] tracking-[0.05rem] text-primary font-bold uppercase mb-2 block">Panel Analítico</span>
                <h1 class="text-4xl font-extrabold tracking-tight text-on-surface">Informes y Analítica</h1>
                <p class="text-on-surface-variant body-lg mt-2 max-w-xl">Información detallada sobre la dinámica de la fuerza laboral, las tasas de empleo de los graduados y la demanda del mercado dentro del ecosistema del observatorio.</p>
            </div>
            <div class="flex items-center gap-3 bg-surface-container-low p-2 rounded-xl">
                <div class="flex items-center gap-2 px-4 py-2 bg-surface-container-lowest rounded-lg border border-outline-variant/20 shadow-sm">
                    <span class="material-symbols-outlined text-zinc-400 text-sm">calendar_today</span>
                    <input class="bg-transparent border-none text-sm font-medium focus:ring-0 p-0 text-on-surface w-48" type="text" value="01 Ene, 2023 - 31 Oct, 2023"/>
                </div>
                <button class="bg-primary text-on-primary px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-container transition-colors shadow-sm">
                    Filtrar Datos
                </button>
            </div>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_12px_32px_-4px_rgba(0,34,4,0.04)] relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <span class="material-symbols-outlined text-6xl">school</span>
                </div>
                <p class="label-md text-[10px] tracking-[0.05rem] text-zinc-500 font-bold uppercase mb-4">Total de Graduados Registrados</p>
                <h3 class="text-5xl font-black text-on-primary-fixed-variant tracking-tighter">12,482</h3>
                <div class="mt-6 flex items-center gap-2 text-emerald-600 text-sm font-bold">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    <span>+12.5% vs el año pasado</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_12px_32px_-4px_rgba(0,34,4,0.04)] relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <span class="material-symbols-outlined text-6xl">domain</span>
                </div>
                <p class="label-md text-[10px] tracking-[0.05rem] text-zinc-500 font-bold uppercase mb-4">Empresas Aliadas</p>
                <h3 class="text-5xl font-black text-on-primary-fixed-variant tracking-tighter">1,240</h3>
                <div class="mt-6 flex items-center gap-2 text-emerald-600 text-sm font-bold">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    <span>+8.2% desde el T2</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_12px_32px_-4px_rgba(0,34,4,0.04)] relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <span class="material-symbols-outlined text-6xl">work_outline</span>
                </div>
                <p class="label-md text-[10px] tracking-[0.05rem] text-zinc-500 font-bold uppercase mb-4">Ofertas de Empleo Activas</p>
                <h3 class="text-5xl font-black text-on-primary-fixed-variant tracking-tighter">3,812</h3>
                <div class="mt-6 flex items-center gap-2 text-tertiary text-sm font-bold">
                    <span class="material-symbols-outlined text-sm">trending_down</span>
                    <span>-2.1% este mes</span>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            <div class="lg:col-span-8 bg-surface-container-lowest p-8 rounded-xl shadow-[0_12px_32px_-4px_rgba(0,34,4,0.04)]">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h4 class="text-xl font-bold text-on-surface">Tendencias Mensuales de Postulación</h4>
                        <p class="text-sm text-zinc-500">Volumen de postulaciones enviadas por mes</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span> 2023
                        </span>
                        <span class="flex items-center gap-1.5 text-xs font-medium text-zinc-400 bg-zinc-50 px-3 py-1 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-zinc-300"></span> 2022
                        </span>
                    </div>
                </div>
                <div class="h-80 flex items-end gap-1 relative pt-10">
                    <div class="absolute left-0 h-full flex flex-col justify-between text-[10px] text-zinc-400 font-medium -ml-2 pb-6">
                        <span>4k</span><span>3k</span><span>2k</span><span>1k</span><span>0</span>
                    </div>
                    <div class="absolute inset-0 flex flex-col justify-between pb-6 pointer-events-none">
                        <div class="border-b border-zinc-100 w-full h-0"></div>
                        <div class="border-b border-zinc-100 w-full h-0"></div>
                        <div class="border-b border-zinc-100 w-full h-0"></div>
                        <div class="border-b border-zinc-100 w-full h-0"></div>
                    </div>
                    <div class="flex-1 flex items-end justify-around gap-2 px-6 h-full z-10">
                        <div class="w-full bg-primary-fixed-dim/20 rounded-t-lg h-[40%] group relative">
                            <div class="absolute inset-x-0 bottom-0 bg-primary rounded-t-lg h-[65%] transition-all duration-300"></div>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-zinc-500 font-bold">ENE</span>
                        </div>
                        <div class="w-full bg-primary-fixed-dim/20 rounded-t-lg h-[45%] group relative">
                            <div class="absolute inset-x-0 bottom-0 bg-primary rounded-t-lg h-[70%]"></div>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-zinc-500 font-bold">FEB</span>
                        </div>
                        <div class="w-full bg-primary-fixed-dim/20 rounded-t-lg h-[60%] group relative">
                            <div class="absolute inset-x-0 bottom-0 bg-primary rounded-t-lg h-[85%]"></div>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-zinc-500 font-bold">MAR</span>
                        </div>
                        <div class="w-full bg-primary-fixed-dim/20 rounded-t-lg h-[55%] group relative">
                            <div class="absolute inset-x-0 bottom-0 bg-primary rounded-t-lg h-[75%]"></div>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-zinc-500 font-bold">ABR</span>
                        </div>
                        <div class="w-full bg-primary-fixed-dim/20 rounded-t-lg h-[75%] group relative">
                            <div class="absolute inset-x-0 bottom-0 bg-primary rounded-t-lg h-[90%]"></div>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-zinc-500 font-bold">MAY</span>
                        </div>
                        <div class="w-full bg-primary-fixed-dim/20 rounded-t-lg h-[90%] group relative">
                            <div class="absolute inset-x-0 bottom-0 bg-primary rounded-t-lg h-[95%]"></div>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-zinc-500 font-bold">JUN</span>
                        </div>
                        <div class="w-full bg-primary-fixed-dim/20 rounded-t-lg h-[80%] group relative">
                            <div class="absolute inset-x-0 bottom-0 bg-primary rounded-t-lg h-[80%]"></div>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-zinc-500 font-bold">JUL</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-4 bg-surface-container-lowest p-8 rounded-xl shadow-[0_12px_32px_-4px_rgba(0,34,4,0.04)]">
                <h4 class="text-xl font-bold text-on-surface mb-6">Distribución por Ciudad</h4>
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-sm font-medium mb-2">
                            <span>Área Metropolitana</span>
                            <span class="text-zinc-500">4,210 (45%)</span>
                        </div>
                        <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full" style="width: 45%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm font-medium mb-2">
                            <span>Departamento Norte</span>
                            <span class="text-zinc-500">2,840 (28%)</span>
                        </div>
                        <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full opacity-80" style="width: 28%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm font-medium mb-2">
                            <span>Región Costa</span>
                            <span class="text-zinc-500">1,920 (18%)</span>
                        </div>
                        <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full opacity-60" style="width: 18%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm font-medium mb-2">
                            <span>Distrito Sur</span>
                            <span class="text-zinc-500">1,102 (9%)</span>
                        </div>
                        <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full opacity-40" style="width: 9%;"></div>
                        </div>
                    </div>
                </div>
                <button class="w-full mt-10 py-3 border border-outline-variant/30 text-primary text-sm font-bold rounded-lg hover:bg-emerald-50 transition-colors">
                    Ver Mapa Detallado
                </button>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-12 bg-surface-container-lowest p-8 rounded-xl shadow-[0_12px_32px_-4px_rgba(0,34,4,0.04)]">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h4 class="text-xl font-bold text-on-surface">Principales Empresas que Contratan</h4>
                        <p class="text-sm text-zinc-500">Corporaciones con la mayor tasa de colocación en este trimestre</p>
                    </div>
                    <button class="text-primary text-sm font-bold hover:underline">Descargar CSV</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-zinc-400 label-md text-[10px] tracking-[0.05rem] uppercase border-b border-zinc-50">
                                <th class="pb-4 font-bold">Empresa</th>
                                <th class="pb-4 font-bold">Sector Industrial</th>
                                <th class="pb-4 font-bold">Ofertas Totales</th>
                                <th class="pb-4 font-bold">Tasa de Colocación</th>
                                <th class="pb-4 font-bold">Crecimiento</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50">
                            <tr class="hover:bg-zinc-50/50 transition-colors group">
                                <td class="py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded bg-emerald-50 flex items-center justify-center font-bold text-emerald-800">TN</div>
                                        <span class="font-bold text-on-surface">TechNova Solutions</span>
                                    </div>
                                </td>
                                <td class="py-5 text-sm font-medium text-zinc-600">Tecnología de la Información</td>
                                <td class="py-5 text-sm font-bold text-on-surface">342</td>
                                <td class="py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold">92%</span>
                                        <div class="w-16 h-1 bg-zinc-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500" style="width: 92%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <span class="text-emerald-600 text-xs font-bold px-2 py-1 bg-emerald-50 rounded">+14.2%</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-zinc-50/50 transition-colors group">
                                <td class="py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded bg-emerald-50 flex items-center justify-center font-bold text-emerald-800">GH</div>
                                        <span class="font-bold text-on-surface">Green Horizon Logistics</span>
                                    </div>
                                </td>
                                <td class="py-5 text-sm font-medium text-zinc-600">Cadena de Suministro</td>
                                <td class="py-5 text-sm font-bold text-on-surface">218</td>
                                <td class="py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold">88%</span>
                                        <div class="w-16 h-1 bg-zinc-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500" style="width: 88%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <span class="text-emerald-600 text-xs font-bold px-2 py-1 bg-emerald-50 rounded">+8.5%</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-zinc-50/50 transition-colors group">
                                <td class="py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded bg-emerald-50 flex items-center justify-center font-bold text-emerald-800">FS</div>
                                        <span class="font-bold text-on-surface">FinStream Banking</span>
                                    </div>
                                </td>
                                <td class="py-5 text-sm font-medium text-zinc-600">Finanzas</td>
                                <td class="py-5 text-sm font-bold text-on-surface">195</td>
                                <td class="py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold">75%</span>
                                        <div class="w-16 h-1 bg-zinc-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500/80" style="width: 75%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <span class="text-tertiary text-xs font-bold px-2 py-1 bg-tertiary/5 rounded">-2.1%</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</main>

<div class="md:hidden fixed bottom-0 w-full bg-white border-t border-zinc-100 px-6 py-3 flex justify-between items-center z-50">
    <button class="flex flex-col items-center gap-1 text-zinc-400">
        <span class="material-symbols-outlined">post_add</span>
        <span class="text-[10px] font-bold">OFERTAS</span>
    </button>
    <button class="flex flex-col items-center gap-1 text-zinc-400">
        <span class="material-symbols-outlined">school</span>
        <span class="text-[10px] font-bold">GRADUADOS</span>
    </button>
    <button class="flex flex-col items-center gap-1 text-emerald-700">
        <span class="material-symbols-outlined">analytics</span>
        <span class="text-[10px] font-bold">INFORMES</span>
    </button>
    <button class="flex flex-col items-center gap-1 text-zinc-400">
        <span class="material-symbols-outlined">settings</span>
        <span class="text-[10px] font-bold">CONFIGURACIÓN</span>
    </button>
</div>
</body>
</html>