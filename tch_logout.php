<?php
session_start();

// التحقق إذا كان المستخدم قد سجل الدخول
if (isset($_SESSION['teacher_logged_in']) && $_SESSION['teacher_logged_in'] == true) {
    // إنهاء الجلسة
    session_unset(); // إزالة جميع المتغيرات الخاصة بالجلسة
    session_destroy(); // تدمير الجلسة

    // إعادة التوجيه إلى صفحة تسجيل الدخول بعد تسجيل الخروج
    header('Location: tch_login.php');
    exit();
} else {
    // إذا لم يكن هناك جلسة نشطة، إعادة التوجيه إلى صفحة تسجيل الدخول
    header('Location: tch_login.php');
    exit();
}
?>
