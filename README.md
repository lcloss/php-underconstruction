# php-underconstruction
Underconstruction page for new sites
## Description
This project is made on Slim Framework 3.
This intend to be a very simple "Underconstruction" page, but with the
feature to visitors leave their email addresses to be informed when the
site goes alive.
Also, it is important to make a contact point. So a contact form is also included.
Change the layouts as you like.
You can take advantage of this project to many simple projects.

## Installation
First, update composer so the vendor's source can be loaded:
```bash
composer update
```

## artesao commands
Artesao is like Laravel artisan.
You can create controllers and tables.

To see a list of available commands, type:
```bash
php artesao list
```

## Creating controllers
To create a Controller, simple run the ```create:controller``` command:
```bash
php artesao create:controller Admin
```
This command will create a file AdminController under ```app/Controllers/``` and also will create an entry 
on app controllers, on ```src/config/controllers.php```.

To delete a Controller, do:
```bash
php artesao drop:controller Admin
```

## Creating tables
To create a Table, simple run the ```create:table``` command:
```bash
php artesao create:table Users
```
This command will create a file UsersTable.php under ```app/Db/``` folder.
Change this file to meet your table columns and seed data. 

This will also create a UsersModel.php under ```app/Models/``` folder.
This is the class you can use on your system.

You can populate data to the table by command line.
Just change the ```$this->seed``` variable on UsersTable.php.

To make database changes, do a:
```bash
php artesao make:table Users
```
This command will create the table on database.

To populate data into table, do a:
```bash
php artesao seed:table Users
```

Or, to drop a table and remove all references:
```bash
php artesao drop:table Users
```