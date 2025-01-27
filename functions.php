<?php
session_start();
function is_admin(){
    if(isset($_SESSION['administrator']) && $_SESSION['administrator']=='true')
    return true;
}
function is_seller(){
    if(isset($_SESSION['user_id']) && $_SESSION['user_role']=='seller')
    return true;
}
function is_buyer(){
    if(isset($_SESSION['user_id']) && $_SESSION['user_role']=='buyer')
    return true;
}
?>