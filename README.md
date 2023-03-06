# Despliegue del Proyecto
**Actualiza las dependencias del proyecto (es necesario tener Composer)**

```bash
composer update
```

**Configura la conexion (trabajamos con MySql), agrega:**

* Contraseña del usuario
* Nombre del usuario
* Nombre de la base de datos

```/env```
```env
...
DATABASE_URL="mysql://admin:admin@127.0.0.1:3306/symfony_posts?serverVersion=8&charset=utf8mb4"
...
```

**Crea la base de datos (si no existe)**
```bash
php bin/console doctrine:database:create
```

**Actualiza el esquema de la base de datos**
```bash
php bin/console doctrine:schema:update --force
```

**Iniciar el proyecto**

```php``` debe ser configurado como variable de entorno (si se utiliza xampp se recomienda el siguiente tutorial, https://dinocajic.medium.com/add-xampp-php-to-environment-variables-in-windows-10-af20a765b0ce)

Nos ubicamos en la carpeta ```\public```

Iniciamos el proyecto como un servidor de php

```bash
cd public
php -S localhost:8000
```

# Uso de la Aplicacion
