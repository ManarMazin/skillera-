<?php
session_start();
require 'manar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // الحصول على البيانات من النموذج
    $username = mysqli_real_escape_string($connect, $_POST['tea_name']);
    $password = mysqli_real_escape_string($connect, $_POST['tea_pass']);

    // التحقق من وجود المعلم في قاعدة البيانات
    $query = "SELECT * FROM teachers WHERE tea_name = '$username' AND tea_pass = '$password'";
    $result = mysqli_query($connect, $query);

    if ($result) {
        // التحقق من أن الاستعلام نجح
        if (mysqli_num_rows($result) > 0) {
            $teacher = mysqli_fetch_assoc($result);

            // تخزين المعلم في الجلسة
            $_SESSION['teacher_logged_in'] = true;
            $_SESSION['teacher_id'] = $teacher['tea_id']; // تخزين معرف المعلم في الجلسة
            $_SESSION['teacher_name'] = $teacher['tea_name']; // تخزين اسم المعلم في الجلسة

            header('Location: tch_dashboard.php'); // إعادة توجيه إلى لوحة التحكم
            exit();
        } else {
            $error_message = "❌ اسم المستخدم أو كلمة المرور غير صحيحة.";
        }
    } else {
        $error_message = "❌ حدث خطأ في قاعدة البيانات.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc; /* خلفية بيضاء مع لمسة من اللون الفاتح */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #1e88e5; /* الأزرق الفاتح */
            font-size: 24px;
            margin-bottom: 20px;
        }

        .error {
            color: #ff3d00;
            margin-bottom: 15px;
            font-size: 14px;
        }

        label {
            font-size: 14px;
            color: #333;
            text-align: right;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #1e88e5;
        }

        button {
            background-color: #1e88e5;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #1565c0; /* لون أزرق غامق عند التمرير */
        }

        p {
            margin-top: 20px;
            font-size: 14px;
        }

        p a {
            color: #1e88e5;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>تسجيل الدخول</h2>
    <?php if (isset($error_message)) { echo "<p class='error'>{$error_message}</p>"; } ?>

    <form method="POST" action="">
        <label for="tea_name">اسم المستخدم</label>
        <input type="text" name="tea_name" id="tea_name" required>

        <label for="tea_pass">كلمة المرور</label>
        <input type="password" name="tea_pass" id="tea_pass" required>

        <button type="submit">تسجيل الدخول</button>
    </form>

    <p>ليس لديك حساب؟ <a href="tch_signup.php">إنشاء حساب جديد</a></p>
</div>

</body>
</html>
