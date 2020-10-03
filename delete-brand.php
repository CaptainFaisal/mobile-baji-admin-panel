<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: Login_admin/index.php"); 
    }
    else{

$brand_id=intval($_GET['brand_id']);
$sql="DELETE FROM brands WHERE brand_id=:brand_id";
$query = $dbh->prepare($sql);
$query->bindParam(':brand_id',$brand_id,PDO::PARAM_STR);
$query->execute();
header("Location: manage-brands.php");
}
?>