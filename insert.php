<?php
include "db.php";

// user
$name = $_POST['name'];
$level = $_POST['level'];

$conn->query("INSERT INTO users (name, level) VALUES ('$name','$level')");
$user_id = $conn->insert_id;

// feedback
$rating = $_POST['rating'];
$suggestion = $_POST['suggestion'];

$conn->query("INSERT INTO feedback (user_id, rating, suggestion)
VALUES ('$user_id','$rating','$suggestion')");

// systems
if(isset($_POST['systems'])){
    foreach($_POST['systems'] as $sys){
        $conn->query("INSERT INTO systems (user_id, system_name)
        VALUES ('$user_id','$sys')");
    }
}

echo "<h2 style='color:green'>تم الإدخال بنجاح</h2>";
?>