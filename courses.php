<?php
session_start();
if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require "manar.php";
mysqli_set_charset($connect, 'utf8');

// استعلام الدورات الصيفية
$summer_videos = mysqli_query($connect, "SELECT * FROM videos WHERE category LIKE '%صيف%' ORDER BY uploaded_at DESC");

// استعلام المناهج الدراسية
$school_videos = mysqli_query($connect, "SELECT * FROM videos WHERE category NOT LIKE '%صيف%' ORDER BY category, level, uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>الكورسات التعليمية</title>
  <style>
    body {
      font-family: 'Tahoma', sans-serif;
      background-color: #f0f0f0;
      margin: 0;
      direction: rtl; /* تحديد الاتجاه من اليمين لليسار */
      text-align: right; /* محاذاة النص إلى اليمين */
    }

    /* شريط التنقل */
    .navbar {
      background-color: #03a9f4;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px;
      color: white;
    }

    .navbar img {
      width: 100px;
      height: 50px;
      border-radius: 8px;
      object-fit: cover;
      background-color: transparent;
      border: none;
    }

    .navbar-title {
      font-size: 30px;
      font-weight: bold;
      text-align: right;
      flex-grow: 1;
    }

    /* البطاقات */
    .container {
      padding: 30px;
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    .card {
      background-color: white;
      padding: 25px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 300px;
      text-align: center;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: scale(1.05);
      background-color: #e0f7fa;
    }

    /* الأقسام المخفية */
    .section {
      display: none;
      padding: 30px;
    }

    .section h2 {
      background-color: #03a9f4;
      color: white;
      padding: 12px;
      border-radius: 10px;
    }

    .video-card {
      background-color: white;
      padding: 15px;
      margin: 15px 0;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .video-title {
      color: #03a9f4;
      font-weight: bold;
    }

    .video-meta {
      color: #777;
      font-size: 13px;
    }

    .video-description {
      margin: 10px 0;
      font-size: 15px;
      color: #333;
    }

    /* زر الرجوع */
    .back-button {
      background-color: #03a9f4;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      margin-bottom: 20px;
    }

    .back-button:hover {
      background-color: #0288d1;
    }
  </style>
</head>
<body>

  <!-- شريط التنقل مع الشعار -->
  
</div>

  </style>
</head>
<body>

  <!-- شريط التنقل -->
  <div class="navbar">
    <a href="index.html">
      <img src="assets/images/logo.jpg" alt="Logo">
    </a>
   <div class="navbar">
    
    <div class="navbar-title">الكورسات التعليمية</div>
  </div>
  </div>
 
</head>
<body>



<!-- البطاقات الرئيسية -->
<div class="container">
  <div class="card" onclick="showSection('school')">
    <h3>📘 المناهج الدراسية</h3>
    <p>عرض الفيديوهات حسب التصنيفات الدراسية</p>
  </div>

  <div class="card" onclick="showSection('summer')">
    <h3>📚 الدورات الصيفية</h3>
    <p>عرض الفيديوهات الخاصة بالدورات الصيفية</p>
  </div>
</div>

<!-- قسم المناهج الدراسية -->
<div id="school" class="section">
  <h2>📘 المناهج الدراسية</h2>
  <?php while($video = mysqli_fetch_assoc($school_videos)): ?>
    <div class="video-card">
      <div class="video-title"><?= htmlspecialchars($video['title']) ?></div>
      <div class="video-meta"><?= htmlspecialchars($video['category']) ?> | <?= htmlspecialchars($video['level']) ?> | <?= htmlspecialchars($video['uploaded_at']) ?></div>
      <div class="video-description"><?= htmlspecialchars($video['description']) ?></div>
      <a class="button" href="<?= htmlspecialchars($video['video_url']) ?>" target="_blank">مشاهدة</a>
    </div>
  <?php endwhile; ?>
</div>

<!-- قسم الدورات الصيفية -->
<div id="summer" class="section">
  <h2>📚 الدورات الصيفية</h2>
  <?php while($video = mysqli_fetch_assoc($summer_videos)): ?>
    <div class="video-card">
      <div class="video-title"><?= htmlspecialchars($video['title']) ?></div>
      <div class="video-meta"><?= htmlspecialchars($video['course_name']) ?> | <?= htmlspecialchars($video['uploaded_at']) ?></div>
      <div class="video-description"><?= htmlspecialchars($video['description']) ?></div>
      <a class="button" href="<?= htmlspecialchars($video['video_url']) ?>" target="_blank">مشاهدة</a>
    </div>
  <?php endwhile; ?>
</div>

<!-- سكربت العرض -->
<script>
  function showSection(sectionId) {
    document.getElementById('school').style.display = 'none';
    document.getElementById('summer').style.display = 'none';
    document.getElementById(sectionId).style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
</script>

</body>
</html>
