<?php
require "manar.php";  // تضمين ملف الاتصال بقاعدة البيانات
session_start();  // بدء الجلسة

global $connect;
mysqli_set_charset($connect, 'utf8');

// فحص إذا كانت بيانات POST موجودة
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post01 = $_POST['get01'];  // اسم المستخدم
    $post02 = $_POST['get02'];  // كلمة المرور
    $post03 = $_POST['get03'];  // متغير لإرسال البيانات (إن وجد)

    if (isset($post03)) {
        // استخدام Prepared Statement بدلاً من استعلام غير آمن
        $stmt = $connect->prepare("SELECT * FROM users WHERE user_name = ? AND user_pass = ?");
        $stmt->bind_param("ss", $post01, $post02);  // ربط المتغيرات مع الاستعلام
        $stmt->execute();  // تنفيذ الاستعلام
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // إذا تم العثور على المستخدم
            $_SESSION['student_logged_in'] = true;  // تخزين حالة تسجيل الدخول في الجلسة
            $_SESSION['user_name'] = $post01;  // تخزين اسم المستخدم في الجلسة

            echo '
                <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                    <img width="200" src="11.png" />
                    <p>شكراً تم تسجيل الدخول بنجاح</p>
                </div>
                <meta http-equiv="refresh" content="3;url=courses.php"/>
            ';
            exit();
        } else {
            // إذا لم يتم العثور على بيانات المستخدم في قاعدة البيانات
            echo '
                <div style="text-align:center;color:#f00;margin: 50px auto;font-weight:bold;">
                    <img width="200" src="11.png" />
                    <p>عذراً لا يتوفر حساب بهذه المعلومات</p>
                </div>
                <meta http-equiv="refresh" content="2;url=login.php"/>
            ';
            exit();
        }
    }

}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #ffffff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-container {
      max-width: 400px;
      margin: 100px auto;
      padding: 40px;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 188, 212, 0.15);
    }

    .login-container h2 {
      text-align: center;
      color:  #03a9f4;
      margin-bottom: 30px;
      font-weight: bold;
    }

    .input-group {
      position: relative;
      margin-bottom: 25px;
    }

    .input-group input {
      width: 100%;
      padding: 12px 45px 12px 12px;
      border: 2px solid  #03a9f4;
      border-radius: 8px;
      font-size: 15px;
      box-sizing: border-box;
      transition: 0.3s;
      background-color: #fff;
    }

    .input-group input:focus {
      outline: none;
      box-shadow: 0 0 5px rgba(0, 188, 212, 0.4);
    }

    .input-group i {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      right: 12px;
      color:  #03a9f4;
      font-size: 18px;
    }

    .btn {
  width: 100%;
  padding: 14px;
  background: linear-gradient(to right,  #03a9f4,  #03a9f4); /* تدرج السمائي الفاتح */
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 18px; /* تكبير الخط */
  font-weight: bold; /* جعله غامق */
  text-align: center; /* توسيط النص */
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0, 188, 212, 0.3);
  transition: 0.3s ease-in-out;
}

.btn:hover {
  background: linear-gradient(to right,  #03a9f4,  #03a9f4);
  box-shadow: 0 6px 12px rgba(0, 172, 193, 0.5);
}



    .footer {
      text-align: center;
      margin-top: 20px;
    }

    .footer a {
      color:  #03a9f4;
      text-decoration: none;
      font-weight: bold;
      margin: 0 10px;
    }

    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>تسجيل الدخول</h2>
    <form method="post" action="login.php">
      <div class="input-group">
        <input type="text" name="get01" placeholder="اسم المستخدم" required>
        <i class="fa-solid fa-user"></i>
      </div>
      <div class="input-group">
        <input type="password" name="get02" placeholder="كلمة المرور" required>
        <i class="fa-solid fa-lock"></i>
      </div>
      <div class="input-group">
        <input type="submit" name="get03" value="تسجيل الدخول" class="btn">
      </div>
    </form>
    <div class="footer">
      <a href="signup.php">إنشاء حساب</a> |
      <a href="index.html">الرجوع</a>
    </div>
  </div>

</body>
</html>

