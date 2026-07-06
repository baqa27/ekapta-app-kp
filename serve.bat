@echo off
REM Laravel 8 - PHP 8.1
set PATH=D:\xampp8.1\php;%PATH%
echo Using PHP 8.1 for Laravel 8
php -v
echo.
php artisan serve
