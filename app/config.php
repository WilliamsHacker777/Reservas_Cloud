<?php
// config.php - Archivo de configuración (NO SUBIR A PRODUCCIÓN EN REPO PÚBLICO)
// Para InfinityFree: edita este archivo directamente en el panel o vía FTP

// ========== BASE DE DATOS ==========
define('DB_HOST', getenv('DB_HOST') ?: 'MYSQL9001.site4now.net'); // Cambiar por host de InfinityFree
define('DB_NAME', getenv('DB_NAME') ?: 'db_ac1074_sistema');      // Cambiar por DB name
define('DB_USER', getenv('DB_USER') ?: 'ac1074_sistema');         // Cambiar por usuario
define('DB_PASS', getenv('DB_PASS') ?: 'SNQqXqxx62X-mEJ');        // Cambiar por contraseña

// ========== RENIEC API ==========
define('RENIEC_TOKEN', getenv('RENIEC_TOKEN') ?: 'apis-token-11088.4YrFYiVdQdkaLfvTgZpsp9DMEE4CNrIo');

// ========== YAPE CONFIG ==========
define('YAPE_PHONE', getenv('YAPE_PHONE') ?: '959 467 673'); // Número Yape para QR

// ========== APP CONFIG ==========
// When using the PHP built-in server with `-t public` the webroot is `public/`.
// Default to localhost:9090 which is what you used; override with env APP_URL if needed.
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:9090/');
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/payments/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
