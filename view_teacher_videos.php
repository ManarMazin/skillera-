<?php
require 'manar.php';  // اتصال بقاعدة البيانات

// استلام tea_id من الرابط
$tea_id = isset($_GET['tea_id']) && is_numeric($_GET['tea_id'])
          ? intval($_GET['tea_id'])
          : 0;

// إذا كان tea_id غير صالح، نظهر رسالة خطأ ويوقف الاستعلام
if ($tea_id <= 0) {
    $error = "❌ لم يتم تحديد معرّف المعلم أو أنه غير صالح.";
    $videos = [];
    $teacher_name = '';
} else {
    // جلب اسم المعلم
    $tstmt = mysqli_prepare($connect, "SELECT tea_name FROM teachers WHERE tea_id = ?");
    mysqli_stmt_bind_param($tstmt, 'i', $tea_id);
    mysqli_stmt_execute($tstmt);
    $tres = mysqli_stmt_get_result($tstmt);
    if (mysqli_num_rows($tres) === 0) {
        $error = "❌ المعلم غير موجود.";
        $videos = [];
        $teacher_name = '';
    } else {
        $teacher_name = mysqli_fetch_assoc($tres)['tea_name'];
        // جلب فيديوهات المعلم
        $vstmt = mysqli_prepare($connect, "
            SELECT course_id, course_name, category, level, title, description, filename, video_url, extra_file, uploaded_at
            FROM videos
            WHERE tea_id = ?
            ORDER BY uploaded_at DESC
        ");
        mysqli_stmt_bind_param($vstmt, 'i', $tea_id);
        mysqli_stmt_execute($vstmt);
        $vres = mysqli_stmt_get_result($vstmt);
        $videos = mysqli_fetch_all($vres, MYSQLI_ASSOC);
        $error = '';
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>فيديوهات المعلم</title>
  <style>
    body { font-family: Tahoma, sans-serif; background:#f0f2f5; direction:rtl; padding:30px; }
    h2 { text-align:center; color:#007BFF; }
    .back { text-align:center; margin-bottom:20px; }
    .back a { color:#007BFF; text-decoration:none; font-weight:bold; }
    .error { text-align:center; color:red; margin-top:50px; }
    .grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:20px; }
    .card { background:#fff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1); overflow:hidden; }
    .card-header { background:#007BFF; color:#fff; padding:10px; font-size:18px; }
    .card-body { padding:15px; }
    .meta, .description { margin-bottom:10px; color:#555; }
    video { width:100%; margin-top:10px; border-radius:4px; }
    .link a, .extra a { display:inline-block; margin-top:8px; font-weight:bold; }
    .link a { color:#007BFF; } .extra a { color:#28a745; }
    .link a:hover, .extra a:hover { text-decoration:underline; }
    .no-videos { text-align:center; color:#777; margin-top:40px; }
  </style>
</head>
<body>

  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
    <p class="back"><a href="teachers_list.php">← العودة لقائمة المعلمين</a></p>
    <?php exit(); ?>
  <?php endif; ?>

  <h2>فيديوهات المعلم: <?= htmlspecialchars($teacher_name) ?></h2>
  <p class="back"><a href="teachers_list.php">← العودة لقائمة المعلمين</a></p>

  <?php if (empty($videos)): ?>
    <p class="no-videos">لا توجد فيديوهات مضافة بعد لهذا المعلم.</p>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($videos as $v): ?>
        <div class="card">
          <div class="card-header"><?= htmlspecialchars($v['title']) ?></div>
          <div class="card-body">
            <div class="meta">
              <div>📘 الدورة: <?= htmlspecialchars($v['course_name']) ?> (ID: <?= $v['course_id'] ?>)</div>
              <div>🗂️ تخصص: <?= htmlspecialchars($v['category']) ?> | مستوى: <?= htmlspecialchars($v['level']) ?></div>
              <div>📅 تاريخ الرفع: <?= date("Y-m-d", strtotime($v['uploaded_at'])) ?></div>
            </div>
            <div class="description"><?= nl2br(htmlspecialchars($v['description'])) ?></div>

            <?php if ($v['filename']): ?>
              <video controls>
                <source src="uploads/<?= htmlspecialchars($v['filename']) ?>" type="video/mp4">
              </video>
            <?php elseif ($v['video_url']): ?>
              <p class="link"><a href="<?= htmlspecialchars($v['video_url']) ?>" target="_blank">▶️ مشاهدة الفيديو</a></p>
            <?php endif; ?>

            <?php if ($v['extra_file']): ?>
              <p class="extra"><a href="uploads/<?= htmlspecialchars($v['extra_file']) ?>" target="_blank">📎 تحميل الملف الإضافي</a></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</body>
</html>
