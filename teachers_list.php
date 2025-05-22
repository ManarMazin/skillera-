<?php
require 'manar.php';  // تأكد أن هذا يعرّف $connect

// جلب جميع المعلمين
$res = mysqli_query($connect, "SELECT tea_id, tea_name FROM teachers ORDER BY tea_name");
if (!$res) {
    die("❌ خطأ في الاستعلام: " . mysqli_error($connect));
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>قائمة المعلمين</title>
  <style>
    body { font-family: Tahoma, sans-serif; background:#f0f2f5; direction:rtl; padding:30px; }
    h2 { text-align:center; color:#007BFF; }
    ul { list-style:none; padding:0; max-width:400px; margin:20px auto; }
    li { background:#fff; margin:8px 0; padding:12px 20px; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    a { color:#007BFF; text-decoration:none; font-weight:bold; }
    a:hover { text-decoration:underline; }
    .back-button {
      display: inline-block;
      background-color:rgb(71, 151, 221);
      color: #fff;
      padding: 20px 100px;
      border-radius: 8px;
      text-decoration: none;
      margin-bottom: 30px;
      transition: background-color 0.3s;
    }
    .back-button:hover {
      background-color:rgb(144, 201, 245);
    }
    .header-bar {
      max-width: 400px;
      margin: 0 auto 20px;
      display: flex;
      justify-content: flex-end;
    }
  </style>
</head>
<body>

  <div class="header-bar">
    <a class="back-button" href="admin.php">🔙 رجوع إلى لوحة التحكم</a>
  </div>

  <h2>📋 قائمة المعلمين</h2>
  <ul>
    <?php while ($t = mysqli_fetch_assoc($res)): ?>
      <li>
        <a href="view_teacher_videos.php?tea_id=<?= intval($t['tea_id']) ?>">
          <?= htmlspecialchars($t['tea_name']) ?> (ID: <?= intval($t['tea_id']) ?>)
        </a>
      </li>
    <?php endwhile; ?>
  </ul>

</body>
</html>

