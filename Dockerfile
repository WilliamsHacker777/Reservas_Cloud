# Usa una imagen base oficial de PHP con Apache
FROM php:8.1-apache

# Establece el directorio de trabajo donde estará la aplicación
WORKDIR /var/www/html

# Copia los archivos de tu proyecto al directorio de trabajo
COPY . /var/www/html/

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# 🚨 INSTRUCCIONES CLAVE PARA CORREGIR LA RUTA 🚨
# 1. Cambia el DocumentRoot de /var/www/html a /var/www/html/public
RUN sed -i -e 's/html/html\/public/g' /etc/apache2/sites-available/000-default.conf

# 2. Asegura que el .htaccess funcione globalmente
RUN sed -i -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Expone el puerto por defecto de Apache
EXPOSE 80

# Comando por defecto para iniciar Apache
CMD ["apache2-foreground"]