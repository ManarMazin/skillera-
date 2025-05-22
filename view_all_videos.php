
<?php
session_start();
require 'manar.php';

// إذا لم يأتِ tea_id فندخله على القائمة
if (!isset($_GET['tea_id']) || !is_numeric($_GET['tea_id'])) {
    header('Location: teachers_list.php');
    exit();
}
// تابع عرض الفيديوهات...


// 1. جلب كل الفيديوهات مع بيانات الدورة والمعلم
$sql = "
  SELECT 
    v.id,
    v.course_id,
    v.course_name,
    v.category,
    v.level,
    v.title,
    v.description,
    v.filename,
    v.video_url,
    v.extra_file,
    v.uploaded_at,
    t.tea_name
  FROM videos v
  LEFT JOIN teachers t ON v.tea_id = t.tea_id
  ORDER BY v.uploaded_at DESC
";
$result = mysqli_query($connect, $sql);
if (!$result) {
    die("❌ خطأ في الاستعلام: " . mysqli_error($connect));
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>جميع الفيديوهات التعليمية</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f2f5;
      direction: rtl;
      padding: 30px;
    }
    h2 {
      text-align: center;
      color: #007BFF;
      margin-bottom: 20px;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
    }
    .card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .card-header {
      background: #007BFF;
      color: #fff;
      padding: 10px;
      font-size: 18px;
    }
    .card-body {
      padding: 15px;
      flex: 1;
    }
    .meta span {
      display: block;
      margin-bottom: 5px;
      color: #555;
    }
    .description {
      margin: 10px 0;
      color: #444;
      font-size: 14px;
      line-height: 1.4;
    }
    video {
      width: 100%;
      border-radius: 4px;
      margin-top: 10px;
    }
    .link a {
      display: inline-block;
      margin-top: 10px;
      color: #007BFF;
      text-decoration: none;
      font-weight: bold;
    }
    .link a:hover {
      text-decoration: underline;
    }
    .extra a {
      display: inline-block;
      margin-top: 8px;
      color: #28a745;
      text-decoration: none;
    }
    .extra a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<h2>📚 جميع الفيديوهات التعليمية</h2>

<?php if (mysqli_num_rows($result) === 0): ?>
  <p style="text-align:center; color:#777;">لا توجد فيديوهات مضافة بعد.</p>
<?php else: ?>
  <div class="grid">
    <?php while ($v = mysqli_fetch_assoc($result)): ?>
      <div class="card">
        <div class="card-header"><?= htmlspecialchars($v['title']) ?></div>
        <div class="card-body">
          <div class="meta">
            <span>👨‍🏫 المعلم: <?= htmlspecialchars($v['tea_name'] ?? 'غير محدد') ?></span>
            <span>📘 الدورة: <?= htmlspecialchars($v['course_name']) ?> (ID: <?= $v['course_id'] ?>)</span>
            <span>🗂️ تخصص: <?= htmlspecialchars($v['category']) ?> | مستوى: <?= htmlspecialchars($v['level']) ?></span>
            <span>📅 تاريخ الرفع: <?= date("Y-m-d", strtotime($v['uploaded_at'])) ?></span>
          </div>
          <div class="description"><?= nl2br(htmlspecialchars($v['description'])) ?></div>

          <?php if ($v['filename']): ?>
            <video controls>
              <source src="uploads/<?= htmlspecialchars($v['filename']) ?>" type="video/mp4">
              متصفحك لا يدعم تشغيل الفيديو.
            </video>
          <?php elseif ($v['video_url']): ?>
            <div class="link">
              <a href="<?= htmlspecialchars($v['video_url']) ?>" target="_blank">▶️ مشاهدة الفيديو</a>
            </div>
          <?php endif; ?>

          <?php if ($v['extra_file']): ?>
            <div class="extra">
              <a href="uploads/<?= htmlspecialchars($v['extra_file']) ?>" target="_blank">📎 تحميل الملف الإضافي</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
<?php endif; ?>

</body>
</html>
