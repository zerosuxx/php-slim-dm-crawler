CREATE USER 'app'@'%' IDENTIFIED BY 'app';
GRANT ALL PRIVILEGES ON app.* TO 'app'@'%';
GRANT ALL PRIVILEGES ON app_test.* TO 'app'@'%';