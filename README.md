## Requirements
* PHP 8.2 or higher
* Database (eg: MySQL, PostgreSQL, SQLite)
* Web Server (eg: Apache, Nginx, IIS)

<hr/>

## Installation
* Install [Composer](https://getcomposer.org/download)
* Clone the repository: `git clone https://github.com/CS-BusinessDev/web-payroll.git`
* Install PHP dependencies: `composer install`
* Setup configuration: `cp .env.example .env`
* Generate application key: `php artisan key:generate`
* Create a database and update your configuration.
* Run database migration: `php artisan migrate`
* Run database seeder: `php artisan db:seed`
* Run the dev server: `php artisan serve`
