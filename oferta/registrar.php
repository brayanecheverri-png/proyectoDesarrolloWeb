<?php
session_start();
require_once '../conexion.php';
$pdo = conectar();

// ============================================================
//  MANEJO DE ACCIONES (POST directo, sin API separada)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = trim($_POST['accion'] ?? '');

    if ($accion === 'crear') {
        $cargo      = trim($_POST['cargo']          ?? '');
        $perfil     = trim($_POST['perfil']         ?? '');
        $salario    = trim($_POST['salario']        ?? '') ?: null;
        $reqs       = trim($_POST['requerimientos'] ?? '') ?: null;
        $experiencia= trim($_POST['experiencia']    ?? '') ?: null;
        $vacantes   = intval($_POST['num_vacante']  ?? 1);
        $horario    = trim($_POST['horario']        ?? '') ?: null;
        $duracion   = trim($_POST['duracion']       ?? '') ?: null;
        $software   = trim($_POST['nom_software_maneja']   ?? '') ?: null;
        $nivel_sw   = trim($_POST['nivel_manejo_software'] ?? '') ?: null;
        $nit        = trim($_POST['nit_empresa']    ?? '') ?: ($_SESSION['cod_empresa'] ?? null);
        $cod_nivel  = trim($_POST['cod_nivel_edu']  ?? '') ?: null;
        $cod_idioma = trim($_POST['cod_idioma']     ?? '') ?: null;
        $cod_ciudad = trim($_POST['cod_ciudad']     ?? '') ?: null;
        $cod_disc   = trim($_POST['cod_discapacidad'] ?? '') ?: null;

        if (empty($cargo)) {
            responder(false, 'El campo Cargo es obligatorio.');
        }
        if (empty($nit)) {
            responder(false, 'No se encontró el NIT de la empresa. Inicia sesión nuevamente.');
        }

        $num_oferta = 'OF-' . date('Ymd') . '-' . rand(100, 999);

        try {
            $stmt = $pdo->prepare("
                INSERT INTO oferta_trabajo_of
                    (num_oferta, nom_puesto_trabajo, descripcion, requisitos, salario,
                     num_vacantes, horario, duracion, experiencia,
                     cod_empresa, cod_discapacidad, cod_idioma, cod_nivel, cod_ciudad, estado)
                VALUES
                    (:num_oferta, :cargo, :perfil, :reqs, :salario,
                     :vacantes, :horario, :duracion, :experiencia,
                     :nit, :cod_disc, :cod_idioma, :cod_nivel, :cod_ciudad, 'AC')
            ");
            $stmt->execute([
                ':num_oferta'  => $num_oferta,
                ':cargo'       => $cargo,
                ':perfil'      => $perfil,
                ':reqs'        => $reqs,
                ':salario'     => $salario,
                ':vacantes'    => $vacantes,
                ':horario'     => $horario,
                ':duracion'    => $duracion,
                ':experiencia' => $experiencia,
                ':nit'         => $nit,
                ':cod_disc'    => $cod_disc,
                ':cod_idioma'  => $cod_idioma,
                ':cod_nivel'   => $cod_nivel,
                ':cod_ciudad'  => $cod_ciudad,
            ]);
            responder(true, 'Oferta publicada correctamente.', ['id' => $pdo->lastInsertId()]);
        } catch (PDOException $e) {
            responder(false, 'Error al guardar: ' . $e->getMessage());
        }
    }

    if ($accion === 'actualizar') {
        $id         = intval($_POST['cod_oferta']   ?? 0);
        $cargo      = trim($_POST['cargo']          ?? '');
        $perfil     = trim($_POST['perfil']         ?? '');
        $salario    = trim($_POST['salario']        ?? '') ?: null;
        $reqs       = trim($_POST['requerimientos'] ?? '') ?: null;
        $experiencia= trim($_POST['experiencia']    ?? '') ?: null;
        $vacantes   = intval($_POST['num_vacante']  ?? 1);
        $horario    = trim($_POST['horario']        ?? '') ?: null;
        $duracion   = trim($_POST['duracion']       ?? '') ?: null;
        $nit        = trim($_POST['nit_empresa']    ?? '') ?: ($_SESSION['cod_empresa'] ?? null);
        $cod_nivel  = trim($_POST['cod_nivel_edu']  ?? '') ?: null;
        $cod_idioma = trim($_POST['cod_idioma']     ?? '') ?: null;
        $cod_ciudad = trim($_POST['cod_ciudad']     ?? '') ?: null;
        $cod_disc   = trim($_POST['cod_discapacidad'] ?? '') ?: null;

        if (!$id)        responder(false, 'ID de oferta inválido.');
        if (empty($cargo)) responder(false, 'El campo Cargo es obligatorio.');

        try {
            $stmt = $pdo->prepare("
                UPDATE oferta_trabajo_of SET
                    nom_puesto_trabajo = :cargo,
                    descripcion        = :perfil,
                    requisitos         = :reqs,
                    salario            = :salario,
                    num_vacantes       = :vacantes,
                    horario            = :horario,
                    duracion           = :duracion,
                    experiencia        = :experiencia,
                    cod_empresa        = :nit,
                    cod_discapacidad   = :cod_disc,
                    cod_idioma         = :cod_idioma,
                    cod_nivel          = :cod_nivel,
                    cod_ciudad         = :cod_ciudad
                WHERE cod_oferta = :id
            ");
            $stmt->execute([
                ':cargo'       => $cargo,
                ':perfil'      => $perfil,
                ':reqs'        => $reqs,
                ':salario'     => $salario,
                ':vacantes'    => $vacantes,
                ':horario'     => $horario,
                ':duracion'    => $duracion,
                ':experiencia' => $experiencia,
                ':nit'         => $nit,
                ':cod_disc'    => $cod_disc,
                ':cod_idioma'  => $cod_idioma,
                ':cod_nivel'   => $cod_nivel,
                ':cod_ciudad'  => $cod_ciudad,
                ':id'          => $id,
            ]);
            responder(true, 'Oferta actualizada correctamente.');
        } catch (PDOException $e) {
            responder(false, 'Error al actualizar: ' . $e->getMessage());
        }
    }

    if ($accion === 'eliminar') {
        $id = intval($_POST['cod_oferta'] ?? 0);
        if (!$id) responder(false, 'ID inválido.');
        try {
            $pdo->prepare("DELETE FROM postulacion WHERE cod_oferta = ?")->execute([$id]);
            $stmt = $pdo->prepare("DELETE FROM oferta_trabajo_of WHERE cod_oferta = ?");
            $stmt->execute([$id]);
            if ($stmt->rowCount() === 0) responder(false, 'Oferta no encontrada.');
            responder(true, 'Oferta eliminada correctamente.');
        } catch (PDOException $e) {
            responder(false, 'Error al eliminar: ' . $e->getMessage());
        }
    }
}

// Acción GET: listar o obtener
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = trim($_GET['accion'] ?? '');

    if ($accion === 'listar') {
        try {
            $stmt = $pdo->query("
                SELECT o.cod_oferta,
                       o.nom_puesto_trabajo AS cargo,
                       o.descripcion        AS perfil,
                       o.salario,
                       o.num_vacantes       AS num_vacante,
                       o.horario, o.duracion, o.experiencia,
                       o.requisitos         AS requerimientos,
                       o.fecha_publicacion,
                       o.estado,
                       e.nom_empresa,
                       e.cod_empresa        AS nit_empresa,
                       c.nom_ciudad,
                       o.cod_ciudad,
                       o.cod_nivel          AS cod_nivel_edu,
                       o.cod_idioma,
                       o.cod_discapacidad
                FROM oferta_trabajo_of o
                LEFT JOIN empresa_of e ON o.cod_empresa = e.cod_empresa
                LEFT JOIN ciudad c     ON o.cod_ciudad  = c.cod_ciudad
                ORDER BY o.cod_oferta DESC
            ");
            responder(true, 'OK', ['ofertas' => $stmt->fetchAll()]);
        } catch (PDOException $e) {
            responder(false, 'Error al listar: ' . $e->getMessage());
        }
    }

    if ($accion === 'obtener') {
        $id = intval($_GET['cod_oferta'] ?? 0);
        if (!$id) responder(false, 'ID inválido.');
        try {
            $stmt = $pdo->prepare("
                SELECT o.*, e.nom_empresa, c.nom_ciudad,
                       n.nom_nivel_educativo, i.nom_idioma, d.nom_discapacidad,
                       o.cod_nivel AS cod_nivel_edu
                FROM oferta_trabajo_of o
                LEFT JOIN empresa_of e      ON o.cod_empresa = e.cod_empresa
                LEFT JOIN ciudad c          ON o.cod_ciudad  = c.cod_ciudad
                LEFT JOIN nivel_educativo n ON o.cod_nivel   = n.cod_nivel_educativo
                LEFT JOIN idioma i          ON o.cod_idioma  = i.cod_idioma
                LEFT JOIN discapacidad d    ON o.cod_discapacidad = d.cod_discapacidad
                WHERE o.cod_oferta = ?
            ");
            $stmt->execute([$id]);
            $oferta = $stmt->fetch();
            if (!$oferta) responder(false, 'Oferta no encontrada.');
            responder(true, 'OK', ['oferta' => $oferta]);
        } catch (PDOException $e) {
            responder(false, 'Error: ' . $e->getMessage());
        }
    }
}

// ============================================================
//  CARGA DE DATOS PARA EL FORMULARIO (render HTML)
// ============================================================
$ciudades       = $pdo->query("SELECT cod_ciudad, nom_ciudad FROM ciudad ORDER BY nom_ciudad")->fetchAll();
$niveles        = $pdo->query("SELECT cod_nivel_educativo, nom_nivel_educativo FROM nivel_educativo")->fetchAll();
$idiomas        = $pdo->query("SELECT cod_idioma, nom_idioma FROM idioma")->fetchAll();
$discapacidades = $pdo->query("SELECT cod_discapacidad, nom_discapacidad FROM discapacidad")->fetchAll();
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registrar Oferta - Observatorio Laboral</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
    <link rel="stylesheet" href="styles.css"/>
</head>
<body class="bg-background text-on-surface font-body min-h-screen flex flex-col">

    <!-- BARRA SUPERIOR -->
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
    <aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 bg-white h-screen w-64 border-r border-slate-100 hidden lg:flex">
    <div class="px-6 mb-6">
        <h2 class="text-slate-800 font-bold text-lg">Gestión Empresa</h2>
        <p class="text-slate-400 text-xs uppercase tracking-widest">Portal Empresarial</p>
        </div>
        <!-- BARRA LATERAL -->
        <nav class="flex flex-col gap-1 pr-4 text-sm font-medium">
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="../oferta/registrar.php">
            <span class="material-symbols-outlined">school</span> Publicar Oferta Empleo
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="directorio.php">
            <span class="material-symbols-outlined">school</span> Directorio Egresados
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="../verOfertaEmpleo/ver.php">
            <span class="material-symbols-outlined">description</span> Mis Postulaciones
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="../reportes/reportes.php">
            <span class="material-symbols-outlined">description</span> Reportes
        </a>
        </nav>
    </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="ml-64 flex-1 bg-surface p-12 overflow-y-auto">

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

            <!-- TABLA DE OFERTAS -->
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

            <!-- FORMULARIO CREAR / EDITAR -->
            <div id="form-container" class="hidden">
                <div class="bg-surface-container-lowest p-12 rounded-xl shadow-sm border border-outline-variant/20 max-w-3xl">

                    <div class="mb-10 border-l-4 border-primary pl-6">
                        <span class="text-[10px] font-bold uppercase tracking-[0.1rem] text-on-surface-variant block mb-2">Nueva Entrada</span>
                        <h2 id="form-titulo" class="text-3xl font-extrabold text-on-surface tracking-tight">Registrar Oferta de Trabajo</h2>
                        <p id="form-subtitulo" class="mt-3 text-on-surface-variant leading-relaxed text-sm">
                            Complete los datos para publicar una nueva vacante en el sistema del observatorio.
                        </p>
                    </div>

                    <form id="form-oferta" class="space-y-8" novalidate>

                        <!-- Información Principal -->
                        <fieldset>
                            <legend class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-6 block">Información Principal</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cargo">
                                        Cargo <span class="text-error">*</span>
                                    </label>
                                    <input id="cargo" name="cargo" type="text"
                                        placeholder="Ej. Arquitecto de Software Senior"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="perfil">
                                        Perfil del Cargo
                                    </label>
                                    <input id="perfil" name="perfil" type="text"
                                        placeholder="Ej. Ingeniero en Sistemas"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="salario">
                                        Salario
                                    </label>
                                    <input id="salario" name="salario" type="text"
                                        placeholder="Ej. $800 - $1200"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="num_vacante">
                                        Número de Vacantes
                                    </label>
                                    <input id="num_vacante" name="num_vacante" type="number" min="1"
                                        placeholder="1"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="horario">
                                        Horario
                                    </label>
                                    <input id="horario" name="horario" type="text"
                                        placeholder="Ej. Tiempo completo"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="duracion">
                                        Duración del Contrato
                                    </label>
                                    <input id="duracion" name="duracion" type="text"
                                        placeholder="Ej. Indefinido"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40"/>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="requerimientos">
                                        Requerimientos
                                    </label>
                                    <textarea id="requerimientos" name="requerimientos" rows="3"
                                        placeholder="Describa los requerimientos del cargo..."
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface placeholder:text-on-surface-variant/40 resize-none"></textarea>
                                </div>

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

                        <hr class="border-outline-variant/30"/>

                        <!-- Empresa -->
                        <fieldset>
                            <legend class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-6 block">Empresa</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="nit_empresa">
                                        NIT de Empresa <span class="text-error">*</span>
                                    </label>
                                    <input id="nit_empresa" name="nit_empresa" type="text"
                                        value="<?= htmlspecialchars($_SESSION['cod_empresa'] ?? '') ?>"
                                        readonly
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface opacity-60 cursor-not-allowed"/>
                                </div>

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

                        <hr class="border-outline-variant/30"/>

                        <!-- Clasificación -->
                        <fieldset>
                            <legend class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-6 block">Clasificación y Ubicación</legend>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cod_nivel_edu">
                                        Nivel Educativo
                                    </label>
                                    <select id="cod_nivel_edu" name="cod_nivel_edu"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface">
                                        <option value="">Seleccionar nivel...</option>
                                        <?php foreach ($niveles as $n): ?>
                                        <option value="<?= htmlspecialchars($n['cod_nivel_educativo']) ?>">
                                            <?= htmlspecialchars($n['nom_nivel_educativo']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cod_idioma">
                                        Idioma Requerido
                                    </label>
                                    <select id="cod_idioma" name="cod_idioma"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface">
                                        <option value="">Seleccionar idioma...</option>
                                        <?php foreach ($idiomas as $i): ?>
                                        <option value="<?= htmlspecialchars($i['cod_idioma']) ?>">
                                            <?= htmlspecialchars($i['nom_idioma']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cod_ciudad">
                                        Ciudad
                                    </label>
                                    <select id="cod_ciudad" name="cod_ciudad"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface">
                                        <option value="">Seleccionar ciudad...</option>
                                        <?php foreach ($ciudades as $c): ?>
                                        <option value="<?= htmlspecialchars($c['cod_ciudad']) ?>">
                                            <?= htmlspecialchars($c['nom_ciudad']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2" for="cod_discapacidad">
                                        Inclusión / Discapacidad (opcional)
                                    </label>
                                    <select id="cod_discapacidad" name="cod_discapacidad"
                                        class="w-full bg-surface-container-low border-0 rounded-lg p-4 text-sm text-on-surface">
                                        <option value="">Sin requisito específico</option>
                                        <?php foreach ($discapacidades as $d): ?>
                                        <option value="<?= htmlspecialchars($d['cod_discapacidad']) ?>">
                                            <?= htmlspecialchars($d['nom_discapacidad']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
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

    <!-- MODAL ELIMINAR -->
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

    <!-- TOAST -->
    <div id="toast"
        class="hidden fixed bottom-8 right-8 z-50 bg-primary text-white flex items-center gap-3 px-6 py-4 rounded-xl shadow-lg text-sm font-semibold">
        <span id="toast-icon" class="material-symbols-outlined text-xl">check_circle</span>
        <span id="toast-msg">Mensaje</span>
    </div>

    <!-- PIE DE PÁGINA -->
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

<script>
// ============================================================
//  JS — apunta al mismo archivo (registrar.php)
// ============================================================
const API = 'registrar.php';
let editandoId = null;

document.addEventListener('DOMContentLoaded', () => {
    cargarOfertas();
    document.getElementById('form-oferta').addEventListener('submit', manejarEnvio);
    document.getElementById('btn-cancelar').addEventListener('click', cancelarEdicion);
    document.getElementById('btn-nueva').addEventListener('click', () => mostrarFormulario(false));
    document.getElementById('modal-confirmar-si').addEventListener('click', confirmarEliminar);
    document.getElementById('modal-confirmar-no').addEventListener('click', cerrarModal);
});

async function cargarOfertas() {
    try {
        mostrarCargaTabla(true);
        const res  = await fetch(`${API}?accion=listar`);
        const data = await res.json();
        if (!data.ok) throw new Error(data.mensaje);
        renderizarTabla(data.ofertas);
    } catch (e) {
        mostrarToast('Error al cargar ofertas: ' + e.message, 'error');
    } finally {
        mostrarCargaTabla(false);
    }
}

function renderizarTabla(ofertas) {
    const tbody = document.getElementById('tabla-body');
    tbody.innerHTML = '';
    if (!ofertas || ofertas.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-12 text-slate-400 text-sm uppercase tracking-widest">No hay ofertas registradas</td></tr>`;
        return;
    }
    ofertas.forEach(o => {
        const tr = document.createElement('tr');
        tr.className = 'border-b border-slate-100 hover:bg-slate-50 transition-colors';
        tr.innerHTML = `
            <td class="py-4 px-6 text-sm font-semibold text-on-surface">${esc(o.cargo)}</td>
            <td class="py-4 px-6 text-sm text-slate-500">${esc(o.perfil)}</td>
            <td class="py-4 px-6 text-sm text-slate-500">${esc(o.nom_ciudad || o.cod_ciudad || '—')}</td>
            <td class="py-4 px-6 text-sm text-slate-500">${esc(o.salario || '—')}</td>
            <td class="py-4 px-6 text-sm text-slate-500">${esc(o.num_vacante)}</td>
            <td class="py-4 px-6">
                <div class="flex gap-2">
                    <button onclick="cargarParaEditar(${o.cod_oferta})"
                        class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-primary border border-primary rounded hover:bg-secondary-container transition-colors">
                        <span class="material-symbols-outlined text-base">edit</span> Editar
                    </button>
                    <button onclick="pedirConfirmacion(${o.cod_oferta})"
                        class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-error border border-error rounded hover:bg-error-container transition-colors">
                        <span class="material-symbols-outlined text-base">delete</span> Eliminar
                    </button>
                </div>
            </td>`;
        tbody.appendChild(tr);
    });
}

async function manejarEnvio(e) {
    e.preventDefault();
    const cargo = document.getElementById('cargo').value.trim();
    const nit   = document.getElementById('nit_empresa').value.trim();
    if (!cargo) { mostrarToast('El campo Cargo es obligatorio.', 'error'); return; }
    if (!nit)   { mostrarToast('No se encontró el NIT de la empresa.', 'error'); return; }

    const btn = document.getElementById('btn-submit');
    activarSpinner(btn, true);

    try {
        const body = new URLSearchParams({
            accion:               editandoId ? 'actualizar' : 'crear',
            cargo,
            perfil:               document.getElementById('perfil').value.trim(),
            salario:              document.getElementById('salario').value.trim(),
            requerimientos:       document.getElementById('requerimientos').value.trim(),
            experiencia:          document.getElementById('experiencia').value.trim(),
            num_vacante:          document.getElementById('num_vacante').value.trim() || '1',
            horario:              document.getElementById('horario').value.trim(),
            duracion:             document.getElementById('duracion').value.trim(),
            nit_empresa:          nit,
            cod_nivel_edu:        document.getElementById('cod_nivel_edu').value,
            cod_idioma:           document.getElementById('cod_idioma').value,
            cod_ciudad:           document.getElementById('cod_ciudad').value,
            cod_discapacidad:     document.getElementById('cod_discapacidad').value,
            ...(editandoId ? { cod_oferta: editandoId } : {})
        });

        const res  = await fetch(API, { method: 'POST', body });
        const data = await res.json();
        if (!data.ok) throw new Error(data.mensaje);

        mostrarToast(editandoId ? 'Oferta actualizada.' : 'Oferta publicada correctamente.', 'exito');
        cancelarEdicion();
        cargarOfertas();
    } catch (e) {
        mostrarToast('Error: ' + e.message, 'error');
    } finally {
        activarSpinner(btn, false);
    }
}

async function cargarParaEditar(id) {
    try {
        const res  = await fetch(`${API}?accion=obtener&cod_oferta=${id}`);
        const data = await res.json();
        if (!data.ok) throw new Error(data.mensaje);
        const o = data.oferta;
        editandoId = id;
        document.getElementById('cargo').value            = o.nom_puesto_trabajo || '';
        document.getElementById('perfil').value           = o.descripcion || '';
        document.getElementById('salario').value          = o.salario || '';
        document.getElementById('requerimientos').value   = o.requisitos || '';
        document.getElementById('experiencia').value      = o.experiencia || '';
        document.getElementById('num_vacante').value      = o.num_vacantes || '';
        document.getElementById('horario').value          = o.horario || '';
        document.getElementById('duracion').value         = o.duracion || '';
        document.getElementById('nit_empresa').value      = o.cod_empresa || '';
        document.getElementById('cod_discapacidad').value = o.cod_discapacidad || '';
        document.getElementById('cod_nivel_edu').value    = o.cod_nivel || '';
        document.getElementById('cod_idioma').value       = o.cod_idioma || '';
        document.getElementById('cod_ciudad').value       = o.cod_ciudad || '';
        mostrarFormulario(true);
        document.getElementById('form-container').scrollIntoView({ behavior: 'smooth' });
    } catch (e) {
        mostrarToast('Error al cargar oferta: ' + e.message, 'error');
    }
}

let idEliminar = null;
function pedirConfirmacion(id) { idEliminar = id; document.getElementById('modal-backdrop').classList.remove('hidden'); }
function cerrarModal() { idEliminar = null; document.getElementById('modal-backdrop').classList.add('hidden'); }

async function confirmarEliminar() {
    cerrarModal();
    if (!idEliminar) return;
    try {
        const res  = await fetch(API, { method: 'POST', body: new URLSearchParams({ accion: 'eliminar', cod_oferta: idEliminar }) });
        const data = await res.json();
        if (!data.ok) throw new Error(data.mensaje);
        mostrarToast('Oferta eliminada.', 'exito');
        cargarOfertas();
    } catch (e) {
        mostrarToast('Error al eliminar: ' + e.message, 'error');
    }
}

function cancelarEdicion() {
    const nit = document.getElementById('nit_empresa').value;
    editandoId = null;
    document.getElementById('form-oferta').reset();
    document.getElementById('nit_empresa').value = nit;
    mostrarFormulario(false);
}

function mostrarFormulario(esEdicion) {
    document.getElementById('form-container').classList.remove('hidden');
    document.getElementById('form-titulo').textContent = esEdicion ? 'Editar Oferta de Trabajo' : 'Registrar Oferta de Trabajo';
    document.getElementById('form-subtitulo').textContent = esEdicion
        ? 'Modifica los datos de la oferta seleccionada.'
        : 'Complete los datos para publicar una nueva vacante en el sistema del observatorio.';
    document.getElementById('btn-submit').querySelector('span:last-child').textContent = esEdicion ? 'Actualizar Oferta' : 'Publicar Oferta';
    document.getElementById('btn-cancelar').classList.toggle('hidden', !esEdicion);
}

function mostrarCargaTabla(loading) { document.getElementById('tabla-spinner')?.classList.toggle('hidden', !loading); }

function activarSpinner(btn, activo) {
    btn.querySelector('.spinner')?.classList.toggle('hidden', !activo);
    btn.disabled = activo;
}

function mostrarToast(mensaje, tipo = 'exito') {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toast-icon');
    document.getElementById('toast-msg').textContent = mensaje;
    toast.className = toast.className.replace(/bg-\S+/, tipo === 'error' ? 'bg-red-600' : 'bg-primary');
    icon.textContent = tipo === 'error' ? 'error' : 'check_circle';
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3500);
}

function esc(str) {
    if (str == null) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>

</body>
</html>
