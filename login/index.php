<?php
include ('../connect.php');
require_once('../functions.php');
if(is_buyer()){
    header('location:../portal');
    die();
}
else if(is_seller()){
    header('location:../seller-dashboard');
    die();
}
$err_login='';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $log_email=$_POST['email'];
    $log_pass=$_POST['password'];
    $stmt=$db->prepare("SELECT * FROM users WHERE user_email= :email AND user_password= :pass");
    $stmt->bindValue(':email',$log_email,PDO::PARAM_STR);
    $stmt->bindValue(':pass',$log_pass,PDO::PARAM_STR);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    
    if($result){
           session_start();
           $stmt=$db->prepare('SELECT user_id,user_name,user_role FROM users WHERE user_email=:email');
           $stmt->bindValue(':email',$log_email,PDO::PARAM_STR);
           $stmt->execute();
           $result0=$stmt->fetch(PDO::FETCH_ASSOC);
           //session
           $_SESSION['user_id']=$result['user_id'];
           $_SESSION['user_email']=$log_email;
           $_SESSION['user_name']=$result0['user_name'];
           $_SESSION['user_role']=$result0['user_role'];
           if($_SESSION['user_role']=='buyer'){
            header('location:../portal');
           }else{
            header("location:../seller-dashboard");
           }
           
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
    <title>User Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
      <div class="nav">
        <h1>هاند ميد</h1>
        <div>
            <ul>
                <li><a href="../main/">الصفحه الرئيسيه</a></li>
                <li><a href="../availableproducts">المنتجات</a></li>
                <li><a href="../register">انشاء حساب</a></li>
            </ul>
        </div>
     </div>
    <div class="flex">
        <div class="container">
        <h2>user login</h2>
        <form method="POST" action="">
            <div class="input-group">
                <input style="text-align:center"
                 type="email" name="email" placeholder="الايميل" required>
            </div>
            <div class="input-group">
                <input style="text-align:center"
                 type="password" name="password" placeholder="كلمه السر" required>
                <span><?php echo $err_login ?></span>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

</body>
</html>
