<?php
/**
 * recuperar_password.php
 * Permite al usuario solicitar restablecimiento de contraseña.
 * Busca el email en la tabla participante vinculada al usuario,
 * genera un token temporal y lo guarda en sesión.
 * (Para envío de email real, integrar PHPMailer)
 */

require_once 'conexion.php';

$mensaje     = '';
$tipo        = '';   // 'exito' | 'error'
$procesado   = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'Por favor ingresa un correo electrónico válido.';
        $tipo    = 'error';
    } else {
        try {
            $pdo = conectar();

            // Buscamos el participante con ese email que tenga usuario activo
            $stmt = $pdo->prepare("
                SELECT u.cod_usuario, u.num_usuario,
                       p.nombre, p.apellido, p.email
                FROM usuario u
                JOIN participante p ON p.num_ident = u.Participante_num_ident
                WHERE p.email = :email
                LIMIT 1
            ");
            $stmt->execute([':email' => $email]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                // Generar token seguro
                $token    = bin2hex(random_bytes(32));
                $expira   = date('Y-m-d H:i:s', time() + 3600); // 1 hora

                // Guardar token en sesión (en producción guardar en BD con tabla tokens)
                session_start();
                $_SESSION['reset_token']    = $token;
                $_SESSION['reset_usuario']  = $fila['cod_usuario'];
                $_SESSION['reset_expira']   = $expira;
                $_SESSION['reset_email']    = $email;

                /*
                 * ── PARA ENVÍO REAL DE EMAIL ──────────────────────────────
                 * Instala PHPMailer: composer require phpmailer/phpmailer
                 * Luego descomenta y configura:
                 *
                 * use PHPMailer\PHPMailer\PHPMailer;
                 * $mail = new PHPMailer(true);
                 * $mail->isSMTP();
                 * $mail->Host       = 'smtp.tuservidor.com';
                 * $mail->SMTPAuth   = true;
                 * $mail->Username   = 'tu@correo.com';
                 * $mail->Password   = 'tu_contraseña';
                 * $mail->SMTPSecure = 'tls';
                 * $mail->Port       = 587;
                 * $mail->setFrom('no-reply@observatorio.edu.co', 'Observatorio Laboral');
                 * $mail->addAddress($email, $fila['nombre']);
                 * $mail->Subject = 'Recuperación de contraseña';
                 * $enlace = 'http://'.$_SERVER['HTTP_HOST'].'/nueva_password.php?token='.$token;
                 * $mail->Body = "Hola {$fila['nombre']},\n\nHaz clic en el siguiente enlace para restablecer tu contraseña:\n$enlace\n\nEste enlace expira en 1 hora.";
                 * $mail->send();
                 * ─────────────────────────────────────────────────────────
                 */
            }

            // Por seguridad mostramos el mismo mensaje aunque el email no exista
            $mensaje   = 'Si el correo está registrado, recibirás un enlace de recuperación en breve. Revisa tu bandeja de entrada y carpeta de spam.';
            $tipo      = 'exito';
            $procesado = true;

        } catch (Throwable $e) {
            $mensaje = 'Ocurrió un error al procesar tu solicitud. Intenta de nuevo más tarde.';
            $tipo    = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es" class="light">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Recuperar Contraseña | Observatorio Laboral</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="estilos.css"/>
    <style>
        body { font-family: 'Public Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex flex-col items-center justify-center px-4">

    <!-- Logo / Encabezado -->
    <div class="mb-8 text-center">
        <a href="index.html" class="inline-flex items-center gap-2 text-green-800 hover:text-green-600 transition-colors mb-4">
            <span class="material-symbols-outlined">arrow_back</span>
            <span class="text-sm font-medium">Volver al inicio de sesión</span>
        </a>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Observatorio Laboral</h1>
        <p class="text-slate-500 text-sm mt-1">Recuperación de contraseña</p>
    </div>

    <!-- Tarjeta -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 border border-slate-100">

        <?php if ($procesado): ?>
            <!-- Estado: Email enviado -->
            <div class="text-center py-4">
                <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-green-600 text-3xl">mark_email_read</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-2">Revisa tu correo</h2>
                <p class="text-slate-500 text-sm leading-relaxed">
                    <?= htmlspecialchars($mensaje) ?>
                </p>
                <a href="index.html"
                   class="mt-6 inline-block w-full text-center bg-green-700 text-white py-3 px-6 rounded-xl font-semibold hover:bg-green-800 transition-colors">
                    Volver al inicio de sesión
                </a>
            </div>

        <?php else: ?>
            <div class="mb-6">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-green-700">lock_reset</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900">¿Olvidaste tu contraseña?</h2>
                <p class="text-slate-500 text-sm mt-1 leading-relaxed">
                    Ingresa el correo electrónico asociado a tu cuenta y te enviaremos un enlace para restablecerla.
                </p>
            </div>

            <?php if ($mensaje !== ''): ?>
                <div class="mb-4 p-4 rounded-lg flex items-start gap-3
                    <?= $tipo === 'error' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-green-50 text-green-700 border border-green-200' ?>">
                    <span class="material-symbols-outlined text-base mt-0.5">
                        <?= $tipo === 'error' ? 'error' : 'check_circle' ?>
                    </span>
                    <span class="text-sm"><?= htmlspecialchars($mensaje) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="recuperar_password.php" novalidate class="space-y-5">

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <span class="material-symbols-outlined text-xl">mail</span>
                        </span>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            autocomplete="email"
                            placeholder="tucorreo@ejemplo.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                        />
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-green-700 hover:bg-green-800 active:scale-95 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200">
                    <span class="material-symbols-outlined text-lg">send</span>
                    Enviar enlace de recuperación
                </button>

            </form>

            <p class="text-center text-xs text-slate-400 mt-6">
                ¿Recordaste tu contraseña?
                <a href="index.html" class="text-green-700 font-semibold hover:underline">Inicia sesión</a>
            </p>
        <?php endif; ?>
    </div>

    <p class="text-xs text-slate-400 mt-6">© <?= date('Y') ?> Observatorio Laboral. Todos los derechos reservados.</p>

</body>
</html>
