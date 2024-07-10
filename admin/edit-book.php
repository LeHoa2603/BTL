<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
} else {
    if(isset($_POST['update'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $author = $_POST['author'];
        $price = $_POST['price'];
        $bookid = intval($_GET['bookid']);
        $sql = "UPDATE tblbooks SET BookName=:bookname, Category=:category, Author=:author, BookPrice=:price WHERE id=:bookid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':author', $author, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['msg'] = "Book info updated successfully";
        header('location:manage-books.php');
        exit();
    }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Edit Book</title>
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
                    <h4 class="header-line">Edit Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Book Info
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <?php
                                $bookid = intval($_GET['bookid']);
                                $sql = "SELECT BookName, Category, Author, BookPrice FROM tblbooks WHERE id = :bookid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':bookid', $bookid, PDO::PARAM_INT);
                                $query->execute();
                                $result = $query->fetch(PDO::FETCH_ASSOC);
                                if($result) { ?>
                                    <div class="form-group">
                                        <label>Book Name<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="bookname" value="<?php echo htmlentities($result['BookName']); ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label>Category<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="category" value="<?php echo htmlentities($result['Category']); ?>" required="required" />
                                    </div>

                                    <div class="form-group">
                                        <label>Author<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="author" id="authorbook" value="<?php echo htmlentities($result['Author']); ?>" required="required" />
                                    </div>

                                    <div class="form-group">
                                        <label>Price in USD<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="price" onkeypress="return isNumberKey(event)" value="<?php echo htmlentities($result['BookPrice']); ?>" required="required" />
                                    </div>
                                    <button type="submit" name="update" class="btn btn-info">Update</button>
                                <?php } ?>
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
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
<?php } ?>
