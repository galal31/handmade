<?php
include("../connect.php");
include("../functions.php");
if(!is_buyer()){
    header("location:../error-404");
    die();
}
$stmt = $db->prepare("DELETE FROM cart WHERE item_id=:id AND buyer_id=:uid");
$stmt->bindParam(":id", $_GET['product_id'], PDO::PARAM_INT);
$stmt->bindParam(":uid", $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
header('location:index.php');