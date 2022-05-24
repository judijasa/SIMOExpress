
CasperJS requires PhantomJS

casper-php is a wrapper of casper-js
casper-js is a wrapper of phantom-js
phantom-js is an executable and cannot be modified

------

MANAGE MYSQL USER PRIVILEGES

Create a mysql user with read only privileges.
Use it in client_config.sh,
file to be required in index.html
Set the permission for client config.sh to
be read only by anyone.

Set a mysql user with root privileges.
Use it in admin_config.sh,
file to be required in scripts with
create/drop/update.

To create new read-only user in mysql server

Access to mysql as root:

$ sudo mysql -u root

Then exec (the host '127.0.0.1' can be changed. For any host use '%')

mysql> CREATE USER 'joe'@'127.0.0.1' IDENTIFIED BY '_xFILe5!0'; 
mysql> GRANT SELECT ON ciudadania_abierta.* TO 'joe'@'127.0.0.1'; 
mysql> FLUSH PRIVILEGES;

CREATE USER 'joe'@'127.0.0.1' IDENTIFIED BY '_Xy00yX_';
GRANT SELECT ON ciudadania_abierta.* TO 'joe'@'127.0.0.1';
FLUSH PRIVILEGES;
