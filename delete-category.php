<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: Login_admin/index.php"); 
    }
    else{

$cat_id=intval($_GET['cat_id']);
$sql="DELETE FROM categories WHERE cat_id=:cat_id";
$query = $dbh->prepare($sql);
$query->bindParam(':cat_id',$cat_id,PDO::PARAM_STR);
$query->execute();
header("Location: manage-categories.php");
}
?>