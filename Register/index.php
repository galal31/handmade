<?php
include_once('../functions.php');
if (is_seller()) {
    if (is_buyer()) {
        header("location:../portal");
        die();
    }
    header("location:../seller-dashboard");
    die();
}

$nameerr = "";
$emailerr = "";

include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (mb_strlen($_POST['name'], 'UTF-8') < 2) {
        $nameerr = "wrong name";
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE user_email= :email");
    $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $emailerr = "email already exists";
    }

    if ($nameerr == "" && $emailerr == "") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $mobile = $_POST['mobile_number'];

        $stmt = $db->prepare("INSERT INTO users (user_name, user_email, user_password, user_role,user_mobile) VALUES (:name, :email, :password, :role,:mobile)");
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->bindValue(':mobile', $mobile, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $user_id = $db->lastInsertId();

            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $role;

            header("Location: ../addproduct");
            exit;
        } else {
            echo "حدث خطأ أثناء تسجيل الحساب.";
        }
    }
}

$db = null;
?>



<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_v2.css">
</head>

<body>


    <div class="nav" style="font-family: Arial, sans-serif;">
        <h2>هاند ميد</h2>
        <div>
            <ul>
                <li><a href="../main">الصفحه الرئيسيه</a></li>
                <li><a href="../availableproducts">المنتجات</a></li>
                <li><a href="../login"> تسجيل دخول</a></li>
            </ul>
        </div>
    </div>
    <div class="flex">
        <div class="container">
            <h2>New User Registration</h2>
            <form method="POST" action="index.php">
                <div class="input-group">
                    <input type="text" name="name" placeholder="Name" required>
                    <span><?php echo $nameerr; ?></span>
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                    <span><?php echo $emailerr; ?></span>
                </div>
                <div class="input-group">
                    <input type="tel" name="mobile_number" placeholder="mobile number" required>
                    <span><?php echo $emailerr; ?></span>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <label for="role">Account Type:</label>
                <select name="role" required>
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                </select>

                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>

</html>