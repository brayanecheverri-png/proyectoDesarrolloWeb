<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registrar Oferta - Observatorio Laboral</title>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Public Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "outline-variant": "#bfcaba",
                        "primary-fixed-dim": "#88d982",
                        "on-error-container": "#93000a",
                        "outline": "#707a6c",
                        "on-primary-fixed": "#002204",
                        "on-secondary": "#ffffff",
                        "surface-variant": "#e2e2e2",
                        "secondary-fixed-dim": "#add0a6",
                        "on-secondary-container": "#4c6a48",
                        "surface-container-low": "#f3f3f3",
                        "on-primary-container": "#cbffc2",
                        "surface-container-high": "#e8e8e8",
                        "secondary": "#476644",
                        "inverse-surface": "#2f3131",
                        "surface-container": "#eeeeee",
                        "error": "#ba1a1a",
                        "on-surface-variant": "#40493d",
                        "on-primary": "#ffffff",
                        "background": "#f9f9f9",
                        "inverse-primary": "#88d982",
                        "on-background": "#1a1c1c",
                        "error-container": "#ffdad6",
                        "on-surface": "#1a1c1c",
                        "secondary-fixed": "#c9ecc1",
                        "surface-container-lowest": "#ffffff",
                        "primary-fixed": "#a3f69c",
                        "primary-container": "#2e7d32",
                        "primary": "#0d631b",
                        "surface-bright": "#f9f9f9",
                        "inverse-on-surface": "#f1f1f1",
                        "surface-container-highest": "#e2e2e2",
                        "secondary-container": "#c6e9be",
                        "surface-dim": "#dadada",
                        "on-error": "#ffffff",
                        "surface": "#f9f9f9"
                    },
                    fontFamily: {
                        "headline": ["Public Sans"],
                        "body":     ["Public Sans"],
                        "label":    ["Public Sans"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                },
            },
        };
    </script>
    <!-- Estilos propios -->
    <link rel="stylesheet" href="styles.css"/>
</head>
<body class="bg-background text-on-surface font-body min-h-screen flex flex-col">

    <!-- ====================================================
         BARRA SUPERIOR
    ===================================================== -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-slate-50 border-b border-slate-200 flex justify-between items-center w-full px-12 h-16">
        <div class="text-xl font-bold tracking-tighter text-green-800">Observatorio Laboral</div>
        <nav class="hidden md:flex items-center gap-8">
            <a class="text-slate-600 hover:text-green-600 transition-colors font-public-sans text-sm tracking-tight" href="/inicio/index.html">Inicio</a>
            <a class="text-slate-600 hover:text-green-600 transition-colors font-public-sans text-sm tracking-tight" href="../directorioOfertas/directorio.php">Ofertas de Empleo</a>
        </nav>
        <div class="flex items-center gap-4">
            <a href="../index.html"><button class="material-symbols-outlined text-slate-600 hover:bg-slate-100 p-2 rounded-full transition-all duration-200">logout</button></a>
        </div>
    </header>

    <div class="flex flex-1 pt-16">

        <!-- ====================================================
             BARRA LATERAL
        ===================================================== -->
        <aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 bg-slate-100 h-screen w-64 border-r border-slate-200 overflow-y-auto z-40">
            <div class="px-6 mb-8">
                <h2 class="font-public-sans text-sm font-bold text-green-700 uppercase tracking-wider">Gestión</h2>
                <p class="text-xs text-slate-500">Portal del Observatorio</p>
            </div>
            <nav class="flex flex-col gap-1 pr-4">
                <a class="sidebar-active flex items-center gap-3 px-6 py-3 transition-all duration-300" href="registrar.php">
                    <span class="material-symbols-outlined">post_add</span>
                    <span class="font-public-sans text-sm">Registrar Oferta</span>
                </a>
                <a class="flex items-center gap-3 px-6 py-3 text-slate-500 hover:bg-slate-200 hover:pl-8 transition-all duration-300" href="../verOfertaEmpleo/ver.php">
                    <span class="material-symbols-outlined">description</span>
                    <span class="font-public-sans text-sm">Ver Postulaciones</span>
                </a>
                <a class="flex items-center gap-3 px-6 py-3 text-slate-500 hover:bg-slate-200 hover:pl-8 transition-all duration-300" href="#">
                    <span class="material-symbols-outlined">analytics</span>
                    <span class="font-public-sans text-sm">Reportes</span>
                </a>
            </nav>
        </aside>

        <!-- ====================================================
             CONTENIDO PRINCIPAL
        ===================================================== -->
        <main class="ml-64 flex-1 bg-surface p-12 overflow-y-auto">

            <!-- Encabezado de página -->
            <div class="flex justify-between items-start mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-[0.1rem] text-on-surface-variant">Gestión de Vacantes</span>
                    <h1 class="text-3xl font-extrabold text-on-surface tracking-tight mt-1">Ofertas de Trabajo</h1>
                </div>
                <button id="btn-nueva"
                    class="flex items-center gap-2 bg-primary text-white px-5 py-3 rounded-lg font-bold text-sm hover:bg-primary-container transition-all shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-xl">add</span>
                    Nueva Oferta
                </button>
            </div>

            <!-- ── TABLA DE OFERTAS ── -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 shadow-sm mb-12 overflow-x-auto">
                <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-bold text-on-surface text-base">Ofertas Registradas</h2>
                    <div id="tabla-spinner" class="hidden">
                        <div class="spinner border-slate-400 border-t-primary"></div>
                    </div>
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <th class="py-3 px-6 text-[10px] font-bold uppercase tracking-widest text-slate-400">Cargo</th>
                            <th class="py-3 px-6 text-[10px] font-bold uppercase tracking-widest text-slate-400">Perfil</th>
                            <th class="py-3 px-6 text-[10px] font-bold uppercase tracking-widest text-slate-400">Ciudad</th>
                            <th class="py-3 px-6 text-[10px] font-bold uppercase tracking-widest text-slate-400">Salario</th>
                            <th class="py-3 px-6 text-[10px] font-bold uppercase tracking-widest text-slate-400">Vacantes</th>
                            <th class="py-3 px-6 text-[10px] font-bold uppercase tracking-widest text-slate-400">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-body">
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400 text-sm uppercase tracking-widest">
                                Cargando ofertas...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- ── FORMULARIO CREAR / EDITAR ── -->
            <div id="form-container" class="hidden">
                <div class="bg-surface-container-lowest p-12 rounded-xl shadow-sm border border-outline-variant/20 max-w-3xl">

                    <!-- Encabezado del formulario -->
                    <div class="mb-10 border-l-4 border-primary pl-6">
                        <span class="text-[10px] font-bold uppercase tracking-[0.1rem] text-on-surface-variant block mb-2">Nueva Entrada</span>
                        <h2 id="form-titulo" class="text-3xl font-extrabold text-on-surface tracking-tight">Registrar Oferta de Trabajo</h2>
                        <p id="form-subtitulo" class="mt-3 text-on-surface-variant leading-relaxed text-sm">
                            Complete los datos para publicar una nueva vacante en el sistema del observatorio.
                        </p>
                    </div>

                    <form id="form-oferta" class="space-y-8" novalidate>

                        <!-- Sección: Información Principal -->
                        <fieldset>
                            <legend class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-6 block">Información Principal</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <!-- Cargo -->
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cargo">
                                        Cargo <span class="text-error">*</span>
                                    </label>
                                    <input id="cargo" name="cargo" type="text"
                                        placeholder="Ej. Arquitecto de Software Senior"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Perfil -->
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="perfil">
                                        Perfil del Cargo
                                    </label>
                                    <input id="perfil" name="perfil" type="text"
                                        placeholder="Ej. Ingeniero en Sistemas"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Salario -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="salario">
                                        Salario
                                    </label>
                                    <input id="salario" name="salario" type="text"
                                        placeholder="Ej. $800 - $1200"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Número de Vacantes -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="num_vacante">
                                        Número de Vacantes
                                    </label>
                                    <input id="num_vacante" name="num_vacante" type="number" min="1"
                                        placeholder="1"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Horario -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="horario">
                                        Horario
                                    </label>
                                    <input id="horario" name="horario" type="text"
                                        placeholder="Ej. Tiempo completo"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Duración -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="duracion">
                                        Duración del Contrato
                                    </label>
                                    <input id="duracion" name="duracion" type="text"
                                        placeholder="Ej. Indefinido"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Requerimientos -->
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="requerimientos">
                                        Requerimientos
                                    </label>
                                    <textarea id="requerimientos" name="requerimientos" rows="3"
                                        placeholder="Describa los requerimientos del cargo..."
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40 resize-none"></textarea>
                                </div>

                                <!-- Experiencia -->
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="experiencia">
                                        Experiencia Requerida
                                    </label>
                                    <input id="experiencia" name="experiencia" type="text"
                                        placeholder="Ej. 2 años en desarrollo web"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                            </div>
                        </fieldset>

                        <!-- Separador -->
                        <hr class="border-outline-variant/30"/>

                        <!-- Sección: Software y Empresa -->
                        <fieldset>
                            <legend class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-6 block">Software y Empresa</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <!-- Software que maneja -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="nom_software_maneja">
                                        Software Requerido
                                    </label>
                                    <input id="nom_software_maneja" name="nom_software_maneja" type="text"
                                        placeholder="Ej. AutoCAD, SAP"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Nivel de manejo -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="nivel_manejo_software">
                                        Nivel de Manejo
                                    </label>
                                    <input id="nivel_manejo_software" name="nivel_manejo_software" type="text"
                                        placeholder="Ej. Avanzado"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- NIT Empresa -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="nit_empresa">
                                        NIT de Empresa <span class="text-error">*</span>
                                    </label>
                                    <input id="nit_empresa" name="nit_empresa" type="text"
                                        placeholder="Ej. 0614-050199-101-4"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Tipo de Contrato -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cod_contrato">
                                        Código de Contrato
                                    </label>
                                    <input id="cod_contrato" name="cod_contrato" type="text"
                                        placeholder="Ej. CT001"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                            </div>
                        </fieldset>

                        <!-- Separador -->
                        <hr class="border-outline-variant/30"/>

                        <!-- Sección: Clasificación -->
                        <fieldset>
                            <legend class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-6 block">Clasificación y Ubicación</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <!-- Nivel Educativo -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cod_nivel_edu">
                                        Código Nivel Educativo
                                    </label>
                                    <input id="cod_nivel_edu" name="cod_nivel_edu" type="text"
                                        placeholder="Ej. 3"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Idioma -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cod_idioma">
                                        Código de Idioma
                                    </label>
                                    <input id="cod_idioma" name="cod_idioma" type="text"
                                        placeholder="Ej. 1"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Ciudad -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cod_ciudad">
                                        Código de Ciudad
                                    </label>
                                    <input id="cod_ciudad" name="cod_ciudad" type="text"
                                        placeholder="Ej. SJ001"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Título Profesional -->
                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cod_titulo">
                                        Código de Título
                                    </label>
                                    <input id="cod_titulo" name="cod_titulo" type="text"
                                        placeholder="Ej. TT001"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <!-- Discapacidad -->
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cod_discapacidad">
                                        Código de Discapacidad (opcional)
                                    </label>
                                    <input id="cod_discapacidad" name="cod_discapacidad" type="text"
                                        placeholder="Ej. DC001"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                            </div>
                        </fieldset>

                        <!-- Botones -->
                        <div class="pt-4 flex flex-col gap-3">
                            <button id="btn-submit" type="submit"
                                class="w-full bg-primary hover:bg-primary-container text-white font-bold py-4 rounded-lg shadow-lg shadow-primary/10 transition-all duration-200 active:scale-[0.98] flex items-center justify-center gap-3">
                                <div class="spinner hidden"></div>
                                <span class="material-symbols-outlined">send</span>
                                <span>Publicar Oferta</span>
                            </button>
                            <button id="btn-cancelar" type="button"
                                class="hidden w-full bg-surface-container text-on-surface-variant font-bold py-4 rounded-lg transition-all hover:bg-surface-container-high active:scale-[0.98]">
                                Cancelar Edición
                            </button>
                            <p class="text-center text-[10px] text-on-surface-variant/60 uppercase tracking-[0.2em]">
                                Los datos se sincronizarán con la red del observatorio
                            </p>
                        </div>

                    </form>
                </div>
            </div>

        </main>
    </div>

    <!-- ====================================================
         MODAL DE CONFIRMACIÓN ELIMINAR
    ===================================================== -->
    <div id="modal-backdrop" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl p-10 max-w-sm w-full mx-4 text-center">
            <span class="material-symbols-outlined text-5xl text-error mb-4 block">warning</span>
            <h3 class="text-lg font-extrabold text-on-surface mb-2">¿Eliminar esta oferta?</h3>
            <p class="text-sm text-on-surface-variant mb-8">Esta acción no se puede deshacer. Se eliminarán también las postulaciones relacionadas.</p>
            <div class="flex gap-4">
                <button id="modal-confirmar-no"
                    class="flex-1 py-3 rounded-lg border border-outline-variant text-on-surface font-bold text-sm hover:bg-surface-container transition-colors">
                    Cancelar
                </button>
                <button id="modal-confirmar-si"
                    class="flex-1 py-3 rounded-lg bg-error text-white font-bold text-sm hover:opacity-90 transition-opacity">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>

    <!-- ====================================================
         TOAST DE NOTIFICACIONES
    ===================================================== -->
    <div id="toast"
        class="hidden fixed bottom-8 right-8 z-50 bg-primary text-white flex items-center gap-3 px-6 py-4 rounded-xl shadow-lg text-sm font-semibold">
        <span id="toast-icon" class="material-symbols-outlined text-xl">check_circle</span>
        <span id="toast-msg">Mensaje</span>
    </div>

    <!-- ====================================================
         PIE DE PÁGINA
    ===================================================== -->
    <footer class="bg-slate-50 w-full px-12 py-8 flex justify-between items-center border-t border-slate-200 z-10 relative ml-64">
        <div class="flex flex-col gap-1">
            <span class="text-sm font-black text-slate-800">Grupo Tridente</span>
            <span class="font-public-sans text-xs uppercase tracking-widest text-slate-400">© 2024 Observatorio Laboral. Todos los derechos reservados.</span>
        </div>
        <div class="flex gap-8">
            <a class="font-public-sans text-xs uppercase tracking-widest text-slate-400 hover:text-green-700 transition-colors" href="#">Política de Privacidad</a>
            <a class="font-public-sans text-xs uppercase tracking-widest text-slate-400 hover:text-green-700 transition-colors" href="#">Términos de Servicio</a>
            <a class="font-public-sans text-xs uppercase tracking-widest text-slate-400 hover:text-green-700 transition-colors" href="#">Soporte de Contacto</a>
        </div>
    </footer>

    <!-- JS propio -->
    <script src="oferta.js"></script>

</body>
</html>