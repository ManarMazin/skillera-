<?php
session_start();
require 'manar.php'; // الاتصال بقاعدة البيانات

// التحقق من تسجيل الدخول
if (!isset($_SESSION['teacher_logged_in']) || $_SESSION['teacher_logged_in'] !== true) {
    header('Location: tch_login.php');
    exit();
}

$tea_id = $_SESSION['teacher_id'];

// جلب اسم المعلم
$teacher_query = "SELECT tea_name FROM teachers WHERE tea_id = ?";
$teacher_stmt = mysqli_prepare($connect, $teacher_query);
if (!$teacher_stmt) {
    die("خطأ في تحضير الاستعلام: " . mysqli_error($connect));
}
mysqli_stmt_bind_param($teacher_stmt, 'i', $tea_id);
mysqli_stmt_execute($teacher_stmt);
$teacher_result = mysqli_stmt_get_result($teacher_stmt);
$teacher_row = mysqli_fetch_assoc($teacher_result);
$teacher_name = $teacher_row ? $teacher_row['tea_name'] : "معلم غير معروف";

// حذف فيديو
if (isset($_GET['delete_id'])) {
    $video_id = $_GET['delete_id'];

    // التحقق من ملكية الفيديو
    $check_sql = "SELECT tea_id FROM videos WHERE id = ?";
    $check_stmt = mysqli_prepare($connect, $check_sql);
    mysqli_stmt_bind_param($check_stmt, 'i', $video_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if ($video = mysqli_fetch_assoc($check_result)) {
        if ($video['tea_id'] == $tea_id) {
            $delete_sql = "DELETE FROM videos WHERE id = ? AND tea_id = ?";
            $delete_stmt = mysqli_prepare($connect, $delete_sql);
            mysqli_stmt_bind_param($delete_stmt, 'ii', $video_id, $tea_id);
            mysqli_stmt_execute($delete_stmt);
            header('Location: tch_dashboard.php');
            exit();
        } else {
            echo "لا يمكنك حذف فيديو لا يخصك.";
        }
    } else {
        echo "الفيديو غير موجود.";
    }
}

// جلب فيديوهات المعلم
$sql = "SELECT id, title, course_name, category, level, uploaded_at FROM videos WHERE tea_id = ? ORDER BY uploaded_at DESC";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, 'i', $tea_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المعلم</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
            direction: rtl;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #03a9f4;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h2 {
            margin: 0;
        }
        .header .buttons a {
            background-color: white;
            color: #03a9f4;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
            font-weight: bold;
        }
        .header .buttons a:hover {
            background-color: #e6f7ff;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
        }
        .card-header {
            background-color: #03a9f4;
            color: white;
            padding: 10px;
            border-radius: 8px;
            font-size: 18px;
        }
        .card-body {
            padding: 15px;
        }
        .actions {
            margin-top: 10px;
            display: flex;
            justify-content: space-around;
        }
        .btn {
            background-color: #03a9f4;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .no-videos {
            text-align: center;
            color: gray;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>مرحبًا، <?= htmlspecialchars($teacher_name) ?></h2>
    <div class="buttons">
        <a href="upload_video.php">➕ إضافة درس جديد</a>
        <a href="tch_logout.php">🚪 تسجيل الخروج</a>
    </div>
</div>

<div class="container">
    <h3>الدروس المضافة</h3>

    <?php if (mysqli_num_rows($result) === 0): ?>
        <p class="no-videos">ليس لديك أي فيديوهات مضافة حتى الآن.</p>
    <?php else: ?>
        <div class="grid">
            <?php while ($v = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <div class="card-header"><?= htmlspecialchars($v['title']) ?></div>
                    <div class="card-body">
                        <p>📘 الدورة: <?= htmlspecialchars($v['course_name']) ?></p>
                        <p>🗂️ التخصص: <?= htmlspecialchars($v['category']) ?> | المستوى: <?= htmlspecialchars($v['level']) ?></p>
                        <p>📅 التاريخ: <?= date("Y-m-d", strtotime($v['uploaded_at'])) ?></p>
                        <div class="actions">
                            <a href="edit_video.php?id=<?= $v['id'] ?>" class="btn">✏️ تعديل</a>
                            <a href="tch_dashboard.php?delete_id=<?= $v['id'] ?>" class="btn btn-danger" onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الفيديو؟')">🗑️ حذف</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

