<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<div class='alert alert-danger'>البريد الإلكتروني موجود بالفعل. <a href='login.html'>سجل دخول</a></div>";
        exit;
    }
    

    $query = "INSERT INTO users (full_name, email, password_hash) VALUES ('$fullname', '$email', '$hashed_password')";
    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success'>تم إنشاء الحساب بنجاح! <a href='login.html'>اضغط هنا لتسجيل الدخول</a></div>";
    } else {
        echo "<div class='alert alert-danger'>خطأ في التسجيل: " . mysqli_error($conn) . "</div>";
    }
}
?>