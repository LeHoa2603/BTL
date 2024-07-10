<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
} else {
    if(isset($_POST['change'])) {
        $password = md5($_POST['password']);
        $newpassword = md5($_POST['newpassword']);
        $email = $_SESSION['login'];
        $sql = "SELECT Password FROM tblstudents WHERE EmailId=:email and Password=:password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if($query->rowCount() > 0) {
            if($_POST['newpassword'] == $_POST['confirmpassword']) {
                $con = "UPDATE tblstudents SET Password=:newpassword WHERE EmailId=:email";
                $chngpwd1 = $dbh->prepare($con);
                $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
                $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
                $chngpwd1->execute();
                $msg = "Your Password successfully changed";
            } else {
                $error = "New Password and Confirm Password do not match!";
            }
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
        .succWrap{
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .passwordMismatch {
            color: #dd3d36;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">User Change Password</h4>
                </div>
            </div>
            <?php if(isset($error)): ?>
                <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?></div>
            <?php elseif(isset($msg)): ?>
                <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">Change Password</div>
                        <div class="panel-body">
                            <form role="form" method="post" name="chngpwd">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input class="form-control" type="password" name="password" autocomplete="off" value="<?php echo htmlentities($_POST['password']); ?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Enter New Password</label>
                                    <input class="form-control" type="password" name="newpassword" autocomplete="off" value="<?php echo isset($_POST['newpassword']) ? $_POST['newpassword'] : ''; ?>" required />
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input class="form-control"  type="password" name="confirmpassword" autocomplete="off" value="<?php echo isset($_POST['confirmpassword']) ? $_POST['confirmpassword'] : ''; ?>" required />
                                    <?php if(isset($error) && $error == "New Password and Confirm Password do not match!"): ?>
                                        <div class="passwordMismatch">New Password and Confirm Password do not match!</div>
                                    <?php endif; ?>
                                </div>
                                <button type="submit" id="changebutton" name="change" class="btn btn-info">Change</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
