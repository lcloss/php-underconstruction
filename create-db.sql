SET @dbname = '';
SET @dbuser = '';
SET @dbpass = '';
CREATE DATABASE @dbname;
CREATE USER @dbuser@'localhost' IDENTIFIED BY @dbpass;
GRANT ALL PRIVILEGES ON *.@dbname TO @dbuser@'localhos';
FLUSH PRIVILEGES;

