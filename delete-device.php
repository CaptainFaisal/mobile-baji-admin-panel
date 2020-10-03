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
$sql="DELETE FROM devices WHERE device_id = :devid;
DELETE FROM brands WHERE device_id=:devid;
DELETE FROM images WHERE device_id=:devid;
DELETE FROM details WHERE device_id=:devid;
DELETE FROM device_store_links WHERE device_id=:devid;
DELETE FROM hardware_details WHERE device_id=:devid;
DELETE FROM software_details WHERE device_id=:devid;
DELETE FROM specifications WHERE device_id=:devid;";
$query = $dbh->prepare($sql);
$query->bindParam(':devid',$devid,PDO::PARAM_STR);
$query->execute();
header("Location: manage-devices.php");
}
?>