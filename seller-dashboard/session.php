<?php
include('../functions.php');
if(!(is_seller() or is_buyer())){
    header("location:../error-404");
    die();
}
else{
    session_unset();
    session_destroy();
    header("location:../login");
}

?>