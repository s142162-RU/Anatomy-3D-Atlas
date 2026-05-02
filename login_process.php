<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $result = mysqli_query($conn, "SELECT id, full_name, password_hash FROM users WHERE email='$email'");
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            header("Location: lab.php"); 
            exit;
        } else {
            echo "<div class='alert alert-danger'>كلمة المرور غير صحيحة</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>البريد الإلكتروني غير مسجل</div>";
    }
}
?>