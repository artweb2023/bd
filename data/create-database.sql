CREATE DATABASE org;

CREATE USER 'admin' @'127.0.0.1' IDENTIFIED BY 'PassWord@369';

GRANT ALL PRIVILEGES ON org.* TO 'admin' @'127.0.0.1';