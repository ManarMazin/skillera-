<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "manar.php"; // الاتصال بقاعدة البيانات

    // الحصول على بيانات النموذج
    $tea_name = mysqli_real_escape_string($connect, $_POST['tea_name']);
    $tea_email = mysqli_real_escape_string($connect, $_POST['tea_email']);
    $tea_pass = mysqli_real_escape_string($connect, $_POST['tea_pass']);

    // التحقق من أن البريد الإلكتروني غير موجود مسبقًا
    $check_query = "SELECT * FROM teachers WHERE tea_email = '$tea_email'";
    $check_result = mysqli_query($connect, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "البريد الإلكتروني موجود مسبقًا. يرجى استخدام بريد إلكتروني آخر.";
    } else {
        // توليد التوكن
        $tea_token = md5(uniqid(rand(), true));

        // إدخال المعلم في قاعدة البيانات
        $insert_query = "INSERT INTO teachers (tea_name, tea_email, tea_pass, tea_token) 
                         VALUES ('$tea_name', '$tea_email', '$tea_pass', '$tea_token')";

        if (mysqli_query($connect, $insert_query)) {
            $_SESSION['teacher_logged_in'] = true; // تسجيل دخول المعلم بعد التسجيل
            $_SESSION['teacher_name'] = $tea_name;
            $_SESSION['teacher_token'] = $tea_token;

            header('Location: teacher_dashboard.php'); // إعادة توجيه إلى لوحة التحكم الخاصة بالمعلم
            exit();
        } else {
            $error = "حدث خطأ أثناء إنشاء الحساب. يرجى المحاولة لاحقًا.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - المعلم</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* تعريف الأنماط الأساسية */
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

        .register-container {
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

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
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

<div class="register-container">
    <h2>إنشاء حساب جديد</h2>
    <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
    <form method="POST" action="">
        <label for="tea_name">اسم المعلم</label>
        <input type="text" name="tea_name" id="tea_name" required>

        <label for="tea_email">البريد الإلكتروني</label>
        <input type="email" name="tea_email" id="tea_email" required>

        <label for="tea_pass">كلمة المرور</label>
        <input type="password" name="tea_pass" id="tea_pass" required>

        <button type="submit">إنشاء حساب</button>
    </form>

    <p>لديك حساب؟ <a href="tch_login.php">تسجيل الدخول</a></p>
</div>

</body>
</html>
