
-- kenapa saya menggunakan migrasi , biar gampang aja pas debugging / pas proses developing , biar ga bolak balim phpmyadmin buat benerin kolom 
-- dan biar lebih masuk aja soalnya make react + supabase juga , jadinya migration harus.

CREATE DATABASE IF NOT EXISTS todolist_db;

USE todolist_db;

CREATE TABLE IF NOT EXISTS crud_051 (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE NOT NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
