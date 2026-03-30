<?php
// ============================================================
//  Formulario Registro Egresado — selects desde bolsa_empleo
// ============================================================
require_once '../conexion.php';
$pdo = conectar();

$ciudades  = $pdo->query("SELECT cod_ciudad, nom_ciudad FROM ciudad ORDER BY nom_ciudad")->fetchAll();
$niveles   = $pdo->query("SELECT cod_nivel_educativo, nom_nivel_educativo FROM nivel_educativo")->fetchAll();
$estados_p = $pdo->query("SELECT cod_estado_prof, nom_estado_prof FROM estado_profesional")->fetchAll();
$discapac  = $pdo->query("SELECT cod_discapacidad, nom_discapacidad FROM discapacidad")->fetchAll();

$success = isset($_GET['success']);
$error   = $_GET['error'] ?? '';
$errMsg  = [
    'campos_requeridos' => 'Por favor complete todos los campos obligatorios.',
    'email_invalido'    => 'El correo electrónico no es válido.',
    'ya_registrado'     => 'Ya existe un egresado con ese número de identificación.',
    'usuario_existente' => 'El nombre de usuario ya está en uso.',
    'bd'                => 'Error de base de datos: ' . htmlspecialchars($_GET['msg'] ?? ''),
];
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registrar Egresado - Observatorio Laboral</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

<nav class="fixed top-0 z-50 flex justify-between items-center w-full px-12 h-16 bg-white border-b border-slate-200 text-sm">
    <div class="text-xl font-bold tracking-tighter text-green-800">Observatorio Laboral</div>
</nav>

<aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 w-64 border-r border-slate-200 bg-slate-100 text-sm font-medium z-40">
    <div class="px-6 mb-8">
        <h2 class="font-bold text-lg">Observatorio Laboral</h2>
        <p class="text-slate-500 text-xs">Registro Egresados</p>
    </div>
    <nav class="flex-1 space-y-1 pr-4">
        <a class="flex items-center px-6 py-3 text-green-700 bg-white rounded-r-lg shadow-sm font-bold" href="registrar.php"><span class="material-symbols-outlined mr-3">school</span> Registrar Egresado</a>
    </nav>
    <div class="mt-auto pt-6 border-t border-stone-200 dark:border-stone-800">
            <div class="p-4 rounded-xl bg-primary-fixed/30 dark:bg-primary-container/20">
                <a class="flex items-center px-4 py-3 text-zinc-500 hover:bg-emerald-50/50 hover:text-emerald-700 transition-all duration-300 font-medium text-sm rounded-lg" href="../index.html">
                <span class="material-symbols-outlined mr-3">logout</span> Cerrar Sesión
                </a>
            </div>
    </div>
</aside>

<main class="ml-64 mt-16 min-h-screen p-12">
    <div class="max-w-3xl mx-auto">
        <header class="mb-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-800 mb-2">Registrar Egresado</h1>
            <p class="text-slate-500">Complete el formulario para agregar un nuevo egresado a la base de datos.</p>
        </header>

        <?php if ($success): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-6 py-4 mb-6 flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span><strong>¡Registro exitoso!</strong> El egresado <strong><?= htmlspecialchars($_GET['nombre'] ?? '') ?></strong> fue guardado correctamente.</span>
        </div>
        <?php elseif ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-6 py-4 mb-6 flex items-center gap-3">
            <span class="material-symbols-outlined text-red-600">error</span>
            <span><?= $errMsg[$error] ?? 'Ocurrió un error al procesar el formulario.' ?></span>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl border border-slate-200 p-10">
            <form action="registro.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                <div class="col-span-full mb-2">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2"><span class="material-symbols-outlined text-green-700">person</span> Información Personal</h3>
                    <div class="h-px bg-slate-100 mt-2"></div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Nombre <span class="text-red-500">*</span></label>
                    <input name="nombre" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" type="text" placeholder="Ingrese el nombre" required/>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Apellido <span class="text-red-500">*</span></label>
                    <input name="apellido" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" type="text" placeholder="Ingrese el apellido" required/>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">N° de Identificación <span class="text-red-500">*</span></label>
                    <input name="id_number" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" type="text" placeholder="Documento de identidad" required/>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Teléfono</label>
                    <input name="phone" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" type="tel" placeholder="+57 300 000 0000"/>
                </div>

                <div class="col-span-full space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Correo Electrónico <span class="text-red-500">*</span></label>
                    <input name="email" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" type="email" placeholder="correo@ejemplo.com" required/>
                </div>

                <div class="col-span-full mt-4 mb-2">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2"><span class="material-symbols-outlined text-green-700">location_on</span> Ubicación y Perfil</h3>
                    <div class="h-px bg-slate-100 mt-2"></div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Ciudad</label>
                    <select name="city" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Seleccionar ciudad</option>
                        <?php foreach ($ciudades as $c): ?>
                        <option value="<?= $c['cod_ciudad'] ?>"><?= htmlspecialchars($c['nom_ciudad']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Nivel Educativo</label>
                    <select name="nivel_edu" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Seleccionar nivel</option>
                        <?php foreach ($niveles as $n): ?>
                        <option value="<?= $n['cod_nivel_educativo'] ?>"><?= htmlspecialchars($n['nom_nivel_educativo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Estado Profesional</label>
                    <select name="estado_prof" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Seleccionar estado</option>
                        <?php foreach ($estados_p as $ep): ?>
                        <option value="<?= $ep['cod_estado_prof'] ?>"><?= htmlspecialchars($ep['nom_estado_prof']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Discapacidad</label>
                    <select name="discapacidad" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Seleccionar</option>
                        <?php foreach ($discapac as $d): ?>
                        <option value="<?= $d['cod_discapacidad'] ?>"><?= htmlspecialchars($d['nom_discapacidad']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-span-full mt-4 mb-2">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2"><span class="material-symbols-outlined text-green-700">lock</span> Credenciales de Acceso</h3>
                    <div class="h-px bg-slate-100 mt-2"></div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Usuario <span class="text-red-500">*</span></label>
                    <input name="username" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" type="text" placeholder="Elija un nombre de usuario" required/>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Contraseña <span class="text-red-500">*</span></label>
                    <input name="password" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" type="password" placeholder="••••••••" required/>
                </div>

                <div class="col-span-full mt-8">
                    <button type="submit" class="w-full py-4 bg-green-700 text-white font-bold rounded-xl hover:bg-green-800 transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">save</span> Guardar Egresado
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-3">Los campos marcados con * son obligatorios.</p>
                </div>
            </form>
        </div>
    </div>
</main>
</body>
</html>
