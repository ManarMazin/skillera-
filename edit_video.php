<?php
session_start();
require 'manar.php';  // اتصال بقاعدة البيانات

// التحقق من تسجيل دخول المعلم
if (!isset($_SESSION['teacher_logged_in']) || $_SESSION['teacher_logged_in'] !== true) {
    header('Location: tch_login.php');
    exit();
}

if (isset($_GET['id'])) {
    $video_id = $_GET['id'];

    // التحقق من وجود الفيديو
    $sql = "SELECT * FROM videos WHERE id = ? AND tea_id = ?";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $video_id, $_SESSION['teacher_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($video = mysqli_fetch_assoc($result)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // معالجة تعديل الفيديو
            $title = $_POST['title'];
            $course_name = $_POST['course_name'];
            $category = $_POST['category'];
            $level = $_POST['level'];
            $video_url = $_POST['video_url'];
            $filename = $_FILES['video_file']['name'];
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($filename);

            // إذا تم رفع ملف جديد، قم بحفظه
            if (move_uploaded_file($_FILES['video_file']['tmp_name'], $target_file)) {
                // تعديل الفيديو في قاعدة البيانات
                $update_sql = "UPDATE videos SET title = ?, course_name = ?, category = ?, level = ?, video_url = ?, filename = ? WHERE id = ?";
                $update_stmt = mysqli_prepare($connect, $update_sql);
                mysqli_stmt_bind_param($update_stmt, 'ssssssi', $title, $course_name, $category, $level, $video_url, $filename, $video_id);
            } else {
                // إذا لم يتم رفع ملف جديد، قم بتحديث باقي البيانات فقط
                $update_sql = "UPDATE videos SET title = ?, course_name = ?, category = ?, level = ?, video_url = ? WHERE id = ?";
                $update_stmt = mysqli_prepare($connect, $update_sql);
                mysqli_stmt_bind_param($update_stmt, 'sssssi', $title, $course_name, $category, $level, $video_url, $video_id);
            }

            if (mysqli_stmt_execute($update_stmt)) {
                header('Location:tch_dashboard.php');
                exit();
            } else {
                echo "حدث خطأ أثناء التعديل.";
            }
        }
    } else {
        echo "الفيديو غير موجود.";
    }
} else {
    echo "لم يتم العثور على الفيديو.";
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل الفيديو</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
            direction: rtl;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 26px;
            color: #007BFF;
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            display: block;
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: background-color 0.3s ease;
        }
        input:focus, select:focus {
            border-color: #007BFF;
            background-color: #e6f0ff;
            outline: none;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            padding: 15px 25px;
            font-size: 18px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        input[type="file"] {
            font-size: 16px;
            padding: 10px;
        }
        .note {
            font-size: 14px;
            color: #555;
            margin-top: 10px;
            text-align: center;
        }
        .note a {
            color: #007BFF;
            text-decoration: none;
        }
        .note a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>تعديل الفيديو</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">العنوان:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($video['title']) ?>" required>

        <label for="course_name">اسم الدورة:</label>
        <input type="text" id="course_name" name="course_name" value="<?= htmlspecialchars($video['course_name']) ?>" required>

        <label for="category">التخصص:</label>
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($video['category']) ?>" required>

        <label for="level">المستوى:</label>
        <input type="text" id="level" name="level" value="<?= htmlspecialchars($video['level']) ?>" required>

        <label for="video_url">رابط الفيديو:</label>
        <input type="text" id="video_url" name="video_url" value="<?= htmlspecialchars($video['video_url']) ?>">

        <label for="video_file">رفع فيديو جديد (اختياري):</label>
        <input type="file" id="video_file" name="video_file">

        <input type="submit" value="تعديل">
    </form>

    <p class="note">
        إذا كنت لا ترغب في تغيير الفيديو، فقط قم بتعديل باقي البيانات واضغط "تعديل".
    </p>
</div>

</body>
</html>
