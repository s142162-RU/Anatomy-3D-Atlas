<?php

// الاتصال بالسيرفر فقط
$conn = new mysqli("localhost", "root", "");

if ($conn->connect_error) {
    die("Connection failed");
}

// إنشاء قاعدة البيانات إذا ما كانت موجودة
$conn->query("CREATE DATABASE IF NOT EXISTS anatomy_db");

// اختيار القاعدة
$conn->select_db("anatomy_db");

// إنشاء جدول users
$conn->query("
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    level VARCHAR(50)
)
");

// إنشاء جدول feedback
$conn->query("
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    rating INT,
    suggestion TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
)
");

// إنشاء جدول systems
$conn->query("
CREATE TABLE IF NOT EXISTS systems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    system_name VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id)
)
");

?>