<?php
require 'manar.php';
mysqli_set_charset($connect, 'utf8');

// جلب المستخدمين من قاعدة البيانات
$query = "SELECT user_name, user_birthday, user_email FROM users";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قائمة المستخدمين</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f0f0f0;
            direction: rtl;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: rgb(92, 118, 156);
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 188, 212, 0.1);
            overflow: hidden;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: rgb(92, 118, 156);
            color: #fff;
        }

        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h2><i class="fa-solid fa-users"></i> بيانات المستخدمين</h2>

<table>
    <thead>
        <tr>
            <th>الاسم</th>
            <th>المواليد</th>
            <th>البريد الإلكتروني</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['user_birthday']) ?></td>
                    <td><?= htmlspecialchars($row['user_email']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">لا يوجد مستخدمون في قاعدة البيانات.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
