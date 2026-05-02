<?php
include "db.php";

$name = $conn->real_escape_string($_POST['name']);
$level = $conn->real_escape_string($_POST['level']);
$rating = intval($_POST['rating']);
$suggestion = $conn->real_escape_string($_POST['suggestion']);

$conn->query("INSERT INTO users (name, level) VALUES ('$name','$level')");
$user_id = $conn->insert_id;

$stmt = $conn->prepare("INSERT INTO feedback (user_id, rating, suggestion) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $rating, $suggestion);
$stmt->execute();

if(isset($_POST['systems'])){
    foreach($_POST['systems'] as $sys){
        $sys = $conn->real_escape_string($sys);
        $conn->query("INSERT INTO systems (user_id, system_name)
        VALUES ('$user_id','$sys')");
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تم الإرسال</title>

<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container" style="padding-top:120px; text-align:center;">

    <div class="form-card">
        <h2 style="color:#00d4ff;">تم إرسال الاستبيان بنجاح</h2>
        <p style="color:var(--sub-text)">شكراً لمساهمتك</p>

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