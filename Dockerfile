# Usa una imagen base oficial de PHP con Apache
FROM php:8.1-apache

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos de tu proyecto
COPY . /var/www/html/

# 🚨 NUEVA INSTRUCCIÓN CLAVE: Copia el archivo de configuración modificado
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# El DocumentRoot ya está configurado en 000-default.conf, 
# por lo que no se necesitan los comandos RUN sed...

# Comando por defecto para iniciar Apache
CMD ["apache2-foreground"]