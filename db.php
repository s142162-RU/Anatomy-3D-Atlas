<?php
// db.php - Database connection for Anatomy Atlas

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'anatomy_db';

// Create connection (procedural style, consistent)
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set Arabic/UTF-8 encoding
mysqli_set_charset($conn, "utf8");

// Create database if not exists (optional but safe)
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $database");
mysqli_select_db($conn, $database);

// Create tables if not exists
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$sql_organs = "CREATE TABLE IF NOT EXISTS organs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name_ar VARCHAR(100) NOT NULL,
    name_en VARCHAR(100) NOT NULL,
    system_type VARCHAR(50) NOT NULL,
    description TEXT
)";

$sql_notes = "CREATE TABLE IF NOT EXISTS user_notes (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    organ_id INT(11) NOT NULL,
    note_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (organ_id) REFERENCES organs(id) ON DELETE CASCADE
)";

mysqli_query($conn, $sql_users);
mysqli_query($conn, $sql_organs);
mysqli_query($conn, $sql_notes);

// Insert sample organs (if table is empty)
$check = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM organs");
$row = mysqli_fetch_assoc($check);
if ($row['cnt'] == 0) {
    mysqli_query($conn, "INSERT INTO organs (name_ar, name_en, system_type, description) VALUES
        ('الجمجمة', 'Skull', 'هيكل عظمي', 'تحمي الدماغ'),
        ('القلب', 'Heart', 'دوري', 'يضخ الدم'),
        ('المعدة', 'Stomach', 'هضمي', 'تهضم الطعام'),
        ('العضلة ذات الرأسين', 'Biceps', 'عضلي', 'تثني الذراع'),
        ('النخاع الشوكي', 'Spinal Cord', 'عصبي', 'ينقل الإشارات العصبية')
    ");
}

?>
