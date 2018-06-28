Requirements
------------
This is a [Lumen](https://lumen.laravel.com) project, so it will require you to have Vagrant and  virtual machine.
For more info visit the [Requirements](https://lumen.laravel.com/docs/5.6)

Installation
------------
Run the composer command in the project folder
```bash
composer install
```

Run the vagrant command in the project folder
```bash
vagrant up
```
Create a .env file which matches your settings. The preset below might help

```php
APP_ENV=local
APP_DEBUG=true
APP_KEY=
APP_TIMEZONE=UTC

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret

CACHE_DRIVER=file
QUEUE_DRIVER=sync

```
Run the database migrations and seeders
```bash
php artisan migrate:refresh --seed
```

If everything went right you should be able to visit the API service at
```bash
http://192.168.10.10/
```
Discounts
------------
Send your order to this route to calculate discounts
```bash
POST -> http://192.168.10.10/api/discount
```

Results
------------
This is what you should get

<p align="center">
<img src="https://i.gyazo.com/55c53e4415f8327538e7ba2dd9df49e5.png">
</p>
<p align="center">
<img src="https://i.gyazo.com/89b232df95fbeab5df38ad0b4722c49e.png">
</p>
<p align="center">
<img src="https://i.gyazo.com/d336df66ce88422507c2c1d5cf76f61e.png">
</p>