<?php

    require"manar.php";
global $connect;
mysqli_set_charset($connect,'utf8');
@$post01=$_POST['get01'];
@$post02=$_POST['get02'];
@$post03=$_POST['get03'];
@$post05=$_POST['get05'];



$Token=date('ymdhis');
$Rand=rand(100,200);
$NewToken=$Token.$Rand;

if(isset($post05)){
   

$sent = "INSERT INTO student
(
    st_token,
    st_name,
    st_age,
    st_line
) VALUES (
    '$NewToken',
    '$post01',
    '$post02',
    '$post03'
)";

    if(mysqli_query($connect, $sent)){
        echo'
            <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                <img width="200" src="11.png" />
                <p>شكراً لك تم اضافة الطالب بنجاح</p>
            </div>
            <meta http-equiv="refresh" content="3, url=admin.php"/>
        ';
        exit();
    }
}
  
  
            //setcookie("Token",$NewToken,time()+(86400),"/");
            //setcookie("Name",$post01,time()+(86400),"/");
            //setcookie("islogin","yes",time()+(86400),"/");








?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اضافة الطلاب</title>
    <link href="style.css" rel="stylesheet"/>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="fontawesome/css/brands.css" rel="stylesheet" />
    <link href="fontawesome/css/solid.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/duotone-thin.css" rel="stylesheet" />
    <link href="fontawesome/css/sharp-duotone-thin.css" rel="stylesheet" />
</head>
<body class="style18">
<table class="style12">

        <tr>
            <td>اضافة الطلاب</td>
            <td><a class="style13" href="admin.php"><i class="fa-solid fa-arrow-left"></i></a></td>
        </tr>
    </table>
</br>
</br>
    <form method="post" action="" enctype="multipart/form-data">
    <div class="style01">
</br><input name="get01" class="style02" type="text" placeholder="اكتب اسم الطالب"/>
</br> <input name="get02" class="style02" type="text" placeholder="اكتب مواليد الطالب"/>
</br><input name="get03" class="style02" type="text" placeholder="مسار او تخصص الطالب الدراسي "/></br>
</div>

        <div class="style03">
            <div class="top"><input name="get05" class="style08" type="submit" value="اضافة الطالب"/></div>
        </div>
    </form>
</body>

</table>

</body>
</html>
