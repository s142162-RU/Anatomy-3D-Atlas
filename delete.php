<?php
include "db.php";

$id = $_POST['id'];

// حذف مرتبط
$conn->query("DELETE FROM systems WHERE user_id=$id");
$conn->query("DELETE FROM feedback WHERE user_id=$id");

// حذف المستخدم
$conn->query("DELETE FROM users WHERE id=$id");

echo "<h2 style='color:red'>تم الحذف</h2>";
?>