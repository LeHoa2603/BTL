<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit(); // Prevent further execution if redirection occurs
}

if(isset($_POST['add'])) {
    $bookname = $_POST['bookname'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $price = $_POST['price'];

    // Validate the price
    if(!is_numeric($price) || $price <= 0) {
        $_SESSION['error'] = "Invalid price. Please enter a valid positive number.";
    } else {
        $sql = "INSERT INTO tblbooks(BookName, Category, Author, BookPrice) VALUES(:bookname, :category, :author, :price)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':author', $author, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);

        if($query->execute()) {
            $_SESSION['msg'] = "Book listed successfully";
            header('location: manage-books.php');
            exit(); // Prevent further execution after redirection
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again";
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
    <title>Online Library Management System | Add Book</title>
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
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Add Book</h4>
                </div>
            </div>
            <?php if($_SESSION['error'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <strong>Error :</strong>
                        <?php echo htmlentities($_SESSION['error']); ?>
                        <?php echo htmlentities($_SESSION['error']=""); ?>
                    </div>
                </div>
            <?php } else if($_SESSION['msg'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Success :</strong>
                        <?php echo htmlentities($_SESSION['msg']); ?>
                        <?php echo htmlentities($_SESSION['msg']=""); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Book Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" onsubmit="return validateForm()">
                                <div class="form-group">
                                    <label>Book Name<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="bookname" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>Category<span style="color:red;">*</span></label>
                                    <input type="text" class="form-control" name="category" required="required" placeholder="Enter Category">
                                </div>
                                <div class="form-group">
                                    <label>Author<span style="color:red;">*</span></label>
                                    <input id ="authorbook" type="text" class="form-control" name="author" required="required" placeholder="Enter Author">
                                </div>
                                <div class="form-group">
                                    <label>Price<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="price" autocomplete="off" required="required"placeholder="Only Number" onkeypress="return isNumber(event)" />
                                </div>
                                <button type="submit" name="add" class="btn btn-info">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
        function validateForm() {
            var price = document.querySelector('input[name="price"]').value;
            if (isNaN(price) || price <= 0) {
                alert("Please enter a valid positive number for the price.");
                return false;
            }
            return true;
        }

        function isNumber(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
    </script>
</body>
</html>
