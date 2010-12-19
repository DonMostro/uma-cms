CREATE USER 'ztvuser'@'localhost' IDENTIFIED BY 'ztvpassword';

GRANT USAGE ON * . * TO 'ztvuser'@'localhost' IDENTIFIED BY 'ztvpassword' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

GRANT ALL PRIVILEGES ON `ztv_cms` . * TO 'ztvuser'@'localhost';
