<?php
session_start();

echo "<h1>Debug de Sesión</h1>";
echo "<pre>";
echo "SESSION contents:\n";
var_dump($_SESSION);
echo "\n\nCookie PHPSESSID: " . ($_COOKIE['PHPSESSID'] ?? 'NO SET') . "\n";
echo "Session ID: " . session_id() . "\n";
echo "Session Status: " . session_status() . "\n";
echo "</pre>";

echo "<hr>";
echo "<a href='/?action=login'>Volver a Login</a>";
?>
