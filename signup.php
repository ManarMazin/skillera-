<?php
session_start();

if (@$_SESSION['islogin'] == 'yes') {
    echo '<meta http-equiv="refresh" content="0; url=index.php"/>';
    exit();
}

require "manar.php";
global $connect;
mysqli_set_charset($connect, 'utf8');

@$post01 = $_POST['get01']; // الاسم
@$post02 = $_POST['get02']; // البريد
@$post03 = $_POST['get03']; // كلمة المرور
@$post04 = $_POST['get04']; // تاريخ الميلاد
@$post05 = $_POST['get05']; // زر الإرسال

$Token = date('ymdhis') . rand(100, 999);

if (isset($post05)) {
    $check = mysqli_query($connect, "SELECT * FROM users WHERE user_email = '$post02'");
    if (mysqli_num_rows($check) > 0) {
        echo '
            <div style="text-align:center;color:#f00;margin: 50px auto;font-weight:bold;">
                <img width="200" src="error.png" />
                <p>عذراً، هذا البريد الإلكتروني مسجّل مسبقاً</p>
            </div>
            <meta http-equiv="refresh" content="3; url=signup.php"/>
        ';
        exit();
    }

    $query = "INSERT INTO users (
        user_token,
        user_name,
        user_email,
        user_pass,
        user_birthday
    ) VALUES (
        '$Token',
        '$post01',
        '$post02',
        '$post03',
        '$post04'
    )";

    if (mysqli_query($connect, $query)) {
        $_SESSION['Token'] = $Token;
        $_SESSION['Name'] = $post01;
        $_SESSION['islogin'] = 'yes';

        echo '
            <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                <img width="200" src="11.png" />
                <p>تم إنشاء الحساب بنجاح</p>
            </div>
            <meta http-equiv="refresh" content="3; url=index.php"/>
        ';
        exit();
    } else {
        echo '
            <div style="text-align:center;color:#f00;margin: 50px auto;font-weight:bold;">
                <img width="200" src="error.png" />
                <p>حدث خطأ أثناء إنشاء الحساب</p>
            </div>
            <meta http-equiv="refresh" content="3; url=signup.php"/>
        ';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 16px;
            box-shadow: 0 0 15px rgba(0, 188, 212, 0.3);
        }
        h2 {
            text-align: center;
            color:#03a9f4 ;
            margin-bottom: 20px;
            font-size: 28px;
        }


.input-group {
    margin-bottom: 20px;
    position: relative;
    width: 100%;
    box-sizing: border-box;
}

.input-group input {
    width: 100%;
    padding: 12px 45px 12px 12px;
    border: 1px solid #03a9f4;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    box-sizing: border-box; /* يمنع الخروج خارج العنصر */
}

.input-group i {
    position: absolute;
    right: 10px;
    top: 13px;
    color: #03a9f4;
}

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color:  #03a9f4;
            border: none;
            border-radius: 50px;
            color: white;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .submit-btn:hover {
            background-color:  #03a9f4;
        }
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        .back-link a {
            color:  #03a9f4;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <form method="post" action="">
        <h2><i class="fas fa-user-plus"></i> إنشاء حساب جديد</h2>

        <div class="input-group">
            <input type="text" name="get01" placeholder="الاسم الكامل" required />
            <i class="fa fa-user"></i>
        </div>

        <div class="input-group">
            <input type="email" name="get02" placeholder="البريد الإلكتروني" required />
            <i class="fa fa-envelope"></i>
        </div>

        <div class="input-group">
            <input type="password" name="get03" placeholder="كلمة المرور" required />
            <i class="fa fa-lock"></i>
        </div>

        <div class="input-group">
            <input type="age" name="get04" placeholder="تاريخ الميلاد" required />
           
        </div>

        <input type="submit" name="get05" value="إنشاء الحساب" class="submit-btn" />

        <div class="back-link">
            <a href="login.php"><i class="fa fa-arrow-right"></i> العودة لتسجيل الدخول</a>
        </div>
    </form>
</div>

</body>
</html>




