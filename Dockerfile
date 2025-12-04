# Usa una imagen base oficial de PHP con Apache
FROM php:8.1-apache

# Establece el directorio de trabajo donde estará la aplicación
WORKDIR /var/www/html

# Copia los archivos de tu proyecto al directorio de trabajo del contenedor
COPY . /var/www/html/

# Si usas Composer, descomenta las siguientes líneas
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# RUN composer install --no-dev --optimize-autoloader

# Habilita el módulo de reescritura de Apache (necesario para URLs limpias con .htaccess)
RUN a2enmod rewrite

# Si tu carpeta pública es la raíz, usa el comando por defecto.
# Si tienes una carpeta 'public/', necesitas reconfigurar Apache, 
# pero la forma más sencilla es manejarlo con el .htaccess y el Start Command en Render.

# Expone el puerto por defecto de Apache
EXPOSE 80

# El comando por defecto para iniciar Apache (Render lo ejecuta automáticamente)
CMD ["apache2-foreground"]