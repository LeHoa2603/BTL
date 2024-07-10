<?php
session_start();
include('includes/config.php');
error_reporting(0);
if(isset($_POST['signup']))
{
    // Code for student ID
    $count_my_page = ("studentid.txt");
    $hits = file($count_my_page);
    $hits[0]++;
    $fp = fopen($count_my_page, "w");
    fputs($fp, "$hits[0]");
    fclose($fp);
    $StudentId = $hits[0];
    $fname = $_POST['fullanme'];
    $mobilephone = $_POST['mobilephone'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $status = 1;
    $sql = "INSERT INTO tblstudents(StudentId, FullName, MobileNumber, EmailId, Password, Status) VALUES(:StudentId, :fname, :mobilephone, :email, :password, :status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':StudentId', $StudentId, PDO::PARAM_STR);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':mobilephone', $mobilephone, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId) {
        echo '<script>alert("Your Registration successful and your student id is  "+"'.$StudentId.'")</script>';
    } else {
        echo "<script>alert('Something went wrong. Please try again');</script>";
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
    <title>Online Library Management System | Student Signup</title>
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
            var password = document.signup.password.value;
            var confirmPassword = document.signup.confirmpassword.value;
            var mobile = document.signup.mobilephone.value;
            var isValid = true;

            // Clear previous error messages
            document.getElementById("password-error").innerHTML = "";
            document.getElementById("mobile-error").innerHTML = "";

            // Check if passwords match
            if (password != confirmPassword) {
                document.getElementById("password-error").innerHTML = "Password and Confirm Password do not match!";
                isValid = false;
            }

            // Check if mobile number is exactly 10 digits
            if (mobile.length != 10) {
                document.getElementById("mobile-error").innerHTML = "Mobile number must be exactly 10 digits.";
                isValid = false;
            }

            return isValid;
        }

        function checkAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data: 'emailid=' + $("#emailid").val(),
                type: "POST",
                success: function(data) {
                    $("#user-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error: function () {}
            });
        }
    </script>
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">User Signup</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            SIGNUP FORM
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="post" onSubmit="return valid();">
                                <div class="form-group">
                                    <label>Enter Full Name</label>
                                    <input class="form-control" type="text" name="fullanme" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>Mobile Number:</label>
                                    <input class="form-control" type="text" name="mobilephone" pattern="[0-9]{10}" title="Please enter exactly 10 digits" autocomplete="off" required />
                                    <div id="mobile-error" class="text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label>Enter Email</label>
                                    <input class="form-control" type="email" name="email" id="emailid" onBlur="checkAvailability()" autocomplete="off" required />
                                    <span id="user-availability-status" style="font-size:12px;"></span>
                                </div>
                                <div class="form-group">
                                    <label>Enter Password</label>
                                    <input class="form-control" type="password" name="password" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required />
                                    <div id="password-error" class="text-danger"></div>
                                </div>
                                <button type="submit" name="signup" class="btn btn-danger" id="submit">Register Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <script src="assets/js
    /jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
