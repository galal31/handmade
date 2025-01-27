<?php
require_once('../functions.php');
require_once("../connect.php");
$err = 0;

if ((isset($_SESSION['user_id']) && ($_GET['user-id'] == $_SESSION['user_id']))
    || (isset($_SESSION['administrator']) && $_SESSION['administrator'] == 'true')
) {
    $stmt = $db->prepare("SELECT user_name, user_email, user_mobile FROM users WHERE user_id = :id");
    $stmt->bindParam(':id', $_GET['user-id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_email = $_POST['user-email'];
        $current_email = $result['user_email'];

        // تحقق فقط إذا تم تغيير البريد الإلكتروني
        if ($new_email !== $current_email) {
            $stmt1 = $db->prepare('SELECT user_email FROM users WHERE user_email = :email');
            $stmt1->bindParam(':email', $new_email, PDO::PARAM_STR);
            $stmt1->execute();
            $mail_result = $stmt1->fetch(PDO::FETCH_ASSOC);

            if ($mail_result) {
                $err = 1; // البريد الإلكتروني موجود بالفعل
            }
        }

        // تنفيذ التحديث إذا لم تكن هناك أخطاء
        if ($err !== 1) {
            $stmt2 = $db->prepare("UPDATE users SET user_name = :name, user_email = :email, user_mobile = :mobile WHERE user_id = :id");
            $stmt2->bindParam(':name', $_POST["user-name"], PDO::PARAM_STR);
            $stmt2->bindParam(':email', $new_email, PDO::PARAM_STR);
            $stmt2->bindParam(':mobile', $_POST["user-mobile"], PDO::PARAM_INT);
            $stmt2->bindParam(':id', $_GET['user-id'], PDO::PARAM_INT);

            if ($stmt2->execute()) {
                $user_id = $_GET['user-id'];
                if (isset($_SESSION['administrator']) && $_SESSION['administrator'] == 'true') {
                    header("Location: ../admin_panel/user-info.php?user=$user_id");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                echo "Error updating data.";
            }
        }
    }
} else {
    echo 'Unauthorized Access';
    die();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات المستخدم</title>
    <link rel="stylesheet" href="edit-user.css">
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
                <?php if ($err == 1) echo '<p class="error">البريد الإلكتروني موجود بالفعل</p>'; ?>

                <label for="user-mobile">رقم الهاتف</label>
                <input type="number" id="user-mobile" name="user-mobile" value="<?php echo $result['user_mobile']; ?>" required>

                <div class="action-buttons">
                    <button type="submit" class="btn save-btn">حفظ التعديلات</button>
                    <?php if (is_admin()) { ?>
                        <button type="button" class="btn cancel-btn" onclick="window.location.href='../admin_panel/user-info.php?user=<?php echo $_GET['user-id']; ?>'">إلغاء</button>
                    <?php } else { ?>
                        <button type="button" class="btn cancel-btn" onclick="window.location.href='index.php'">إلغاء</button>
                    <?php } ?>
                </div>
            </form>
        </section>
    </div>
</body>

</html>
