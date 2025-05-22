<?php
    session_start();
    if(@$_SESSION['islogin'] == 'yes'){
        echo'<meta http-equiv="refresh" content="0, url=index.html"/>';
        exit();
    }
    require"manar.php";
global $connect;
mysqli_set_charset($connect,'utf8');
@$post01=$_POST['get01'];
@$post02=$_POST['get02'];
@$post05=$_POST['get05'];



$Token=date('ymdhis');
$Rand=rand(100,200);
$NewToken=$Token.$Rand;



    $tokenproject = @$_GET['T'];
    $chang = "SELECT * FROM student WHERE st_token='$tokenproject'";
    $changRun = mysqli_query($connect, $chang);
    $changRow = mysqli_fetch_array($changRun);

    if(isset($post05)){
        
        $update= "UPDATE student SET
            st_name='$post01',
            st_age='$post02'
         

            WHERE st_token='$tokenproject'
        ";
        if(mysqli_query($connect, $update)){
            echo'
                <div style="text-align:center;color:#080;margin: 50px auto;font-weight:bold;">
                    <img width="200" src="11.png" />
                    <p>شكراً لك تم تحديث البيانات بنجاح</p>
                </div>
                <meta http-equiv="refresh" content="3, url=allstu.php"/>
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
    <title>تعديل بيانات </title>
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
            <td>تعديل </td>
            <td><a class="style13" href="allstu.php"><i class="fa-solid fa-arrow-left"></i></a></td>
        </tr>
    </table>
    <form method="post" action="" enctype="multipart/form-data">
    <div class="style01">
   
            <input value="<?php echo $changRow['st_name']; ?>" name="get01" class="style02" type="text" placeholder="اكتب الاسم"/>
            <input value="<?php echo $changRow['st_age']; ?>" name="get02" class="style02" type="text" placeholder="اكتب المواليد"/>
          
        </div>
        <div class="style03">
            <div class="top"><input name="get05" class="style08" type="submit" value="تعديل "/></div>
        </div>
    </form>
</body>

</table>

</body>
</html>
