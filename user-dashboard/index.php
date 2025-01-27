<?php
include("../connect.php");
include("../functions.php");
if (!is_buyer()) {
  header("location:../login");
  die();
}
$stmt = $db->prepare("SELECT user_name,user_email,user_mobile,user_wallet from users where user_id=:id");
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt1 = $db->prepare('SELECT items.item_name,items.item_price,users.user_name,cart.item_id
 from items,cart,users
 where cart.item_id=items.item_id AND cart.buyer_id=:id AND users.user_id=items.user_id'); //item_name - price - seller_name
$stmt1->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt1->execute();
$carts = $stmt1->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>عربة التسوق</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <nav class="navbar">
    <div class="logo">ملفي الشخصي</div>
    <ul>
      <li><a href="../availableproducts/index.php">تسوق الأن</a></li>
      <li><a href="#cart">عربة التسوق</a></li>
      <li><a href="../seller-dashboard/session.php">تسجيل الخروج</a></li>
    </ul>
  </nav>

  <main>
    <section class="user-info">
      <h2>بيانات المستخدم</h2>
      <table>
        <tr>
          <td>الاسم</td>
          <td><?php echo $result['user_name'] ?></td>
        </tr>
        <tr>
          <td>البريد الإلكتروني</td>
          <td><?php echo $result['user_email'] ?></td>
        </tr>
        <tr>
          <td>رقم الهاتف</td>
          <td><?php echo $result['user_mobile'] ?></td>
        </tr>
        <tr>
          <td>المحفظة</td>
          <td><?php echo $result['user_wallet'] ?></td>
        </tr>
      </table>
    </section>

    <div class="action-buttons">
      <a href="edit-user.php?user-id= <?php echo $_SESSION['user_id']; ?>"><button class="update">تحديث البيانات</button></a>
      <button class="delete" onclick="confirmDelete('سيتم حذف الحساب','delete-account.php?delete=true')">حذف الحساب</button>
      <button class="btn add-credit-btn" onclick="window.location.href='../seller-dashboard/add.php'">إضافة رصيد</button>
    </div>

    <section class="cart" id="cart">
      <h2>عربة التسوق</h2>
      <table>
        <thead>
          <tr>
            <th>اسم المنتج</th>
            <th>السعر</th>
            <th>البائع</th>
            <th>#</th>
          </tr>
          <?php
          foreach ($carts as $cart) {
            echo '<tr>';
            echo '<td>' . $cart['item_name'] . '</td>';
            echo '<td>' . $cart['item_price'] . '</td>';
            echo '<td>' . $cart['user_name'] . '</td>';
            echo "<td>
                             <a href='../availableproducts/purchase.php?product-id=" . $cart['item_id'] . "'> 
                                 <button class='btn edit-btn'>تأكيد الشراء</button>
                             </a>
                             <button class='btn delete-btn' onclick=\"confirmDelete('سيتم حذف المنتج من السلة', 'delete-cart.php?product_id=" . $cart['item_id'] . "')\">حذف</button>
                           </td>";

            echo '</tr>';
          }
          ?>
        </thead>
        <tbody>

        </tbody>
      </table>
    </section>
  </main>

  <script>
    function confirmDelete(message, url) {
      if (confirm(message)) {
        window.location.href = url;
      }
    }
  </script>
</body>

</html>