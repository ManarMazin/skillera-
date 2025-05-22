<?php
 

    require "manar.php";
    global $connect;
    mysqli_set_charset($connect, 'utf8');

    $showdb = "SELECT * FROM student";
    $showdbRun = mysqli_query($connect, $showdb);
    
    if(@$_GET['D']=='D'){
        $tokenproject = @$_GET['T'];
        $DD= "DELETE FROM student WHERE st_token='$tokenproject'";
        $DDRun = mysqli_query($connect,$DD);
        echo'
            <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                <img width="200" src="11.png" />
                <p>شكراً لك تم حذف الطالب  بنجاح</p>
            </div>
            <meta http-equiv="refresh" content="3, url=allstu.php"/>
        ';
        exit();
    }
?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معلومات الطالب</title>
    <link href="style.css" rel="stylesheet"/>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="fontawesome/css/brands.css" rel="stylesheet" />
    <link href="fontawesome/css/solid.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/duotone-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-duotone-thin.css" rel="stylesheet" />
</head>
<body class="style20">
<a href="admin.php">
 

<div class="container"> <button class="btn-top" >اضغط هنا</button></div>
</a>

    <table class="style20">
        <tr>
            <th>ت</th>
            <th>الاسم</th>
            <th>المواليد</th>
            <th>تعديل</th>
            <th>حذف</th>

    </tr>
    <?php
            $number=1;
            while($showdbRow = mysqli_fetch_array($showdbRun)){
                echo'
                    <tr>
                        <td>'.$number++.'</td>
                        <td>'.$showdbRow['st_name'].'</td>
                        <td>'.$showdbRow['st_age'].'</td>
                        <td><a class="style020" href="edit.php?T='.$showdbRow['st_token'].'">تعديل</a></td>
                        <td><a class="style020" href="allstu.php?D=D&T='.$showdbRow['st_token'].'">حذف</a></td>
                    </tr>
                ';
            }
        ?>
    </table>
 
</body>
</html>
