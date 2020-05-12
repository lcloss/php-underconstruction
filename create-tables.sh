#!/bin/bash
#
# Use: ./create-tables.sh
php artesao create:table Contacts
php artesao create:table Spammers
php artesao create:table NotifierList
php artesao make:table Contacts
php artesao make:table Spammers
php artesao make:table NotifierList
php artesao seed:table Contacts
php artesao seed:table Spammers
php artesao seed:table NotifierList