- Hay un backup dentro de la carpeta database/version/test_db.sql usarlo para crear la base de datos y su única tabla

- Correr los siguientes comandos por si acaso
php artisan config:cache
php artisan config:clear
php artisan route:cache

- En .env modificar con las credenciales reales en tu equipo para conectar a la bd
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=test_db
    DB_USERNAME=root
    DB_PASSWORD=12345678

- Si los comandos dan error de escritura, borrar el contenido de bootstrap/cache y storage/framework/cache y probar de nuevos los comandos de artisan
    
