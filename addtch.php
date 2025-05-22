<?php
session_start();

require "manar.php";
global $connect;
mysqli_set_charset($connect, 'utf8');

$success_message = '';
$error_message = '';

if (isset($_POST['add_teacher'])) {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $cat = mysqli_real_escape_string($connect, $_POST['cat']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);

    // توليد توكن عشوائي
    $token = uniqid('tch_');

    // رفع السيرة الذاتية
    $cvFileName = '';
    if (!empty($_FILES['cv']['name'])) {
        $uploadDir = 'uploads/cv/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['cv']['name']);
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowed = ['pdf', 'doc', 'docx'];

        if (in_array(strtolower($ext), $allowed)) {
            $newFileName = uniqid('cv_') . '.' . $ext;
            $filePath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['cv']['tmp_name'], $filePath)) {
                $cvFileName = $newFileName;
            } else {
                $error_message = "❌ فشل في رفع السيرة الذاتية.";
            }
        } else {
            $error_message = "❌ فقط الملفات PDF أو Word مسموحة.";
        }
    }

    if (empty($error_message)) {
        $insert = "INSERT INTO teachers 
                   (tea_name, tea_email, tea_cat, tea_pass, tea_token, tea_cv)
                   VALUES ('$name', '$email', '$cat', '$password', '$token', '$cvFileName')";

        if (mysqli_query($connect, $insert)) {
            header("Location: admin.php?success=1");
            exit();
        } else {
            $error_message = "❌ خطأ في إضافة البيانات.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة أستاذ جديد</title>
    <link href="style.css" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f6fc;
            color: #333;
            padding: 40px;
        }
        .form-box {
            background-color: #fff;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px #ccc;
        }
        .form-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .form-group input[type="file"] {
            padding: 5px;
        }
        .btn-submit {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-size: 16px;
        }
        .btn-submit:hover {
            background-color: #2980b9;
        }
        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>إضافة أستاذ جديد</h2>

        <?php if (!empty($error_message)): ?>
            <div class="message"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>اسم الأستاذ:</label>
                <input type="text" name="name" required />
            </div>

            <div class="form-group">
                <label>الإيميل:</label>
                <input type="email" name="email" required />
            </div>

            <div class="form-group">
                <label>الاختصاص:</label>
                <input type="text" name="cat" required />
            </div>

            <div class="form-group">
                <label>كلمة المرور:</label>
                <input type="password" name="password" required />
            </div>

            <div class="form-group">
                <label>السيرة الذاتية (PDF أو Word):</label>
                <input type="file" name="cv" />
            </div>

            <button type="submit" class="btn-submit" name="add_teacher">إضافة</button>
        </form>
    </div>
</body>
</html>
