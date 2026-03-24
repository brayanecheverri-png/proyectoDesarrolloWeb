<?php
/**
 * ============================================================
 *  Conexión a Base de Datos
 *  Archivo: conexion.php
 * ============================================================
 *  Configuración centralizada para la conexión a la BD
 *  Utiliza PDO para consultas seguras
 */

// ---------- Configuración de BD ----------
define('DB_HOST', 'localhost');
define('DB_NAME', 'observatorio_laboral');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Conectar a la base de datos
 * @return PDO Objeto de conexión PDO
 */
function conectar(): PDO {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
    
    return $pdo;
}

/**
 * Responder con JSON
 * @param bool $ok Estado de la operación
 * @param string $mensaje Mensaje de respuesta
 * @param array $extra Datos adicionales
 */
function responder(bool $ok, string $mensaje = '', array $extra = []): void {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge(['ok' => $ok, 'mensaje' => $mensaje], $extra));
    exit;
}

/**
 * Escapar HTML para evitar XSS
 * @param string $string Texto a escapar
 * @return string Texto escapado
 */
function escaparHTML(string $string): string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Validar entrada de datos
 * @param mixed $dato Dato a validar
 * @param string $tipo Tipo de validación (int, email, string)
 * @return mixed Dato validado o false
 */
function validar($dato, string $tipo = 'string') {
    switch ($tipo) {
        case 'int':
            return filter_var($dato, FILTER_VALIDATE_INT);
        case 'email':
            return filter_var($dato, FILTER_VALIDATE_EMAIL);
        case 'string':
            return is_string($dato) && !empty(trim($dato)) ? trim($dato) : false;
        default:
            return false;
    }
}
