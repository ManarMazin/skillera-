<?php
session_start();
require "manar.php";

// تأكد من أن الاتصال موجود وأن الترميز مضبوط على UTF-8
mysqli_set_charset($connect, 'utf8');

// التحقق من وجود جلسة تسجيل الدخول
if (!isset($_SESSION['admin_id'])) {
    header("Location: manager_login.php");
    exit();
}

// استعلام لجلب بيانات الطلاب
$adQuery = "SELECT * FROM student";
$adRun = mysqli_query($connect, $adQuery);

// التحقق من نجاح الاستعلام وعدد النتائج
$adNum = ($adRun) ? mysqli_num_rows($adRun) : 0;
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الادمن</title>
    <link href="style.css" rel="stylesheet"/>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="fontawesome/css/brands.css" rel="stylesheet" />
    <link href="fontawesome/css/solid.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/duotone-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-duotone-thin.css" rel="stylesheet" />
</head>
<body class="style19">
    <table class="style12">
        <tr>
            <td>لوحة الادمن</td>
            <td><a class="style13" href="manager_logout.php"><i class="fa-solid fa-arrow-left"></i></a></td>
        </tr>
    </table>
    <div>
        <a class="style14" href="addst.php">اضافة طالب جديد</a>
    </div>
    <div>
        <a class="style15" href="">
            <p class="style16"><?php echo $adNum; ?></p>
            <p>عدد الطلاب المسجلين </p>
        </a>

        <div>
        <a class="style14" href="allstu.php">كل الطلاب المسجلين في المدرسة</a>
    </div>
    <div>
        <a class="style14" href="alltech.php">بيانات الكادر التدريسي</a>
    </div>
    <div>
        <a class="style14" href="addtch.php">اضافة استاذ جديد </a>
    </div>

    <div>
        <a class="style14" href="view_all_videos.php">المحاضرات المرفوعة </a>
    </div>
    <div>
        <a class="style14" href="online_st.php">المستخدمون او الطلبة المسجلون في المنصة</a>
    </div>
    </div>
</body>
</html>

