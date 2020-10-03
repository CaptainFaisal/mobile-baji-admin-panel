<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: Login_admin/index.php"); 
    }
    else{

$devid=intval($_GET['devid']);     
$device_photo = "";
$sql1 = "SELECT img FROM devices JOIN images USING(device_id) WHERE device_id=:devid;";
$query1 = $dbh->prepare($sql1);
$query1->bindParam(':devid',$devid,PDO::PARAM_STR);
$query1->execute();
$results1=$query1->fetchAll(PDO::FETCH_OBJ);
if($query1->rowCount() > 0)
{
foreach($results1 as $result1)
{
$device_photo = $result1->img;
}
}

if(isset($_POST['submit']))
{
$link_logo = "";
$sql = "SELECT link_logo FROM devices JOIN device_store_links USING(device_id) WHERE device_id=:devid;";;
$query = $dbh->prepare($sql);
$query->bindParam(':devid',$devid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{ 
$link_logo = $result->link_logo;
}
}
$logo_arr = explode(",",$link_logo);
$file = $_FILES['link_logo'];
$a = Array();
$fileErr = array();
for($i=0;$i<count($file['name']);$i++){
    foreach($file as $key => $value){
        $a[$key] = $value[$i];
    }
    if($a['name']){
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
                $destination = 'store-logos/'.$fileNameNew;
                move_uploaded_file($fileTmpName,$destination);
                $logo_arr[$i]=$destination;
            }else{
                    array_push($fileErr,"Something went wrong while uploading store logo ".($i+1).".");
            }
        }else{
            array_push($fileErr,"Type of store logo ".($i+1)." is invalid."."Only ".substr($allow,1)." are allowed");
        }
    }
}
if(count($file['name'])<count($logo_arr)){
    for($ii=0;$ii<count($logo_arr)-count($file['name']);$ii++){
        array_pop($logo_arr);
    }
}
$devimg_arr = explode(",",$device_photo);
$file1 = $_FILES['device_photo'];
$a1 = Array();
$fileErr1 = array();
for($i1=0;$i1<count($file1['name']);$i1++){
    foreach($file1 as $key1 => $value1){
        $a1[$key1] = $value1[$i1];
    }
    if($a1['name']){
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
                $destination1 = 'images/'.$fileNameNew1;
                move_uploaded_file($fileTmpName1,$destination1);
                $devimg_arr[$i1]=$destination1;
            }else{
                    array_push($fileErr1,"Something went wrong while uploading device photo ".($i1+1).".");
            }
        }else{
            array_push($fileErr1,"Type of device photo ".($i1+1)." is invalid."."Only ".substr($allow1,1)." are allowed");
        }
    }
}
$devicename=$_POST['devicename'];
$devicephoto = implode(",",$devimg_arr);
$linkLogo = implode(",",$logo_arr);
$category = $_POST['category'];
$brand = $_POST['brand'];
$price=$_POST['price'];
$details=$_POST['details'];
$links=$_POST['links'];
$hard_col_1=$_POST['hard_col_1'];
$hard_col_2=$_POST['hard_col_2'];
$soft_col_1=$_POST['soft_col_1'];
$soft_col_2=$_POST['soft_col_2'];
$specifications=$_POST['specifications'];
$sql="UPDATE devices SET name = :devicename,brand_id=:brand,cat_id=:category WHERE device_id = :devid;
UPDATE images SET img = :devicephoto WHERE device_id=:devid;
UPDATE prices SET prices=:price WHERE device_id=:devid;
UPDATE details SET details = :details WHERE device_id=:devid;
UPDATE device_store_links SET links=:links,link_logo=:link_logo WHERE device_id=:devid;
UPDATE hardware_details SET hard_col_1=:hard_col_1,hard_col_2=:hard_col_2 WHERE device_id=:devid;
UPDATE software_details SET soft_col_1=:soft_col_1,soft_col_2=:soft_col_2 WHERE device_id=:devid;
UPDATE specifications SET specifications=:specifications WHERE device_id=:devid;";
$query = $dbh->prepare($sql);
$query->bindParam(':devicename',$devicename,PDO::PARAM_STR);
$query->bindParam(':devicephoto',$devicephoto,PDO::PARAM_STR);
$query->bindParam(':price',$price,PDO::PARAM_STR);
$query->bindParam(':brand',$brand,PDO::PARAM_STR);
$query->bindParam(':category',$category,PDO::PARAM_STR);
$query->bindParam(':details',$details,PDO::PARAM_STR);
$query->bindParam(':links',$links,PDO::PARAM_STR);
$query->bindParam(':link_logo',$linkLogo,PDO::PARAM_STR);
$query->bindParam(':hard_col_1',$hard_col_1,PDO::PARAM_STR);
$query->bindParam(':hard_col_2',$hard_col_2,PDO::PARAM_STR);
$query->bindParam(':soft_col_1',$soft_col_1,PDO::PARAM_STR);
$query->bindParam(':soft_col_2',$soft_col_2,PDO::PARAM_STR);
$query->bindParam(':specifications',$specifications,PDO::PARAM_STR);
$query->bindParam(':devid',$devid,PDO::PARAM_STR);
$query->execute();

$msg="Device info updated successfully";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Device</title>
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
                                <h2 class="title">Edit Device</h2>

                            </div>

                            <!-- /.col-md-6 text-right -->
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>

                                    <li class="active">Edit Device</li>
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
                                            <h5>Fill the device info</h5>
                                            <h5><?php echo $cnt1; ?></h5>
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
                                        <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                                            <?php 

$sql = "SELECT * FROM devices
JOIN brands
USING (brand_id)
JOIN categories
USING (cat_id)
JOIN details
USING (device_id)
JOIN prices
USING (device_id)
JOIN device_store_links
USING (device_id)
JOIN images
USING (device_id)
JOIN hardware_details
USING (device_id)
JOIN software_details
USING (device_id)
JOIN specifications
USING (device_id)
WHERE device_id=:devid;";
$query = $dbh->prepare($sql);
$query->bindParam(':devid',$devid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{  ?>


                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Device Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="devicename" class="form-control"
                                                        id="devicename" value="<?php echo htmlentities($result->name)?>"
                                                        required="required" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Category</label>
                                                <div class="col-sm-10">
                                                    <select name="category" class="form-control" id="default" require>
                                                        <option value="<?php echo htmlentities($result->cat_id)?>">
                                                            Select Category</option>
                                                        <?php $sql2 = "SELECT * from categories";
$query2 = $dbh->prepare($sql2);
$query2->execute();
$results2=$query2->fetchAll(PDO::FETCH_OBJ);
if($query2->rowCount() > 0)
{
foreach($results2 as $result2)
{   ?>
                                                        <option value="<?php echo htmlentities($result2->cat_id); ?>">
                                                            <?php echo htmlentities($result2->category); ?>&nbsp;
                                                        </option>
                                                        <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Brand</label>
                                                <div class="col-sm-10">
                                                    <select name="brand" class="form-control" id="default" require>
                                                        <option value="<?php echo htmlentities($result->brand_id)?>">
                                                            Select Brand</option>
                                                        <?php $sql3 = "SELECT * from brands";
$query3 = $dbh->prepare($sql3);
$query3->execute();
$results3=$query3->fetchAll(PDO::FETCH_OBJ);
if($query3->rowCount() > 0)
{
foreach($results3 as $result3)
{   ?>
                                                        <option value="<?php echo htmlentities($result3->brand_id); ?>">
                                                            <?php echo htmlentities($result3->brand); ?>&nbsp;
                                                        </option>
                                                        <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <?php for($iter; $iter<count(explode(",",$device_photo));$iter++){?>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Device Photo
                                                    <?php echo $iter+1; ?></label>
                                                <div class="col-sm-10">
                                                    <input type="file" name="device_photo[]" class="form-control">
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Price</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="price" class="form-control" id="price"
                                                        value="<?php echo htmlentities($result->prices)?>"
                                                        required="required" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Details</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="details" class="form-control" id="details"
                                                        value="<?php echo htmlentities($result->details)?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="links" class="col-sm-2 control-label">Store
                                                    Links</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="links" class="form-control"
                                                        value="<?php echo htmlentities($result->links)?>" id="links">
                                                </div>
                                            </div>
                                            <div id="link_logo">
                                            </div>
                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Hardware
                                                    details</label>
                                                <div class="col-sm-5">
                                                    <input type="text" name="hard_col_1" class="form-control"
                                                        id="hard_col_1"
                                                        value="<?php echo htmlentities($result->hard_col_1)?>"
                                                        required="required" autocomplete="off" placeholder="Column 1">
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="text" name="hard_col_2" class="form-control"
                                                        id="hard_col_2"
                                                        value="<?php echo htmlentities($result->hard_col_2)?>"
                                                        required="required" autocomplete="off" placeholder="Column 2">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            </div>
                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Software
                                                    Details</label>
                                                <div class="col-sm-5">
                                                    <input type="text" name="soft_col_1" class="form-control"
                                                        id="soft_col_1"
                                                        value="<?php echo htmlentities($result->soft_col_1)?>"
                                                        required="required" autocomplete="off" placeholder="Column 1">
                                                </div>
                                                <div class="col-sm-5">
                                                    <input type="text" name="soft_col_2" class="form-control"
                                                        id="soft_col_2"
                                                        value="<?php echo htmlentities($result->soft_col_2)?>"
                                                        required="required" autocomplete="off" placeholder="Column 2">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="default"
                                                    class="col-sm-2 control-label">Specifications</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="specifications" class="form-control"
                                                        id="specifications"
                                                        value="<?php echo htmlentities($result->specifications)?>"
                                                        required="required" autocomplete="off">
                                                </div>
                                            </div>
                                            <?php }} ?>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="submit"
                                                        class="btn btn-primary">Update</button>
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
            const appendFileInput = () => $("#link_logo").append(`
                                            <div class="form-group">
                                                <label for="link_logo" class="col-sm-2 control-label">Store Logo</label>
                                                <div class="col-sm-10">
                                                    <input type="file" name="link_logo[]" class="form-control"
                                                        id="link_logo">
                                                </div>
                                            </div>`);
            $('#links').val().split(',').map(() => appendFileInput());
            $('#links').keyup(() => {
                const splitted = $('#links').val().split(",").length;
                if ($("#link_logo").children().length < splitted) {
                    appendFileInput();
                } else if ($("#link_logo").children().length > splitted) {
                    $('#link_logo').children()[$('#link_logo').children().length - 1].remove();
                }
            });
        });
        </script>
</body>

</html>
<?php } ?>