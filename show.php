<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>صفحة البطاقات</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f0f0f0;
      margin: 0;
      padding: 40px;
      direction: rtl;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
    }

    .card {
      background-color: white;
      border-right: 8px solid #03a9f4;
      border-radius: 15px;
      padding: 30px 20px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      text-decoration: none;
      color: #333;
      transition: transform 0.3s ease;
      height: 300px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-title {
      font-size: 24px;
      font-weight: bold;
      color:#03a9f4 ;
      margin-bottom: 15px;
      text-align: center;
    }

    .card-desc {
      font-size: 16px;
      color: #555;
      text-align: center;
    }

    @media (max-width: 992px) {
      .container {
        grid-template-columns: 1fr 1fr;
      }
    }

    @media (max-width: 600px) {
      .container {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <a href="index.html" class="card">
      <div class="card-title">skillera</div>
      <div class="card-desc"> منصة تركز على تنمية المهارات وتوفير التعليم والتدريب في مجالات مختلفة    </div>
    </a>

    <a href="admin.php" class="card">
      <div class="card-title">Admin system</div>
      <div class="card-desc">نظام خاص للمدراء حيث يعرض كافة معلومات الاساتذة والطلاب والمستخدمين</div>
    </a>

    <a href="tch_dashboard.php" class="card">
      <div class="card-title">Teacher dashboard </div>
      <div class="card-desc">خاص للاساتذة حيث يتمكن الاستاذ من رفع المحاضرات والدروس وبصيغة ملف او فديو او رابط</div>
    </a>
  </div>

</body>
</html>

