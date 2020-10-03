<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: Login_admin/index.php"); 
    }
    else{
if(isset($_POST['update']))
{
$file = $_FILES['newsphoto'];
$fileName = $file['name'];
$fileError = $file['error'];
$fileTmpName = $file['tmp_name'];
$tmp = explode(".",$fileName);
$fileExtName = strtolower(end($tmp));
$allowed = Array('png','jpg','jpeg');
$allow = "";
$fileErr="";
$destination = "";
foreach($allowed as $key => $value){
    $allow .= ",".$value;
}
if(in_array($fileExtName,$allowed)){
    if($fileError === 0){
        $fileNameNew = uniqid("",true).".".$fileExtName;
        $destination = 'news-photos/'.$fileNameNew;
        move_uploaded_file($fileTmpName,$destination);
    }else{
        $fileErr="Something went wrong while uploading this photo.";
    }
}else{
    $fileErr="Type of this photo is invalid."."Only ".substr($allow,1)." are allowed";
}
$title=$_POST['title']; 
$news_id=intval($_GET['news_id']);
$subtitle = $_POST['subtitle'];
$news = $_POST['news'];
$sql="UPDATE news SET ".($file['name']?"news_img=:destination,":"")."news_title=:title".($subtitle?",news_subtitle=:subtitle":"").",news=:news WHERE news_id=:news_id;";
$query = $dbh->prepare($sql);
$query->bindParam(':title',$title,PDO::PARAM_STR);
$query->bindParam(':news',$news,PDO::PARAM_STR);
$query->bindParam(':news_id',$news_id,PDO::PARAM_STR);
if($subtitle){
    $query->bindParam(':subtitle',$subtitle,PDO::PARAM_STR);
}
if($file['name']){
    $query->bindParam(':destination',$destination,PDO::PARAM_STR);
}
$query->execute();
$msg="Data has been updated successfully";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update News</title>
    <link rel="stylesheet" href="css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen"> <!-- USED FOR DEMO HELP - YOU CAN REMOVE IT -->
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
</head>

<body class="top-navbar-fixed">
    <div class="main-wrapper">

        <!-- ========== TOP NAVBAR ========== -->
        <?php include('includes/topbar.php');?>
        <!-----End Top bar>
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
        <div class="content-wrapper">
            <div class="content-container">

                <!-- ========== LEFT SIDEBAR ========== -->
                <?php include('includes/leftbar.php');?>
                <!-- /.left-sidebar -->

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Update News</h2>
                            </div>

                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li><a href="#">News</a></li>
                                    <li class="active">Update News</li>
                                </ul>
                            </div>

                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->

                    <section class="section">
                        <div class="container-fluid">





                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <h5>Update News</h5>
                                            </div>
                                        </div>
                                        <?php if($msg){?>
                                        <div class="alert alert-success left-icon-alert" role="alert">
                                            <strong>Well done!</strong><?php echo htmlentities($msg); ?>
                                        </div><?php } 
else if($error){?>
                                        <div class="alert alert-danger left-icon-alert" role="alert">
                                            <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                        </div>
                                        <?php } ?>

                                        <form method="POST" enctype="multipart/form-data">
                                            <?php 
$news_id=intval($_GET['news_id']);
$sql = "SELECT * from news WHERE news_id=:news_id";
$query = $dbh->prepare($sql);
$query->bindParam(':news_id',$news_id,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>

                                            <div class="form-group has-success">
                                                <label class="control-label">News Photo</label>
                                                <div class="">
                                                    <input type="file" name="newsphoto" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group has-success">
                                                <label for="success" class="control-label">Title</label>
                                                <div class="">
                                                    <input type="text" name="title"
                                                        value="<?php echo htmlentities($result->news_title);?>"
                                                        required="required" class="form-control" id="success">
                                                </div>
                                            </div>
                                            <?php if($result->news_subtitle){ ?>
                                            <div class="form-group has-success">
                                                <label class="control-label">Sub Title</label>
                                                <div class="">
                                                    <input type="text" name="subtitle"
                                                        value="<?php echo htmlentities($result->news_subtitle);?>"
                                                        required="required" class="form-control">
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="form-group has-success">
                                                <label class="control-label">News</label>
                                                <div class="">
                                                    <textarea name="news"
                                                        value="<?php echo htmlentities($result->news);?>"
                                                        required="required"
                                                        class="form-control"><?php echo htmlentities($result->news)?></textarea>
                                                </div>
                                            </div>
                                            <?php }} ?>
                                            <div class="form-group has-success">

                                                <div class="">
                                                    <button type="submit" name="update"
                                                        class="btn btn-success btn-labeled">Update<span
                                                            class="btn-label btn-label-right"><i
                                                                class="fa fa-check"></i></span></button>
                                                </div>



                                        </form>


                                    </div>
                                </div>
                            </div>
                            <!-- /.col-md-8 col-md-offset-2 -->
                        </div>
                        <!-- /.row -->




                </div>
                <!-- /.container-fluid -->
                </section>
                <!-- /.section -->

            </div>
            <!-- /.main-page -->


            <!-- /.right-sidebar -->

        </div>
        <!-- /.content-container -->
    </div>
    <!-- /.content-wrapper -->

    </div>
    <!-- /.main-wrapper -->

    <!-- ========== COMMON JS FILES ========== -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/jquery-ui/jquery-ui.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/pace/pace.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/iscroll/iscroll.js"></script>

    <!-- ========== PAGE JS FILES ========== -->
    <script src="js/prism/prism.js"></script>

    <!-- ========== THEME JS ========== -->
    <script src="js/main.js"></script>



    <!-- ========== ADD custom.js FILE BELOW WITH YOUR CHANGES ========== -->
</body>

</html>
<?php  } ?>