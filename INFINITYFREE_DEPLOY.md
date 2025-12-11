# Guía de Despliegue en InfinityFree

## Requisitos previos
- Cuenta gratuita en InfinityFree (www.infinityfree.net)
- Acceso FTP o File Manager del panel
- Base de datos MySQL (incluida en la cuenta)

## Pasos para desplegar

### 1. Crear subdominios y base de datos en InfinityFree
1. Accede a tu panel de InfinityFree.
2. Ve a "Bases de datos MySQL" y crea una nueva:
   - Nombre de base de datos
   - Nombre de usuario MySQL
   - Contraseña
   - Anota estos datos (IMPORTANTE)
3. (Opcional) Crea un subdominio si quieres (ej: reservas.tudominio.infinityfree.app)

### 2. Preparar la base de datos
1. Accede a phpMyAdmin desde tu panel de InfinityFree.
2. Importa tu archivo SQL (con las tablas: usuarios, habitaciones, personas, reservas).
   - Script esperado: tabla `reservas` debe tener columnas: `payment_method`, `payment_status`, `payment_receipt` (ya hecho en ALTER).
3. Verifica que todas las tablas y columnas existan.

### 3. Descargar y preparar archivos locales
1. En tu proyecto local (c:\wamp64\www\Reservas_Cloud\), verifica que tenga:
   - Directorio `.htaccess` en la raíz del proyecto (ya creado).
   - Archivo `public/index.php` (ya adaptado).
   - Archivo `public/consulta_dni.php` (ya existe).
   - Carpeta `app/`, `views/`, `public/img/` (con imágenes).
   - Carpeta `public/uploads/payments/` (será creada al subir).

### 4. Subir archivos via FTP (File Manager)
1. Conecta por FTP a InfinityFree (obtén credenciales del panel).
   - Herramienta recomendada: FileZilla (gratis).
2. Navega al directorio raíz asignado (ej: `htdocs` o `public_html`).
3. Sube TODOS los archivos del proyecto, excepto:
   - Carpeta `node_modules/` (si existe).
   - Archivo `.env` (si existe, manejo manual).
   - Carpeta `.git/` (control de versiones, no necesaria).
4. Estructura esperada en el servidor:
   ```
   /htdocs (o root asignado)
   ├── .htaccess          (archivo creado)
   ├── app/
   ├── views/
   ├── public/
   │   ├── index.php
   │   ├── consulta_dni.php
   │   ├── img/
   │   └── uploads/payments/ (crear si no existe)
   ├── index.php (del proyecto raíz, si aplica)
   └── (otros archivos)
   ```

### 5. Actualizar credenciales de base de datos
1. Accede a través del File Manager de InfinityFree.
2. Edita el archivo: `app/core/database.php`
3. Reemplaza:
   ```php
   $host = "tu_host_mysql_infinityfree";   // dato obtenido en paso 2
   $db   = "tu_bd_infinityfree";            // nombre de BD
   $user = "tu_usuario_mysql";              // usuario de BD
   $pass = "tu_contraseña_mysql";           // contraseña de BD
   ```
   - El host suele ser algo como: `sql000.infinityfree.com` (varía por servidor).
4. Guarda los cambios.

### 6. Crear carpeta de uploads
1. Crea la carpeta `public/uploads/payments/` en el servidor (si no existe).
2. Asigna permisos de escritura (CHMOD 755).
   - Si usas File Manager de InfinityFree: haz clic derecho en la carpeta → Propiedades → Permisos → 755.

### 7. Configurar .htaccess (ya incluido)
- El archivo `.htaccess` ya está incluido en la raíz del proyecto.
- Verifica que esté subido y que `mod_rewrite` esté habilitado (normalmente lo está en InfinityFree).
- Este archivo redirige todas las peticiones a `public/index.php`.

### 8. Probar el sitio
1. Abre tu navegador y ve a: `https://tudominio.infinityfree.app`
2. Deberías ver la página de inicio del proyecto.
3. Prueba:
   - Registrarse (`?action=register`)
   - Iniciar sesión (`?action=login`)
   - Ver habitaciones (home)
   - Seleccionar una habitación y hacer clic en "Reservar"
   - Completar el formulario de pago con un DNI ficticio
   - Verificar que se guarde la reserva en la BD

### 9. Ajustes finales (recomendados para producción)
- **Token RENIEC**: El token actualmente está en `public/consulta_dni.php`. En producción, es mejor moverlo a una variable de entorno o archivo no accesible públicamente.
  - Alternativa: crear un archivo `config.php` fuera del webroot (si es posible en InfinityFree) o usar variables del panel.
- **Uploads**: configura límite de tamaño en `php.ini` (si tienes acceso) o manualmente en `reservaController.php`.
- **HTTPS**: InfinityFree proporciona HTTPS gratuito; verifica que esté habilitado.

## Solución de problemas comunes en InfinityFree

**Problema**: "404 Not Found" en páginas internas
- **Solución**: Verifica que `.htaccess` esté en la raíz y que `mod_rewrite` esté activo.

**Problema**: "Fatal error: Class 'Database' not found" o errores de rutas
- **Solución**: Comprueba que `app/core/database.php` tenga las rutas relativas correctas (`__DIR__ . '/../...'`).

**Problema**: "Cannot connect to database"
- **Solución**: Verifica credenciales en `database.php`. Usa el host exacto proporcionado por InfinityFree (no localhost).

**Problema**: "Permission denied" en carpeta `uploads`
- **Solución**: Asigna permisos 755 o 777 a la carpeta `public/uploads/payments/`.

**Problema**: Imágenes no cargan en `public/img/`
- **Solución**: Verifica que la carpeta esté subida correctamente. URL debe ser `/img/nombreimagen.jpg` (relativa a root).

**Problema**: La consulta a RENIEC no funciona
- **Solución**: 
  - Verifica que `public/consulta_dni.php` está subido.
  - Comprueba el token en ese archivo (debe ser válido).
  - Asegúrate que el servidor tiene acceso a internet (normalmente sí en InfinityFree).

## Resumen de cambios hechos al código para InfinityFree
- ✅ URLs cambiadas de `/Reservas_Cloud/public/?action=...` a `/?action=...`
- ✅ Archivo `.htaccess` en raíz del proyecto para redireccionar a `public/index.php`
- ✅ Rutas relativas confirmadas en `public/index.php` y demás archivos.

¡Tu proyecto debe funcionar correctamente en InfinityFree tras seguir estos pasos!
