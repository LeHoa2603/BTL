<?php
session_start();
error_reporting(0);
include('includes/config.php');
$login_error_message = '';

// Đăng xuất người dùng nếu đã đăng nhập trước đó
if ($_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

// Xử lý đăng nhập khi người dùng gửi form
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Lưu ý: Sử dụng mã hóa mật khẩu MD5 không được khuyến khích vì nó không an toàn.

    // Kiểm tra xem thông tin đăng nhập có khớp với cơ sở dữ liệu không
    $sql = "SELECT UserName, Password FROM admin WHERE UserName = :username AND Password = :password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    // Nếu thông tin đăng nhập đúng, đặt session và chuyển hướng đến trang dashboard
    if ($query->rowCount() > 0) {
        $_SESSION['alogin'] = $_POST['username'];
        echo "<script type='text/javascript'> document.location ='admin/dashboard.php'; </script>";
    } else {
        // Nếu thông tin đăng nhập không đúng, hiển thị thông báo lỗi
         $login_error_message = 'Login unsuccessful';
        // echo "<script>alert('Đăng nhập không thành công');</script>";
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
    <title>Online Library Management System</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">ADMIN LOGIN</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            ADMIN LOGIN
                        </div>
                        <div class="panel-body">
                            <?php if (!empty($login_error_message)): ?>
                                <div class="alert alert-danger"><?php echo $login_error_message; ?></div>
                            <?php endif; ?>
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>USER NAME</label>
                                    <input class="form-control" type="text" name="username" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>PASSWORD</label>
                                    <input class="form-control" type="password" name="password" autocomplete="off" required />
                                </div>
                                <button type="submit" name="login" class="btn btn-info">LOGIN</button>
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
</body>
</html>
