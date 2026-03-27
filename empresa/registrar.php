<?php
require_once '../conexion.php';
$pdo = conectar();
$ciudades = $pdo->query("SELECT cod_ciudad, nom_ciudad FROM ciudad ORDER BY nom_ciudad")->fetchAll();
$success = isset($_GET["success"]);
$error = $_GET["error"] ?? "";
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registro de Empresas - Observatorio Laboral</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <link rel="stylesheet" href="style.css">
    <script src="tailwind-config.js"></script>
</head>
<body class="bg-surface text-on-surface antialiased">

    <header class="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-stone-900 flex justify-between items-center w-full px-12 h-16 max-w-full font-['Public_Sans'] text-sm tracking-tight border-b-0">
        <div class="text-xl font-bold tracking-tighter text-green-800 dark:text-green-300">
            Observatorio Laboral
        </div>
        <nav class="hidden md:flex items-center space-x-8">
            <a class="text-stone-600 dark:text-stone-400 hover:text-green-700 dark:hover:text-green-300 hover:bg-stone-50 dark:hover:bg-stone-800 transition-colors duration-200" href="../index.html">Inicio</a>
            <a class="text-stone-600 dark:text-stone-400 hover:text-green-700 dark:hover:text-green-300 hover:bg-stone-50 dark:hover:bg-stone-800 transition-colors duration-200" href="../directorioOfertas/directorio.php">Ofertas de Empleo</a>
            <a class="text-stone-600 dark:text-stone-400 hover:text-green-700 dark:hover:text-green-300 hover:bg-stone-50 dark:hover:bg-stone-800 transition-colors duration-200" href="../directorioEmpresa/directorio.php">Empresas</a>
            <a class="text-stone-600 dark:text-stone-400 hover:text-green-700 dark:hover:text-green-300 hover:bg-stone-50 dark:hover:bg-stone-800 transition-colors duration-200" href="../directorioEgresado/directorio.php">Graduados</a>
        </nav>
        <div class="flex items-center space-x-4">
            <button class="p-2 text-green-700 dark:text-green-400 hover:bg-stone-50 dark:hover:bg-stone-800 rounded-full transition-colors duration-200">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="p-2 text-green-700 dark:text-green-400 hover:bg-stone-50 dark:hover:bg-stone-800 rounded-full transition-colors duration-200">
                <span class="material-symbols-outlined">account_circle</span>
            </button>
        </div>
    </header>

    <aside class="fixed left-0 top-16 bottom-0 w-64 bg-stone-50 dark:bg-stone-950 flex flex-col p-6 z-40 font-['Public_Sans'] text-sm font-medium border-r-0">
        <div class="mb-10">
            <h2 class="text-lg font-black text-green-800 dark:text-green-200">Portal Admin</h2>
            <p class="text-xs text-stone-500 uppercase tracking-widest mt-1">Suite de Gestión</p>
        </div>
        <nav class="flex flex-col space-y-2">
            <a class="flex items-center space-x-3 p-3 text-stone-500 dark:text-stone-500 hover:text-green-600 dark:hover:text-green-300 hover:translate-x-1 transition-transform duration-200 rounded-lg" href="../oferta/registrar.php">
                <span class="material-symbols-outlined">post_add</span>
                <span>Registrar Ofertas</span>
            </a>
            <a class="flex items-center space-x-3 p-3 text-stone-500 dark:text-stone-500 hover:text-green-600 dark:hover:text-green-300 hover:translate-x-1 transition-transform duration-200 rounded-lg" href="../egresado/registrar.php">
                <span class="material-symbols-outlined">school</span>
                <span>Registrar Graduados</span>
            </a>
            <a class="flex items-center space-x-3 p-3 text-green-700 dark:text-green-400 bg-white dark:bg-stone-900 shadow-sm rounded-lg" href="../empresa/registrar.php">
                <span class="material-symbols-outlined">domain</span>
                <span>Registrar Empresas</span>
            </a>
            <a class="flex items-center space-x-3 py-3 px-4 text-slate-500 hover:bg-slate-200 hover:pl-6 transition-all duration-300 rounded-l-lg group" href="../verPostulaciones/ver.php">
                <span class="material-symbols-outlined text-xl">description</span>
                <span class="font-medium text-sm">Ver Postulaciones</span>
            </a>
            <a class="flex items-center space-x-3 py-3 px-4 text-slate-500 hover:bg-slate-200 hover:pl-6 transition-all duration-300 rounded-l-lg group border-r-4 border-transparent" href="../reportes/reportes.php">
                <span class="material-symbols-outlined text-xl">analytics</span>
                <span class="font-medium text-sm">Reportes</span>
            </a>
        </nav>
        <div class="mt-auto pt-6 border-t border-stone-200 dark:border-stone-800">
            <div class="p-4 rounded-xl bg-primary-fixed/30 dark:bg-primary-container/20">
                <p class="text-[10px] font-bold text-on-primary-fixed uppercase tracking-tighter mb-1">Estado de Observación</p>
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                    <span class="text-xs font-semibold text-primary">Datos en Vivo Activos</span>
                </div>
            </div>
        </div>
    </aside>

    <main class="ml-64 mt-16 p-12 min-h-screen bg-surface">
        <div class="max-w-4xl mx-auto">
            <header class="mb-12">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-1 bg-primary"></div>
                    <span class="text-xs font-bold tracking-[0.2em] text-on-surface-variant uppercase">Módulo de Registro</span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight text-on-surface mb-3">Registrar Empresa</h1>
                <p class="text-lg text-on-surface-variant max-w-2xl font-normal leading-relaxed">
                    Amplíe nuestra red de socios industriales. Complete la información autorizada a continuación para integrar una nueva entidad corporativa en la base de datos del observatorio.
                </p>
            </header>

            <div class="bg-surface-container-low rounded-xl overflow-hidden">
                <form action="registro.php" method="POST" class="p-10 space-y-12">
                    <section>
                        <div class="flex items-baseline space-x-4 mb-8">
                            <span class="text-2xl font-bold text-outline-variant">01</span>
                            <h3 class="text-xl font-bold text-on-surface">Identidad de la Empresa</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="space-y-2">
                                <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-wider" for="nit">NIT (ID Tributario)</label>
                                <input name="nit" class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary-fixed-dim focus:border-transparent transition-all outline-none text-on-surface" id="nit" placeholder="ej. 900.123.456-7" type="text" required/>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-wider" for="name">Nombre (Razón Social)</label>
                                <input name="nombre" class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary-fixed-dim focus:border-transparent transition-all outline-none text-on-surface" id="name" placeholder="Nombre Oficial Registrado" type="text" required/>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="flex items-baseline space-x-4 mb-8">
                            <span class="text-2xl font-bold text-outline-variant">02</span>
                            <h3 class="text-xl font-bold text-on-surface">Contacto Principal</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="space-y-2">
                                <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-wider" for="contact">Persona de Contacto</label>
                                <input name="contacto" class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary-fixed-dim focus:border-transparent transition-all outline-none text-on-surface" id="contact" placeholder="Persona a cargo" type="text" required/>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-wider" for="email">Correo Electrónico</label>
                                <input name="email" class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary-fixed-dim focus:border-transparent transition-all outline-none text-on-surface" id="email" placeholder="corporativo@empresa.com" type="email" required/>
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-wider" for="phone">Número de Teléfono</label>
                                <input name="telefono" class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary-fixed-dim focus:border-transparent transition-all outline-none text-on-surface" id="phone" placeholder="+57 (___) ___ ____" type="tel" required/>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="flex items-baseline space-x-4 mb-8">
                            <span class="text-2xl font-bold text-outline-variant">03</span>
                            <h3 class="text-xl font-bold text-on-surface">Detalles de Ubicación</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-6 mb-6">
                            <div class="space-y-2">
                                <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-wider" for="country">País</label>
                                <select name="pais" class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary-fixed-dim focus:border-transparent transition-all outline-none text-on-surface appearance-none" id="country">
                                    <option>Colombia</option>
                                    <option>Internacional</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-wider" for="department">Departamento</label>
                                <input name="departamento" class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary-fixed-dim focus:border-transparent transition-all outline-none text-on-surface" id="department" placeholder="ej. Antioquia" type="text"/>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-wider" for="city">Ciudad</label>
                                <select name="ciudad" id="city" class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary-fixed-dim focus:border-transparent transition-all outline-none text-on-surface"><option value="">Seleccionar ciudad</option><?php foreach($ciudades as $c): ?><option value="<?= $c['cod_ciudad'] ?>"><?= htmlspecialchars($c['nom_ciudad']) ?></option><?php endforeach; ?></select>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-bold text-on-surface-variant uppercase tracking-wider" for="address">Dirección Física</label>
                            <input name="direccion" class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant/30 rounded-lg focus:ring-2 focus:ring-primary-fixed-dim focus:border-transparent transition-all outline-none text-on-surface" id="address" placeholder="Calle, Edificio, Oficina" type="text"/>
                        </div>
                    </section>

                    <div class="pt-8 border-t border-outline-variant/20 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center space-x-2 text-on-surface-variant italic text-sm">
                            <span class="material-symbols-outlined text-sm">info</span>
                            <span>Los datos serán validados en un plazo de 24 horas hábiles.</span>
                        </div>
                        <button class="w-full md:w-auto px-10 h-14 bg-primary text-white font-bold rounded-lg hover:bg-primary-container transition-all duration-300 shadow-md flex items-center justify-center space-x-2 active:scale-[0.98]" type="submit">
                            <span>Registrar Empresa</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                </form>
            </div>

            <footer class="mt-16 text-center text-[10px] font-bold text-outline uppercase tracking-[0.3em]">
                Observatorio Laboral © 2024 • Serie de Archivo Editorial
            </footer>
        </div>
    </main>

    <div class="fixed right-12 bottom-12 w-48 h-48 pointer-events-none opacity-10">
        <img class="w-full h-full object-cover rounded-full grayscale mix-blend-multiply" alt="mapa minimalista" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCWM_LjNQjVrc8Us8-C3rdQQwQ1ub65syeBSDYTWQzrhp8cxgvHb4jo8LU24sxTegTy9PbzgNcQEVJ8T_-oHxoXzmYVmXcg3xN_tJ69aG_pr9NFXHp3968EdkmqcPDqoCbGT15_rsZUKT6xiavAF2uk7A1iFpFx9GGODZ9eVGKf1L5KbJkXHOdnP0hi9J3q2AwLm1ga_Hha-8Go-2bkMpHPPXvpPMxItZ6qyVHaR8L9gtOxfXlMqjWJe2U9O3eMNg3uLAuYbHKOGG0"/>
    </div>
</body>
</html>