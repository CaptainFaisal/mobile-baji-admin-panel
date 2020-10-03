<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: Login_admin/index.php"); 
    }
    else{

$news_id=intval($_GET['news_id']);
$sql="DELETE FROM news WHERE news_id=:news_id";
$query = $dbh->prepare($sql);
$query->bindParam(':news_id',$news_id,PDO::PARAM_STR);
$query->execute();
header("Location: manage-news.php");
}
?>