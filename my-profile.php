<?php
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    if(isset($_POST['update'])) {
        $sid = $_SESSION['stdid'];
        $fname = $_POST['fullanme'];
        $mobilephone = $_POST['mobilephone'];

        // Kiểm tra số điện thoại hợp lệ
        if(strlen($mobilephone) != 10 || !ctype_digit($mobilephone)) {
            $error = "Mobile number must be exactly 10 digits.";
        } else {
            $sql = "UPDATE tblstudents SET FullName = :fname, MobileNumber = :mobilephone WHERE StudentId = :sid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':sid', $sid, PDO::PARAM_STR);
            $query->bindParam(':fname', $fname, PDO::PARAM_STR);
            $query->bindParam(':mobilephone', $mobilephone, PDO::PARAM_STR);
            $query->execute();

            $success = "Your profile has been updated";
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
    <title>Online Library Management System | My Profile</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- JAVASCRIPT VALIDATION -->
    <script>
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 name="myprofile" class="header-line">My Profile</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">My Profile</div>
                        <div class="panel-body">
                            <form name="signup" method="post">
                                <?php if(isset($error)){?>
                                <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
                                <?php } ?>
                                <?php if(isset($success)){?>
                                <div class="errorWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($success); ?> </div>
                                <?php } ?>
                                <?php
                                $sid = $_SESSION['stdid'];
                                $sql = "SELECT StudentId, FullName, EmailId, MobileNumber, RegDate, UpdationDate, Status FROM tblstudents WHERE StudentId = :sid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {
                                ?>
                                <div class="form-group">
                                    <label>Student ID :</label>
                                    <?php echo htmlentities($result->StudentId); ?>
                                </div>
                                <div class="form-group">
                                    <label>Reg Date :</label>
                                    <?php echo htmlentities($result->RegDate); ?>
                                </div>
                                <?php if ($result->UpdationDate != "") { ?>
                                <div class="form-group">
                                    <label>Last Updation Date :</label>
                                    <?php echo htmlentities($result->UpdationDate); ?>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label>Profile Status :</label>
                                    <?php if ($result->Status == 1) { ?>
                                    <span style="color: green">Active</span>
                                    <?php } else { ?>
                                    <span style="color: red">Blocked</span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label>Enter Full Name</label>
                                    <input class="form-control" type="text" name="fullanme" value="<?php echo htmlentities($result->FullName); ?>" autocomplete="off" required />
                                </div>
                                <div class="form-group">

                                    <label>Mobile Number :</label>
                                    <input class="form-control" type="text" name="mobilephone" maxlength="10" value="<?php echo htmlentities($result->MobileNumber); ?>" autocomplete="off" required onkeypress="return isNumberKey(event)" />
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" type="email" name="email" value="<?php echo htmlentities($result->EmailId); ?>" autocomplete="off" required readonly />
                                </div>
                                <?php } } ?>
                                <button type="submit" name="update" class="btn btn-primary" id="submit">Update Now</button>
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
