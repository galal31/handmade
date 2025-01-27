<?php
include("../connect.php");
include("../functions.php");
if (!is_admin()) {
    header('../error-404');
    die();
}
$stmt = $db->prepare("SELECT user_name, user_email, user_wallet, user_ItemsNumber, user_mobile FROM users WHERE user_id=:user_id");
$stmt->bindParam(':user_id', $_GET['user'], PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt1 = $db->prepare('SELECT item_id, item_name, item_price, item_description, item_quantity FROM items WHERE user_id=:id');
$stmt1->bindParam(':id', $_GET['user'], PDO::PARAM_INT);
$stmt1->execute();
$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
$stmt2 = $db->prepare('select o.order_name,o.price,o.quantity,o.order_date from orders o,
items i where i.item_id=o.item_id AND i.user_id=:id');
$stmt2->bindParam(':id', $_GET['user'], PDO::PARAM_INT);
$stmt2->execute();
$orders = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$stmt3 = $db->prepare('SELECT items.item_name,stas.total_sold from stas,items
where stas.item_id=items.item_id AND items.user_id=:id');
$stmt3->bindParam(':id', $_GET['user'], PDO::PARAM_INT);
$stmt3->execute();
$statistics = $stmt3->fetchAll(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add-balance'])) {
        $stmt = $db->prepare("update users set user_wallet = user_wallet+ :amount where user_id=:id");
        $stmt->bindParam(':amount', $_POST['amount'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $_GET['user'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "<script> alert('تم إضافة الرصيد بنجاح ') </script>";
            header("location:" . $_SERVER['PHP_SELF'] . "?user=" . $_GET['user'] . "");
        } else {
            echo "<script> alert('Connection Error') </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم البائع - متجر الإبداع</title>
    <link rel="stylesheet" href="assets/css/style1.css">
    <script>
        function confirmDelete(message, url) {
            if (confirm(message)) {
                window.location.href = url;
            }
        }
    </script>
</head>

<body>
    <nav id="navbar">
        <ul>
            <li><a href="#seller-info">عن البائع</a></li>
            <li><a href="#available-products">المنتجات المتاحة</a></li>
            <li><a href="#sold-products">المنتجات المباعة</a></li>
            <li><a href="#best-sellers">الأكثر مبيعًا</a></li>
        </ul>
    </nav>

    <div id="dashboard">
        <section id="seller-info" class="section">
            <h2>معلومات البائع</h2>
            <table class="info-table">
                <tr>
                    <th>اسم البائع</th>
                    <td><?php echo $result['user_name'] ?></td>
                </tr>
                <tr>
                    <th>البريد الإلكتروني</th>
                    <td><?php echo $result['user_email'] ?></td>
                </tr>
                <tr>
                    <th>رقم الهاتف</th>
                    <td><?php echo $result['user_mobile'] ?></td>
                </tr>
                <tr>
                    <th>رصيد المحفظة</th>
                    <td><?php echo $result['user_wallet'] . " جنية" ?></td>
                </tr>
            </table>

            <!-- <div class="action-buttons">
                <a href="../seller-dashboard/edit-account.php?user-id=<?php echo $_GET['user']; ?>"><button class="btn edit-btn">تحديث البيانات</button></a>
                <button class="btn add-credit-btn">إضافة رصيد</button>
            </div> -->
            <div class="action-buttons">
                <a href="../seller-dashboard/edit-account.php?user-id=
                <?php echo $_GET['user']; ?>"><button class="btn edit-btn">تحديث البيانات</button></a>
                <form method="POST" action="" class="add-balance-form">
                    <input type="number" name="amount" placeholder="أدخل المبلغ" min="1" required>
                    <button type="submit" name="add-balance" class="add-balance">إضافة رصيد</button>
                </form>
            </div>
        </section>
    </div>
</body>

</html>