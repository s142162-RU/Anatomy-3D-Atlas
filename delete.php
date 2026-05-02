<?php
include "db.php";

$id = $_POST['id'];


$conn->query("DELETE FROM systems WHERE user_id=$id");
$conn->query("DELETE FROM feedback WHERE user_id=$id");

$conn->query("DELETE FROM users WHERE id=$id");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تم الحذف</title>

<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.form-card {
    background: #000;
    border-radius: 20px;
    padding: 30px;
    border: 1px solid #1e293b;
    box-shadow: 0 0 20px rgba(0,212,255,0.15);
    max-width: 600px;
    margin: auto;
    text-align: center;
}
</style>
</head>

<body>

<div class="container" style="padding-top:120px;">

    <div class="form-card">
        <h2 style="color:red;">تم الحذف بنجاح</h2>
        <p style="color: var(--sub-text);">تم إزالة المستخدم من النظام</p>

        <a href="manage_users.php" class="submit-btn" style="display:inline-block; width:auto;">
            العودة لإدارة المستخدمين
        </a>
        </br>
        <a href="index.html" class="submit-btn" style="display:inline-block; width:auto;">
            العودة للرئيسية
        </a>
    </div>

</div>

<div class="footer">
    <p>© 2026 Anatomy Atlas || Science Team</p>
</div>

</body>
</html>