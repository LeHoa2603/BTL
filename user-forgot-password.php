<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(isset($_POST['change']))
{
    //code for captach verification

    $email=$_POST['email'];
    $mobile=$_POST['mobile'];
    $newpassword=md5($_POST['newpassword']);
    $sql ="SELECT EmailId FROM tblstudents WHERE EmailId=:email and MobileNumber=:mobile";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':email', $email, PDO::PARAM_STR);
    $query-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query-> execute();
    $results = $query -> fetchAll(PDO::FETCH_OBJ);
    if($query -> rowCount() > 0)
    {
        $con="update tblstudents set Password=:newpassword where EmailId=:email and MobileNumber=:mobile";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1-> bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();
        $success_message = "Your Password successfully changed";
    }
    else {
        $error_message = "Email id or Mobile no is invalid";
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
    <title>Online Library Management System | Password Recovery </title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script type="text/javascript">
        function valid() {
            if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
                document.getElementById("error-message").innerHTML = "New Password and Confirm Password Field do not match!";
                document.chngpwd.confirmpassword.focus();
                return false;
            }
            return true;
        }
        function validatePhoneInput(event) {
            var input = event.target;
            var value = input.value;
            // Remove any non-digit characters
            input.value = value.replace(/\D/g, '');
        }
    </script>
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">User Password Recovery</h4>
                </div>
            </div>

            <!--LOGIN PANEL START-->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" >
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            LOGIN FORM
                        </div>
                        <div class="panel-body">
                            <form role="form" name="chngpwd" method="post" onSubmit="return valid();">
                                <?php if (isset($success_message)) { ?>
                                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                                <?php } ?>
                                <?php if (isset($error_message)) { ?>
                                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                                <?php } ?>
                                <div id="error-message" class="alert alert-danger" style="display:none;"></div>
                                <div class="form-group">
                                    <label>Enter Reg Email id</label>
                                    <input class="form-control" type="email" name="email" required autocomplete="off" />
                                </div>

                                <div class="form-group">
                                    <label>Enter Reg Mobile No</label>
                                    <input class="form-control" type="tel" name="mobile" pattern="\d{10}" title="Please enter exactly 10 digits" required autocomplete="off" oninput="validatePhoneInput(event)" />
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="newpassword" required autocomplete="off" />
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input class="form-control" type="password" name="confirmpassword" required autocomplete="off" />
                                </div>

                                <button type="submit" name="change" class="btn btn-info">Change Password</button> | <a href="index.php">Login</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!---LOGIN PANEL END-->
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
</body>
</html>
