# Setup Sharing Technology project in development
## Requirment softwares:
1. Laravel Homestead ([https://laravel.com/docs/5.6/homestead](https://laravel.com/docs/5.6/homestead)):
+ Vagrant ([lastest](https://www.vagrantup.com/downloads.html))
+ Virtual box ([version 5.2](https://www.virtualbox.org/wiki/Downloads))
2. Ampps ([version 3.8](https://www.ampps.com/downloads))

    *Note: for NashTech only, need stop `World Wide Web Publishing Service` in Services in Windows to make port 80 free*

3. PostgreSQL ([version 9.3](https://www.postgresql.org/download/windows/))

4. Git SCM ([https://git-scm.com/](https://git-scm.com/))

5. (Optional) Source tree ([https://www.sourcetreeapp.com/](https://www.sourcetreeapp.com/))
## Setup for CakePHP (old project)
1. Clone source code from [rits-php repos](https://st-orange.backlog.jp/git/ORANGE/rits-php/tree/master)
2. Import `rits_20180209.tar` via pgAdmin
3. Point Ampps Virtual host to `[your_path]/rits-php/src`
4. Go to source code change:
+ app/Config/bootstrap.php

Change from

```php
Cache::config('browse', array(
		'engine' => 'Memcached',
		'prefix' => 'browse_',
		'servers' => array(
				'192.168.250.20:11211'
		),
));
```

to

```php
Cache::config('browse',array(
		'engine' => 'File', //[required]
		'duration' => '+1 day',//3600, //[optional]
		//'probability' => 100, //[optional]
		'path' => CACHE.'browse'.DS, //[optional] use system tmp directory - remember to use absolute path
		'prefix' => 'browse_', //[optional]  prefix every cache file with this string
		'lock' => false, //[optional]  use file locking
		//'serialize' => true, //[optional]
		//'mask' => 0666, //[optional]
));
```
5. Update database connection
```php
public $default = array(
		'datasource' => 'Database/Postgres',
		'persistent' => false,
		'host' => 'localhost',
		'port' => '5432',
		'login' => 'postgres',
		'password' => '123456',
		'database' => 'rits',
		'prefix' => '',
		'encoding' => 'utf8',
	);
```

6. (Optional) Go to `core.php` change:
```php
Configure::write('debug', 0);
```
to
```php
Configure::write('debug', 2);
```

7. Update file `hosts` in `C:\Windows\System32\drivers\etc` to make virtual host working

## Setup for Laravel (new project)

1. Clone source code from [rits-laravel-5.5 repos](https://st-orange.backlog.jp/git/ORANGE/rits-laravel-5.5/tree/master)
2. Open Git Bash and create SSH key ([https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/](https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/)):
```bash
$ ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
```
3. Go to Homestead folder, run and waiting **few minutes** to download and provisioning:
```bash
$ vagrant up
```
4. Update `Homestead.yml`
```bash
folders:
    - map: D:\NashTech-Projects\rits-laravel-5.5
      to: /home/vagrant/code

sites:
    - map: rits-vagrant.test
      to: /home/vagrant/code/public
```

5. Update file `hosts` in `C:\Windows\System32\drivers\etc` to make site working

6. SSH to Homestead go to `/home/vagrant/code` folder to run `composer install`
```bash
$ vagrant ssh
$ cd /home/vagrant/code
$ composer install
```

7. Go to `rits-laravel-5.5` project copy `.env.testing` to `.env` and then change 
```
(...)
APP_URL=http://rits-vagrant.test

DB_CONNECTION=pgsql
DB_HOST=[your_PC_id_address]
DB_PORT=5432
DB_DATABASE=rits
DB_USERNAME=postgres
DB_PASSWORD=[your_postgre_password]
(...)
```
*Note: check `your_PC_id_address` with cmd `ipconfig`*

8. Go to pgAdmin tools, modify `pg_hba.conf`
```
(...)
host	 all	 all	 [your_PC_id_address]/32	 md5
```

9. Go to `https://rits-vagrant.test` in browser and see the login form