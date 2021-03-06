# MGLara 
## Sistema Administrativo/Comercial MG Papelaria - Versão Laravel

```
cd /var/www
mkdir MGLara
cd MGLara
git init
git remote add origin git@github.com:fabiomigliorini/MGLara.git
git pull origin master
composer install
php artisan key:generate
sudo a2enmod rewrite
sudo service apache2 restart
```

---

```
vi .env:

APP_ENV=local
APP_DEBUG=true
APP_KEY=lYk0mYMJOXtdZoKxSwt1da6LCUh9V4jn (SUBSTITUIR PELA CHAVE GERADA no comando php artisan key:generate)

DB_HOST=localhost
DB_DATABASE=mgsis
DB_USERNAME=mgsis
DB_PASSWORD=mgsis

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

---

```
Adicionaro ao /etc/apache2/sites-available/000-default.conf:

Dentro da tag </VirtualHost>

        <Directory /var/www/>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>

```

---

Configuração Supervisor para as filas

```
sudo apt-get install supervisor
sudo update-rc.d supervisor defaults
sudo vi /etc/supervisor/conf.d/laravel-worker.conf
```

Colocar dentro deste arquivo:

```
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/MGLara/artisan queue:work database --queue=urgent,high,medium,'',low --daemon
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/log/supervisor/laravel-worker.log
```

```

sudo supervisorctl reread

sudo supervisorctl update

sudo supervisorctl start laravel-worker:*

```

Para carregar mudanças no queue
```
php artisan queue:restart
```
