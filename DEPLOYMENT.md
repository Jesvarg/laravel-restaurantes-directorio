# Guía de Despliegue en Servidor Ubuntu (EC2)

Este documento detalla los pasos para desplegar la aplicación Laravel en un servidor Ubuntu, como una instancia EC2 de AWS.

## 1. Instalación del Stack (LAMP + Node.js)

Primero, actualiza los repositorios del sistema e instala Apache, MySQL, PHP, Node.js y otras herramientas necesarias.

```bash
# Actualizar el índice de paquetes
sudo apt-get update

# Instalar Apache, MySQL y Git
sudo apt-get install -y apache2 mysql-server git

# Instalar PHP y las extensiones comunes para Laravel
sudo apt-get install -y php php-cli php-mysql php-mbstring php-xml php-curl php-zip php-bcmath

# Instalar Node.js y npm (manejador de paquetes de Node)
sudo apt-get install -y nodejs npm

# Instalar Composer (manejador de dependencias de PHP)
sudo apt-get install -y composer
```

## 2. Configuración del Virtual Host de Apache

Crea un archivo de configuración de Virtual Host para que Apache sepa cómo servir tu aplicación.

1.  **Crea el archivo de configuración**:

    ```bash
    sudo nano /etc/apache2/sites-available/laravel-restaurantes.conf
    ```

2.  **Añade el siguiente contenido**. Reemplaza `tu-dominio.com` con tu dominio real o la IP pública de tu EC2. El `DocumentRoot` debe apuntar a la carpeta `public` de tu proyecto.

    ```apache
    <VirtualHost *:80>
        ServerName tu-dominio.com
        DocumentRoot /var/www/laravel-restaurantes/public

        <Directory /var/www/laravel-restaurantes>
            AllowOverride All
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>
    ```

3.  **Activa el nuevo sitio y el módulo `rewrite` de Apache**:

    ```bash
    # Habilitar el sitio
    sudo a2ensite laravel-restaurantes.conf

    # Habilitar el módulo de reescritura de URL
    sudo a2enmod rewrite

    # Deshabilitar el sitio por defecto
    sudo a2dissite 000-default.conf
    ```

4.  **Recarga Apache para aplicar los cambios**:

    ```bash
    sudo systemctl reload apache2
    ```

## 3. Despliegue del Código

Ahora, clona el código de tu repositorio y configúralo en el servidor.

1.  **Crea el directorio y clona el proyecto**:

    ```bash
    # Crea el directorio para tu proyecto
    sudo mkdir -p /var/www/laravel-restaurantes

    # Clona tu repositorio (reemplaza <URL_DEL_REPOSITORIO>)
    sudo git clone <URL_DEL_REPOSITORIO> /var/www/laravel-restaurantes
    ```

2.  **Establece los permisos correctos**. El servidor web (Apache) necesita permisos de escritura en las carpetas `storage` y `bootstrap/cache`.

    ```bash
    # Asigna la propiedad de los archivos al usuario de Apache
    sudo chown -R www-data:www-data /var/www/laravel-restaurantes

    # Otorga permisos de escritura al grupo en las carpetas necesarias
    sudo chmod -R 775 /var/www/laravel-restaurantes/storage
    sudo chmod -R 775 /var/www/laravel-restaurantes/bootstrap/cache
    ```

3.  **Instala las dependencias y configura el entorno**:

    ```bash
    # Navega al directorio del proyecto
    cd /var/www/laravel-restaurantes

    # Instala las dependencias de PHP (sin las de desarrollo)
    sudo composer install --optimize-autoloader --no-dev

    # Instala las dependencias de Node.js y compila los assets
    sudo npm install
    sudo npm run build

    # Crea el archivo de entorno a partir del ejemplo
    sudo cp .env.example .env

    # Genera la clave de la aplicación
    sudo php artisan key:generate
    ```

4.  **Configura el archivo `.env`**. Edítalo para configurar la base de datos, la URL de la aplicación y otras variables de entorno.

    ```bash
    sudo nano .env
    ```

    Asegúrate de configurar al menos estas variables:

    ```ini
    APP_NAME=Laravel
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=http://tu-dominio.com

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nombre_de_tu_bd
    DB_USERNAME=tu_usuario_bd
    DB_PASSWORD=tu_contraseña_bd
    ```

5.  **Ejecuta las migraciones y crea el enlace simbólico de almacenamiento**:

    ```bash
    # Ejecuta las migraciones para crear las tablas en la base de datos
    sudo php artisan migrate --force  # --force es necesario en producción

    # Crea el enlace simbólico para el almacenamiento público
    sudo php artisan storage:link

    # Limpia la caché de configuración para producción
    sudo php artisan optimize
    ```

## 4. Verificación Final

Una vez completados los pasos anteriores, tu aplicación debería estar en línea.

1.  **Prueba de navegación**: Abre tu navegador y visita `http://tu-dominio.com`. La aplicación debería cargarse.

2.  **Revisión de logs**: Si encuentras un error 500 o cualquier otro problema, los logs son tu mejor aliado.

    *   **Log de errores de Apache**:

        ```bash
        sudo tail -f /var/log/apache2/error.log
        ```

    *   **Log de Laravel**:

        ```bash
        sudo tail -f /var/www/laravel-restaurantes/storage/logs/laravel.log
        ```

Estos archivos te darán pistas claras sobre cualquier problema de configuración, permisos o errores en el código.
