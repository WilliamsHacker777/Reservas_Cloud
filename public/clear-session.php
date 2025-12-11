<?php
// Limpiar sesión corrupta
session_start();
session_unset();
session_destroy();

// Reiniciar sesión limpia
session_start();

echo "Sesión limpiada. Redirigiendo...";
header("Location: /?action=login");
exit;
?>
