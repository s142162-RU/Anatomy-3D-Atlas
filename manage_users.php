<?php
include "db.php";

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#0f172a;color:white;">

<div class="container mt-5">

<h2 class="text-center text-info">إدارة المستخدمين</h2>

<table class="table table-dark text-center mt-3">
<tr>
<th>ID</th>
<th>الاسم</th>
<th>المستوى</th>
<th>حذف</th>
</tr>

<?php
while($row = $result->fetch_assoc()){
echo "
<tr>
<td>{$row['id']}</td>
<td>{$row['name']}</td>
<td>{$row['level']}</td>
<td>
<form action='delete.php' method='POST'>
<input type='hidden' name='id' value='{$row['id']}'>
<button class='btn btn-danger'
onclick=\"return confirm('هل أنت متأكد؟')\">
Delete
</button>
</form>
</td>
</tr>
";
}
?>

</table>

</div>

</body>
</html>