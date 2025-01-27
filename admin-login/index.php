<?php
include ('../connect.php');
$err_login='';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $log_email=$_POST['email'];
    $log_pass=$_POST['password'];
    $stmt=$db->prepare("SELECT * FROM admins WHERE admin_email= :email AND admin_password= :pass");
    $stmt->bindValue(':email',$log_email,PDO::PARAM_STR);
    $stmt->bindValue(':pass',$log_pass,PDO::PARAM_STR);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    if($result){
           session_start();
           $_SESSION['administrator']='true';
           header('location:../admin_panel');
           
    }else{
        $err_login='email or password error';
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Admins Login</h2>
        <form method="POST" action="">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
                <span><?php echo $err_login?></span>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>
