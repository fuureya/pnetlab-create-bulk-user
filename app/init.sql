CREATE DATABASE IF NOT EXISTS pnetlab_db;
USE pnetlab_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255),
    name VARCHAR(255) DEFAULT 'PNETLab User',
    password VARCHAR(255) NOT NULL,
    role INT DEFAULT 1,
    offline INT DEFAULT 1,
    user_status INT DEFAULT 1,
    pod INT UNIQUE,
    online_time INT,
    expired_time INT
);
