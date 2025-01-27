<?php

require_once("../functions.php");
require_once("../connect.php");
$err = 0;
if (!(is_admin() or is_seller())) {
    echo 'Unauthorized Access';
    die();
} else {
    //عرض البيانات الحاليه
    $stmt = $db->prepare("select user_name,user_email,user_mobile from users where user_id=:id");
    $stmt->bindParam(':id', $_GET['user-id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $stmt1 = $db->prepare('select user_email from users where user_email=:email');
        $stmt1->bindParam(':email', $_POST['user-email'], PDO::PARAM_STR);
        $stmt1->execute();
        $mail_result = $stmt1->fetch(pdo::FETCH_ASSOC);
        if ($mail_result) {
            $err = 1;
        } else {
            //تحديث البيانات
            $stmt2 = $db->prepare("update users set user_name=:name,user_email=:email,user_mobile=:mobile where user_id=:id");
            $stmt2->bindParam(':name', $_POST["user-name"], PDO::PARAM_STR);
            $stmt2->bindParam(':email', $_POST["user-email"], PDO::PARAM_STR);
            $stmt2->bindParam(':mobile', $_POST["user-mobile"], PDO::PARAM_INT);
            $stmt2->bindParam(':id', $_GET['user-id'], PDO::PARAM_INT);

            if ($stmt2->execute()) {
                $userid = $_GET['user-id'];
                if (is_admin()) {
                    header("Location:../admin_panel/seller-info.php?user= $userid ");
                } elseif (is_seller()) {
                    header('location:index.php');
                }
            } else {
                echo "Error updating data.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات المستخدم</title>
    <link rel="stylesheet" href="edit-account.css">
</head>

<body>
    <nav id="navbar">
        <ul>
            <li><a href="index.php">لوحة التحكم</a></li>
            <li><a href="edit-product.html">تعديل بياناتي</a></li>
        </ul>
    </nav>

    <div id="dashboard">
        <section id="edit-product" class="section">
            <h2>تعديل بيانات المستخدم</h2>

            <form action="" method="POST">
                <label for="user-name">اسم المستخدم</label>
                <input type="text" name="user-name" value="<?php echo $result['user_name']; ?>" required>

                <label for="user-email">البريد الإلكتروني</label>
                <input type="email" name="user-email" value="<?php echo $result['user_email']; ?>" required>
                <?php $err == 1 ? print 'email exist' : print ' ' ?>

                <label for="user-mobile">رقم الهاتف</label>
                <input type="number" id="user-mobile" name="user-mobile" value="<?php echo $result['user_mobile']; ?>" required>


                <div class="action-buttons">
                    <button type="submit" class="btn save-btn">حفظ التعديلات</button>
                    <?php if(is_seller()){?>
                        <button type="button" class="btn cancel-btn" onclick="window.location.href='index.php'">إلغاء</button>
                    <?php }elseif(is_admin()){?>
                        <button type="button" class="btn cancel-btn" onclick="window.location.href=
                        `../admin_panel/seller-info.php?user= <?php echo $_GET['user-id'] ?>`">إلغاء</button>
                    <?php }?>
                </div>
            </form>
        </section>
    </div>
</body>

</html>