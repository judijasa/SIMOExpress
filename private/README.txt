
CasperJS requires PhantomJS

casper-php is a wrapper of casper-js
casper-js is a wrapper of phantom-js
phantom-js is an executable and cannot be modified

---

MANAGE MYSQL USER PRIVILEGES

1) Create a mysql user with read only privileges.  Use it in client_config.sh, file to be required in index.html 
Set the permission for client config.sh to be read only by anyone.

2) Set a mysql user with root privileges. Use it in admin_config.sh, file to be required in scripts with create/drop/update.

To create new read-only user in mysql server, access to mysql as root:

$ sudo mysql -u root

Then exec (the host '127.0.0.1' can be changed. For any host use '%')

mysql> CREATE USER 'joe'@'127.0.0.1' IDENTIFIED BY 'joe-user-password'; 
mysql> GRANT SELECT ON mydatabase.* TO 'joe'@'127.0.0.1'; 
mysql> FLUSH PRIVILEGES;
