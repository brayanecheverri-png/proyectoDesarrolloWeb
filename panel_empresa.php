<?php
/* =============================================
   panel_empresa.php — Portal Empresa
   Solo accesible si tipo_usuario === 'empresa'
   ============================================= */
session_start();

if (empty($_SESSION['tipo_usuario'])) {
    header('Location: index.html');
    exit;
}
if ($_SESSION['tipo_usuario'] !== 'empresa') {
    header('Location: panel_egresado.php');
    exit;
}

$nombre      = htmlspecialchars($_SESSION['nombre'] ?? 'Empresa');
$cod_empresa = $_SESSION['cod_empresa'] ?? '';
?>
<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Panel Empresa | Observatorio Laboral</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>body{font-family:'Public Sans',sans-serif;}</style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">

<!-- NAV -->
<nav class="bg-white flex justify-between items-center w-full px-10 h-16 fixed top-0 z-50 border-b border-slate-100 shadow-sm">
    <div class="text-xl font-bold tracking-tighter text-green-800">Observatorio Laboral</div>
    <div class="hidden md:flex items-center gap-3 text-sm font-medium">
        <span class="text-slate-500">Hola, <span class="font-semibold text-green-700"><?= $nombre ?></span></span>
        <span class="text-slate-300">|</span>
        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-bold uppercase tracking-wide">Empresa</span>
    </div>
    <div class="flex items-center gap-3 text-green-700">
        <a href="logout.php" title="Cerrar sesión">
            <button class="material-symbols-outlined hover:bg-slate-100 transition-all p-2 rounded-full">logout</button>
        </a>
    </div>
</nav>

<!-- SIDEBAR -->
<aside class="fixed left-0 top-16 bottom-0 flex flex-col py-6 bg-white h-screen w-64 border-r border-slate-100 hidden lg:flex">
    <div class="px-6 mb-6">
        <h2 class="text-slate-800 font-bold text-lg">Gestión Empresa</h2>
        <p class="text-slate-400 text-xs uppercase tracking-widest">Portal Empresarial</p>
    </div>
    <nav class="flex flex-col gap-1 pr-4 text-sm font-medium">
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="oferta/registrar.php">
            <span class="material-symbols-outlined">school</span> Publicar Oferta Empleo
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="directorioEgresado/directorio.php">
            <span class="material-symbols-outlined">school</span> Directorio Egresados
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="verOfertaEmpleo/ver.php">
            <span class="material-symbols-outlined">description</span> Mis Postulaciones
        </a>
        <a class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:bg-slate-100 hover:pl-8 transition-all rounded-r-lg"
           href="reportes/reportes.php">
            <span class="material-symbols-outlined">description</span> Reportes
        </a>
    </nav>
</aside>

<!-- MAIN -->
<main class="lg:ml-64 pt-24 px-8 pb-12 flex-1">
    <div class="mb-8">
        <p class="text-slate-400 text-xs uppercase tracking-widest font-bold">Panel Principal</p>
        <h1 class="text-3xl font-black text-slate-900 mt-1">Bienvenido, <?= $nombre ?> 👋</h1>
        <p class="text-slate-500 mt-2 text-sm">Gestiona los candidatos que se han postulado a tus ofertas de empleo.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl">

        <!-- Directorio Egresados -->
        <a href="directorioEgresado/directorio.php"
           class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md hover:border-green-200 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center mb-4 group-hover:bg-green-200 transition-colors">
                <span class="material-symbols-outlined text-green-700 text-2xl" style="font-variation-settings:'FILL' 1">school</span>
            </div>
            <h2 class="text-lg font-bold text-slate-900 mb-1">Directorio de Egresados</h2>
            <p class="text-sm text-slate-500">Consulta el perfil de egresados y estudiantes registrados en el Observatorio.</p>
            <div class="flex items-center gap-1 mt-4 text-green-700 text-xs font-bold uppercase tracking-wide">
                <span>Ver egresados</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
        </a>

        <!-- Mis Postulaciones -->
        <a href="verOfertaEmpleo/ver.php"
           class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
                <span class="material-symbols-outlined text-blue-700 text-2xl" style="font-variation-settings:'FILL' 1">description</span>
            </div>
            <h2 class="text-lg font-bold text-slate-900 mb-1">Postulaciones a mis Ofertas</h2>
            <p class="text-sm text-slate-500">Revisa quiénes se han postulado a las ofertas publicadas por tu empresa.</p>
            <div class="flex items-center gap-1 mt-4 text-blue-700 text-xs font-bold uppercase tracking-wide">
                <span>Ver postulaciones</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </div>
        </a>

    </div>
</main>

<footer class="lg:ml-64 bg-white px-10 py-6 border-t border-slate-100 flex justify-between items-center">
    <span class="text-xs font-black text-slate-600 uppercase">Observatorio Laboral</span>
    <span class="text-slate-400 text-xs">© <?= date('Y') ?> Observatorio Laboral</span>
</footer>

</body>
</html>
