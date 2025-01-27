<?php
include('functions.php');
@session_start();
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
if(!(is_buyer() or is_seller() or is_admin())){
    echo 'false';
}else{
    echo 'done';
}
if($_SERVER['REQUEST_METHOD']=='POST'){
    session_unset();
    header("location:".$_SERVER['PHP_SELF']."");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
    <input type="submit" value="session">
    </form>
    
</body>
</html>