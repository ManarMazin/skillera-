<?php
session_start();
require 'manar.php';

$teacher_id = $_SESSION['teacher_id']; // معرف المعلم من الجلسة

// استعلام لجلب الدورات التي يُدرسها المعلم
$query_courses = "SELECT * FROM courses WHERE teacher_id = '$teacher_id'";
$result_courses = mysqli_query($connect, $query_courses);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>الطلاب في الدورات</title>
  <style>
    /* تصميم الصفحة */
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f7fc;
      margin: 0;
      padding: 20px;
    }
    h3 {
      color: #0288d1;
    }
    .course {
      background-color: white;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      border-radius: 8px;
    }
    .student-list {
      margin-top: 20px;
    }
    .student-list p {
      font-size: 14px;
      color: #333;
    }
  </style>
</head>
<body>

<?php while ($course = mysqli_fetch_assoc($result_courses)): ?>
  <div class="course">
    <h3>دورة: <?= htmlspecialchars($course['course_name']) ?></h3>
    
    <?php
    $course_id = $course['course_id'];
    $query_students = "SELECT students.st_name FROM students 
                       INNER JOIN student_courses ON students.st_id = student_courses.st_id
                       WHERE student_courses.course_id = '$course_id'";
    $result_students = mysqli_query($connect, $query_students);
    ?>
    
    <div class="student-list">
      <h4>الطلاب:</h4>
      <?php while ($student = mysqli_fetch_assoc($result_students)): ?>
        <p><?= htmlspecialchars($student['st_name']) ?></p>
      <?php endwhile; ?>
    </div>
  </div>
<?php endwhile; ?>

</body>
</html>
