<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: Login_admin/index.php"); 
    }
    else{
if(isset($_POST['submit']))
{
$file = $_FILES['devimg'];
$a = Array();
$t = "";
$fileErr = array();
for($i=0;$i<count($file['name']);$i++){
    foreach($file as $key => $value){
        $a[$key] = $value[$i];
    }
    $fileName = $a['name'];
    $fileError = $a['error'];
    $fileTmpName = $a['tmp_name'];
    $tmp = explode(".",$fileName);
    $fileExtName = strtolower(end($tmp));
    $allowed = Array('png','jpg','jpeg');
    $allow = "";
    foreach($allowed as $key => $value){
        $allow .= ",".$value;
    }
    if(in_array($fileExtName,$allowed)){
        if($fileError === 0){
            $fileNameNew = uniqid("",true).".".$fileExtName;
            $destination = 'images/'.$fileNameNew;
            $t .= ",".$destination;
            move_uploaded_file($fileTmpName,$destination);
        }else{
                array_push($fileErr,"Something went wrong while uploading device photo ".($i+1).".");
        }
    }else{
        array_push($fileErr,"Type of device photo ".($i+1)." is invalid."."Only ".substr($allow,1)." are allowed");
    }
}
$file1 = $_FILES['link_logo'];
$a1 = Array();
$t1 = "";
$fileErr1 = array();
for($i1=0;$i1<count($file1['name']);$i1++){
    foreach($file1 as $key1 => $value1){
        $a1[$key1] = $value1[$i1];
    }
    $fileName1 = $a1['name'];
    $fileError1 = $a1['error'];
    $fileTmpName1 = $a1['tmp_name'];
    $tmp1 = explode(".",$fileName1);
    $fileExtName1 = strtolower(end($tmp1));
    $allowed1 = Array('png','jpg','jpeg');
    $allow1 = "";
    foreach($allowed1 as $key1 => $value1){
        $allow1 .= ",".$value1;
    }
    if(in_array($fileExtName1,$allowed1)){
        if($fileError1 === 0){
            $fileNameNew1 = uniqid("",true).".".$fileExtName1;
            $destination1 = 'store-logos/'.$fileNameNew1;
            $t1 .= ",".$destination1;
            move_uploaded_file($fileTmpName1,$destination1);
        }else{
                array_push($fileErr1,"Something went wrong while uploading store logo ".($i1+1).".");
        }
    }else{
        array_push($fileErr1,"Type of store logo ".($i1+1)." is invalid."."Only ".substr($allow1,1)." are allowed");
    }
}
$devicename=$_POST['devicename'];
$devimg=substr($t,1);
$brand_id=$_POST['brand'];
$cat_id=$_POST['category'];
$details=$_POST['details']; 
$store_links=$_POST['store_links'];
$link_logo=substr($t1,1);
$hard_col_1=$_POST['hard_col_1']; 
$hard_col_2=$_POST['hard_col_2']; 
$soft_col_1=$_POST['soft_col_1']; 
$soft_col_2=$_POST['soft_col_2']; 
$specificatoins=$_POST['specifications'];
$prices=$_POST['prices'];
$sql="INSERT INTO devices(name,cat_id,brand_id) VALUES(:devicename,:cat_id,:brand_id);
INSERT INTO images(device_id,img) VALUES((SELECT device_id FROM devices
ORDER BY device_id DESC
LIMIT 1),:devimg);
INSERT INTO hardware_details (device_id,hard_col_1,hard_col_2) VALUES ((SELECT device_id FROM devices
ORDER BY device_id DESC
LIMIT 1),:hard_col_1,:hard_col_2);
INSERT INTO software_details (device_id,soft_col_1,soft_col_2) VALUES ((SELECT device_id FROM devices
ORDER BY device_id DESC
LIMIT 1),:soft_col_1,:soft_col_2);
INSERT INTO details (device_id,details) VALUES ((SELECT device_id FROM devices
ORDER BY device_id DESC
LIMIT 1),:details);
INSERT INTO device_store_links (device_id,links,link_logo) VALUES ((SELECT device_id FROM devices
ORDER BY device_id DESC
LIMIT 1),:store_links,:link_logo);
INSERT INTO specifications (device_id,specifications) VALUES ((SELECT device_id FROM devices
ORDER BY device_id DESC
LIMIT 1),:specifications);
INSERT INTO prices (device_id,prices) VALUES ((SELECT device_id FROM devices
ORDER BY device_id DESC
LIMIT 1),:prices);";
$query = $dbh->prepare($sql);
$query->bindParam(':devicename',$devicename,PDO::PARAM_STR);
$query->bindParam(':devimg',$devimg,PDO::PARAM_STR);
$query->bindParam(':details',$details,PDO::PARAM_STR);
$query->bindParam(':store_links',$store_links,PDO::PARAM_STR);
$query->bindParam(':link_logo',$link_logo,PDO::PARAM_STR);
$query->bindParam(':cat_id',$cat_id,PDO::PARAM_STR);
$query->bindParam(':brand_id',$brand_id,PDO::PARAM_STR);
$query->bindParam(':hard_col_1',$hard_col_1,PDO::PARAM_STR);
$query->bindParam(':hard_col_2',$hard_col_2,PDO::PARAM_STR);
$query->bindParam(':soft_col_1',$soft_col_1,PDO::PARAM_STR);
$query->bindParam(':soft_col_2',$soft_col_2,PDO::PARAM_STR);
$query->bindParam(':specifications',$specificatoins,PDO::PARAM_STR);
$query->bindParam(':prices',$prices,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$msg="Device info added successfully";
}
else 
{
$error="Something went wrong. Please try again";
}

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Add new device </title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="css/select2/select2.min.css">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
</head>

<body class="top-navbar-fixed">
    <div class="main-wrapper">

        <!-- ========== TOP NAVBAR ========== -->
        <?php include('includes/topbar.php');?>
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
                                <h2 class="title">Add new device</h2>

                            </div>

                            <!-- /.col-md-6 text-right -->
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li>Devices</li>
                                    <li class="active">Add new device</li>
                                </ul>
                            </div>

                        </div>
                        <!-- /.row -->
                    </div>
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h5>Fill the Device Info</h5>
                                        </div>
                                    </div>
                                    <div class="panel-body">

                                        <?php if($msg){?>
                                        <div class="alert alert-success left-icon-alert" role="alert">
                                            <strong>Well done!</strong><?php echo htmlentities($msg); ?>
                                        </div><?php } 
else if($error){?>
                                        <div class="alert alert-danger left-icon-alert" role="alert">
                                            <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                        </div>
                                        <?php } ?>
                                        <?php if($fileErr){ 
                                        foreach($fileErr as $k=>$v){    
                                        ?>
                                        <div class="alert alert-danger left-icon-alert" role="alert">
                                            <?php echo htmlentities($v); ?>
                                        </div>
                                        <?php }}?>
                                        <?php if($fileErr1){ 
                                        foreach($fileErr1 as $k1=>$v1){    
                                        ?>
                                        <div class="alert alert-danger left-icon-alert" role="alert">
                                            <?php echo htmlentities($v1); ?>
                                        </div>
                                        <?php }}?>
                                        <form class="form-horizontal" id="form" method="POST"
                                            enctype="multipart/form-data">

                                            <div class="form-group">
                                                <label for="devicename" class="col-sm-2 control-label">Device
                                                    Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="devicename" class="form-control"
                                                        id="devicename" autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Brand</label>
                                                <div class="col-sm-10">
                                                    <select name="brand" class="form-control" id="default" require>
                                                        <option value="">Select Brand</option>
                                                        <?php $sql = "SELECT * from brands";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>
                                                        <option value="<?php echo htmlentities($result->brand_id); ?>">
                                                            <?php echo htmlentities($result->brand); ?>&nbsp;
                                                        </option>
                                                        <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Category</label>
                                                <div class="col-sm-10">
                                                    <select name="category" class="form-control" id="default" require>
                                                        <option value="">Select Category</option>
                                                        <?php $sql = "SELECT * from categories";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>
                                                        <option value="<?php echo htmlentities($result->cat_id); ?>">
                                                            <?php echo htmlentities($result->category); ?>&nbsp;
                                                        </option>
                                                        <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="img-input">
                                                <div class="form-group">
                                                    <label for="devimg" class="col-sm-2 control-label">Device
                                                        Photo</label>
                                                    <div class="col-sm-10">
                                                        <input type="file" name="devimg[]" class="form-control"
                                                            id="devimg" autocomplete="off" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-container"
                                                style="display:flex;flex-direction:row;align-items:center;justify-content:flex-end;margin-bottom:10px;flex-wrap:wrap;">
                                                <button type="button" class="btn btn-success m-10" id="add"
                                                    title="Click here to add more photo.">Add <i class="fa fa-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger m-10" id="remove">Remove <i
                                                        class="fa fa-minus"></i>
                                                </button>
                                            </div>

                                            <div class="form-group m-2">
                                                <label class="col-sm-2"
                                                    style="display:flex;justify-content:flex-end;">Hardware
                                                    Details</label>
                                                <div class="col-sm-5">
                                                    <input type="text" name="hard_col_1" class="form-control"
                                                        id="hard_col_1" autocomplete="off" placeholder="Column 1"
                                                        required>
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="text" name="hard_col_2" class="form-control"
                                                        id="hard_col_2" autocomplete="off" placeholder="Column 2"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2"
                                                    style="display:flex;justify-content:flex-end;">Software
                                                    Details</label>
                                                <div class="col-sm-5">
                                                    <input type="text" name="soft_col_1" class="form-control"
                                                        id="soft_col_1" autocomplete="off" placeholder="Column 1"
                                                        required>
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="text" name="soft_col_2" class="form-control"
                                                        id="soft_col_2" autocomplete="off" placeholder="Column 2"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="prices" class="col-sm-2 control-label">Prices</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="prices" class="form-control" id="prices"
                                                        autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="specifications"
                                                    class="col-sm-2 control-label">Specifications</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="specifications" class="form-control"
                                                        id="specifications" autocomplete="off" required>
                                                </div>
                                            </div>



                                            <div class="form-group">
                                                <label for="store_links" class="col-sm-2 control-label">Store
                                                    links</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="store_links" class="form-control"
                                                        id="store-links" required>
                                                </div>
                                            </div>
                                            <div id="link-logo">
                                                <div class="form-group">
                                                    <label for="link_logo" class="col-sm-2 control-label">Link
                                                        logo</label>
                                                    <div class="col-sm-10">
                                                        <input type="file" name="link_logo[]" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="details" class="col-sm-2 control-label">Details</label>
                                                <div class="col-sm-10">
                                                    <textarea name="details" id="details" cols="110" rows="5"
                                                        form="form"
                                                        placeholder="Enter some description of the device..."></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="submit" class="btn btn-primary">Add <i
                                                            class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>
        <script src="js/prism/prism.js"></script>
        <script src="js/select2/select2.min.js"></script>
        <script src="js/main.js"></script>
        <script>
        $(function($) {
            $(".js-states").select2();
            $(".js-states-limit").select2({
                maximumSelectionLength: 2
            });
            $(".js-states-hide").select2({
                minimumResultsForSearch: Infinity
            });
            $('#add').click(() => $('#img-input').append(`<div class="form-group">
                                                    <label for="devimg" class="col-sm-2 control-label">Device Photo</label>
                                                    <div class="col-sm-10">
                                                        <input type="file" name="devimg[]" class="form-control"
                                                            autocomplete="off" required>
                                                    </div>
                                                </div>`));
            $('#remove').click(() => $('#img-input').children()[$('#img-input').children().length - 1]
                .remove());
            $('#store-links').keyup(() => {
                const splitted = $('#store-links').val().split(",").length
                if ($("#link-logo").children().length < splitted) {
                    $('#link-logo').append(`<div class="form-group">
                                                    <label for="link_logo" class="col-sm-2 control-label">Link logo</label>
                                                    <div class="col-sm-10">
                                                        <input type="file" name="link_logo[]" class="form-control" required>
                                                    </div>
                                                </div>`);
                } else if ($("#link-logo").children().length > splitted) {
                    $('#link-logo').children()[$('#link-logo').children().length - 1].remove();
                }
            })

        });
        </script>
</body>

</html>
<?php } ?>