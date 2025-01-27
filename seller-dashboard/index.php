<?php
include("../connect.php");
include("../functions.php");
if (!is_seller()) {
    header("location:../error-404");
    die();
}
$stmt = $db->prepare("SELECT user_name, user_email, user_wallet, user_ItemsNumber, user_mobile FROM users WHERE user_id=:user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt1 = $db->prepare('SELECT item_id, item_name, item_price, item_description, item_quantity FROM items WHERE user_id=:id');
$stmt1->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt1->execute();
$result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
$stmt2 = $db->prepare('select o.order_name,o.price,o.quantity,o.order_date from orders o,
items i where i.item_id=o.item_id AND i.user_id=:id');
$stmt2->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt2->execute();
$orders = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$stmt3 = $db->prepare('SELECT items.item_name,stas.total_sold from stas,items
where stas.item_id=items.item_id AND items.user_id=:id');
$stmt3->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt3->execute();
$statistics = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم البائع - متجر الإبداع</title>
    <link rel="stylesheet" href="style.css">
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
            <li><a href="session.php">تسجيل الخروج</a></li>
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

            <div class="action-buttons">
                <a href="edit-account.php?user-id=<?php echo $_SESSION['user_id']; ?>"><button class="btn edit-btn">تحديث البيانات</button></a>
                <button class="btn delete-btn" onclick="confirmDelete('سيتم حذف حسابك','delete-account.php?delete=true')">حذف الحساب</button>
                <button class="btn add-credit-btn" onclick="window.location.href='add.php'">إضافة رصيد</button>
            </div>
        </section>

        <section id="available-products" class="section">
            <h2>المنتجات المتاحة</h2>
            <table>
                <tr>
                    <th>اسم المنتج</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>

                <?php
                foreach ($result1 as $item) {
                    echo '<tr>';
                    echo '<td>' . $item['item_name'] . '</td>';
                    echo '<td>' . $item['item_price'] . '</td>';
                    echo '<td>' . $item['item_quantity'] . '</td>';
                    echo '<td>' . $item['item_description'] . '</td>';
                    echo "<td>
                            <a href='edit-product.php?product_id=" . $item['item_id'] . "&product_name=" . $item['item_name'] . "&product_price=" . $item['item_price'] . "&product_quantity=" . $item['item_quantity'] . "&product_description=" . $item['item_description'] . "'> 
                                <button class='btn edit-btn'>تعديل</button>
                            </a>
                            <button class='btn delete-btn' onclick=\"confirmDelete('سيتم حذف المنتج', 'delete-product.php?product_id=" . $item['item_id'] . "')\">حذف</button>
                          </td>";
                    echo '</tr>';
                }
                ?>
            </table>
            <div style="text-align: center; margin-top: 20px;">
                <a href="../addproduct"><button class="btn add-product-btn">إضافة منتج</button></a>
            </div>
        </section>

        <section id="sold-products" class="section">
            <h2>المنتجات المباعة</h2>
            <table>
                <tr>
                    <th>اسم المنتج</th>
                    <th>السعر</th>
                    <th>الكمية المباعة</th>
                    <th>تاريخ البيع</th>
                </tr>
                <?php
                foreach ($orders as $order) {
                    echo '<tr>';
                    echo '<td>' . $order['order_name'] . '</td>';
                    echo '<td>' . $order['price'] . '</td>';
                    echo '<td>' . $order['quantity'] . '</td>';
                    echo '<td>' . $order['order_date'] . '</td>';
                    echo '</tr>';
                }
                ?>

            </table>
        </section>

        <section id="best-sellers" class="section">
            <h2>الأكثر مبيعًا</h2>
            <table>
                <tr>
                    <th>اسم المنتج</th>
                    <th>الكمية المباعة</th>
                </tr>
                <?php
                foreach ($statistics as $stas) {
                    echo '<tr>';
                    echo '<td>' . $stas['item_name'] . '</td>';
                    echo '<td>' . $stas['total_sold'] . '</td>';
                    echo '</tr>';
                }
                ?>

            </table>
        </section>
    </div>
</body>

</html>