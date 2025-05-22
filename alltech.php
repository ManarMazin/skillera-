<?php
    session_start();
    
   

    require "manar.php";
    global $connect;
    mysqli_set_charset($connect, 'utf8');

    $showdb = "SELECT * FROM teachers";
    $showdbRun = mysqli_query($connect, $showdb);
    
    if(@$_GET['D']=='D'){
        $tokenproject = @$_GET['T'];
        $DD= "DELETE FROM teachers WHERE tea_token='$tokenproject'";
        $DDRun = mysqli_query($connect,$DD);
        echo'
            <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                <img width="200" src="11.png" />
                <p>شكراً لك تم الحذف بنجاح</p>
            </div>
            <meta http-equiv="refresh" content="3, url=admin.php"/>
        ';
        exit();
    }
?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الأساتذة</title>
    <link href="style.css" rel="stylesheet"/>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="fontawesome/css/brands.css" rel="stylesheet" />
    <link href="fontawesome/css/solid.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/duotone-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-duotone-thin.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <header>
            <h1>عرض الكادر التدريسي</h1>
            <a class="back-btn" href="admin.php"><i class="fa-solid fa-arrow-left"></i> العودة</a>
        </header>

        <table class="teachers-table">
            <thead>
                <tr>
                    <th>ت</th>
                    <th>اسم الأستاذ</th>
                    <th>إيميل الأستاذ</th>
                    <th>اختصاص الأستاذ</th>
                    <th>السيرة الذاتية</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $number = 1;
                    while ($showdbRow = mysqli_fetch_array($showdbRun)) {
                        echo '
                            <tr>
                                <td>' . $number++ . '</td>
                                <td>' . htmlspecialchars($showdbRow['tea_name']) . '</td>
                                <td>' . htmlspecialchars($showdbRow['tea_email']) . '</td>
                                <td>' . htmlspecialchars($showdbRow['tea_cat']) . '</td>
                                <td>';
                        
                        if (!empty($showdbRow['tea_cv'])) {
                            echo '<a href="uploads/cv/' . htmlspecialchars($showdbRow['tea_cv']) . '" target="_blank">📎 تحميل</a>';
                        } else {
                            echo 'لا يوجد';
                        }

                        echo '</td>
                                <td><a class="btn edit-btn" href="tch_edit.php?T=' . htmlspecialchars($showdbRow['tea_token']) . '">تعديل</a></td>
                                <td><a class="btn delete-btn" href="alltech.php?D=D&T=' . htmlspecialchars($showdbRow['tea_token']) . '">حذف</a></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
