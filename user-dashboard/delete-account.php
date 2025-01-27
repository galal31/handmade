<?php
include("../connect.php");
include("../functions.php");
if(!is_buyer()){
    header("location:../error-404");
    die();
}
else
if(!isset($_SESSION["user_id"])){
    header("location:../login");
}
elseif(isset($_SESSION['user_id'])&&$_GET['delete']=='true'){
    $stmt2=$db->prepare('DELETE FROM users WHERE user_id = :id');
    $stmt2->bindparam(':id', $_SESSION['user_id'],PDO::PARAM_INT);
    $stmt2->execute();
    session_unset();
    session_destroy();
    header("location:../login");
    die();
}

?>