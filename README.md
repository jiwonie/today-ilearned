# php-mvc-framework

__prerequisite__

 - php 7.3.9 or later is required.
 - mysql 5.7.30 or later is required.

__run web server__

 - php built-in web server `php -S localhost:8000`
 - docker images [docker lamp stack](https://hub.docker.com/r/mattrayner/lamp 'docker hub')
 - or [mamp](https://www.mamp.info/ 'mamp'), [xampp](https://www.apachefriends.org/ 'xampp')

__references__

 - The php.ini file must be created within the php folder.
 - PDO extension should be enabled within the php.ini file.

__docker references__

 - _mysql password is displayed at first generation._
 - `docker run --name "project" -p "80:80" -v /c/dev/workspace/project:/var/www/html mattrayner/lamp:latest`
 - `mysql -uadmin -p<PASSWORD> -h<HOST> -P<PORT>`
 - `localhost/phpmyadmin/index.php`

