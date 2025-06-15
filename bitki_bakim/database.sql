CREATE DATABASE IF NOT EXISTS bitki_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE bitki_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS bitkiler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    isim VARCHAR(255) NOT NULL,
    tur VARCHAR(100) NOT NULL,
    son_sulama_tarihi DATE NOT NULL,
    son_bakim_tarihi DATE NOT NULL,
    notlar TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
