# Boaty

## Requerimientos

Instalar las dependencias del backend
```bash
composer install
```

Instalar las dependencias del frontend
```bash
npm install
```

Crear una base de datos local (con xampp o Laragon) 
y copiar los datos de la misma en un archivo ".env"
dentro de la raíz del proyecto (tomar el archivo .env.example como ejemplo)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=boaty  <- Nombre de la base de datos
DB_USERNAME=root   <- Usuario
DB_PASSWORD=123456 <- Contraseña
```

Crear la "key" de la aplicación y ejecutar las migraciones
```bash
php artisan key:generate

php artisan migrate --seed
```

## Emulacion local

para compilar los archivos del frontend
```bash
npm run dev
```
para compilar los archivos del frontend y observar sus cambios
```bash
npm run watch
```

para iniciar el servidor de laravel
```bash
php artisan serve
```

si desea iniciar un servidor local de laravel y consumirlo con una aplicación
movil corriendo en un dispositivo, deberá seguir los siguientes pasos.

1_ Abrir la CMD y correr el comando "ipconfig"

2_ Buscar la opción "Dirección IPv4" y copiar el resultado. EJ: 190.90.0.0

3_ Abrir el archivo "./config/sanctum.php" y añadir la nueva dirección a la lista de URL validas

```php
return [
    'stateful' => explode(',', env(
        'SANCTUM_STATEFUL_DOMAINS',
        '...,190.90.0.0:8000,...' . parse_url(env('APP_URL'), PHP_URL_HOST)
    )),

// ....
]

```

4_ Ejecutar el servidor laravel local con apuntando a esa dirección IP como host

```bash
php artisan serve --host=190.90.0.0
```

Las llamadas al servidor dentro de la aplicación también deberán ser a la misma dirección IP

## Acceso y Funciones incluidas

Para acceder a la aplicación, se puede usar
los datos registrados durante la migración de la base
de datos.

**USUARIO**    : *jhon@mail.com*

**CONTRASEÑA** : *123456*

El sistema también cuenta con un panel para realizar pruebas de las funciones
backend. Puede acceder al mismo haciendo clic en el botón "DEV" en la sección
inferior del menu lateral izquierdo o navegando a la ruta "localhost:8000/dev"