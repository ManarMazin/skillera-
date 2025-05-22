<?php
session_start();
require 'manar.php';

if (!isset($_SESSION['teacher_logged_in']) || $_SESSION['teacher_logged_in'] !== true) {
    header("Location: tch_login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'] ?? 0;

// استقبال course_id من الرابط
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

if ($course_id === 0) {
    die("❌ لم يتم تحديد معرف الدورة.");
}

$message = "";

// عند إرسال الفورم
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $level = $_POST['level'] ?? '';
    $video_url = $_POST['video_url'] ?? '';
    $filename = '';

    // التحقق من أن كل الحقول ممتلئة
    if (!$title || !$description || !$category || !$level || (!$video_url && $_FILES['video_file']['error'] != 0)) {
        $message = "❌ الرجاء ملء جميع الحقول المطلوبة.";
    } else {
        // إذا تم رفع ملف فيديو
        if ($_FILES['video_file']['error'] == 0) {
            $filename = time() . '_' . basename($_FILES['video_file']['name']);
            move_uploaded_file($_FILES['video_file']['tmp_name'], 'uploads/' . $filename);
        }

        $stmt = mysqli_prepare($connect, "INSERT INTO videos 
        (tea_id, course_id, title, description, category, level, filename, video_url, uploaded_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        if (!$stmt) {
            die("❌ فشل تجهيز الاستعلام: " . mysqli_error($connect));
        }

        mysqli_stmt_bind_param($stmt, "iissssss", $tea_id, $course_id, $title, $description, $category, $level, $filename, $video_url);
        mysqli_stmt_execute($stmt);

        $message = "✅ تم رفع الفيديو بنجاح!";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>رفع فيديو تعليمي</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            direction: rtl;
            padding: 30px;
        }
        .upload-form {
            max-width: 700px;
            margin: auto;
            background: white;
            border: 2px solid #007BFF;
            border-radius: 12px;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #007BFF;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="file"] {
            margin-top: 10px;
        }
        .submit-btn {
            margin-top: 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            cursor: pointer;
        }
        .msg {
            margin-top: 15px;
            font-weight: bold;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="upload-form">
        <h2>📤 رفع فيديو تعليمي</h2>
        <?php if ($message): ?>
            <p class="msg"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>عنوان الفيديو</label>
            <input type="text" name="title" required>

            <label>وصف الفيديو</label>
            <textarea name="description" rows="3" required></textarea>

            <label>التخصص</label>
            <input type="text" name="category" required>

            <label>المستوى</label>
            <input type="text" name="level" required>

            <label>رابط خارجي للفيديو (اختياري)</label>
            <input type="text" name="video_url">

            <label>أو اختر ملف فيديو</label>
            <input type="file" name="video_file" accept="video/*">

            <button type="submit" class="submit-btn">رفع الفيديو</button>
        </form>
    </div>
</body>
</html>
