<?php
include "db.php";
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>إدارة المستخدمين</title>

<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="header-wrapper">
    <div class="header">
       
        <div class="navbar right-nav">
            <a href="index.html">الرئيسية</a>

            <div class="dropdown">
                <a href="#" class="dropbtn">الأطلس الحيوي</a>
                <div class="dropdown-content">
                    <a href="skeletal.html">الهيكل العظمي</a>
                    <a href="muscular.html">الجهاز العضلي</a>
                    <a href="circulatory.html">الجهاز الدوري</a>
                    <a href="digestive.html">الجهاز الهضمي</a>
                    <a href="nervous.html">الجهاز العصبي</a>
                </div>
            </div>

            <a href="lab.html">مختبر دراسي</a>
            <a href="questionnaire.html">استبيان</a>
            <a href="funpage.html">لعبة الذاكرة</a>
            
            
        </div>

        <a href="index.html" class="logo-img-link">
            <img src="Logo.png" class="logo-img">
        </a>

        <div class="navbar left-nav">
            <a href="about.html">من نحن</a>
            <a href="contact.html">تواصل معنا</a>
            <a href="login.html" class="login-btn-header">تسجيل الدخول</a>
        </div>

    </div>
</div>

<div class="container" style="padding-top:120px;">

    <h2 style="color:#00d4ff; text-align:center;">إدارة المستخدمين</h2>

    <div class="form-card mt-4">
        <table class="table table-dark text-center">
            <tr>
                <th>ID</th>
                <th>الاسم</th>
                <th>المستوى</th>
                <th>حذف</th>
            </tr>

            <?php while($row = $result->fetch_assoc()){ ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['level'] ?></td>
                <td>
                    <form action="delete.php" method="POST">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button class="btn btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            <?php } ?>

        </table>
    </div>

</div>

<div class="footer">
    <p>© 2026 Anatomy Atlas || Science Team</p>
</div>

</body>
</html>