<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if(isset($_POST['change'])) {
        $password = md5($_POST['password']);
        $newpassword = md5($_POST['newpassword']);
        $username = $_SESSION['alogin'];

        $sql ="SELECT Password FROM admin WHERE UserName=:username AND Password=:password";
        $query= $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount() > 0) {
            $con = "UPDATE admin SET Password=:newpassword WHERE UserName=:username";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':username', $username, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();
            $msg = "Your Password successfully changed";
        } else {
            $error = "Your current password is wrong";
        }
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Change Password</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .form-error {
            color: red;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Admin Change Password</h4>
                </div>
            </div>
            <?php if(isset($error)){?>
                <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
            <?php } else if(isset($msg)){?>
                <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
            <?php }?>
            <!--LOGIN PANEL START-->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Change Password
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" name="chngpwd" onsubmit="return validatePassword()">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input class="form-control" type="password" name="password" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input class="form-control" type="password" name="newpassword" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required />
                                    <div id="password-error" class="form-error"></div>
                                </div>
                                <button type="submit" id="change" name="change" class="btn btn-info">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--LOGIN PANEL END-->
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    <script>
        function validatePassword() {
            var newPassword = document.forms["chngpwd"]["newpassword"].value;
            var confirmPassword = document.forms["chngpwd"]["confirmpassword"].value;
            var passwordError = document.getElementById("password-error");

            if (newPassword != confirmPassword) {
                passwordError.textContent = "New Password and Confirm Password fields do not match!";
                return false;
            } else {
                passwordError.textContent = "";
                return true;
            }
        }
    </script>
</body>
</html>
