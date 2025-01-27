<?php
include("../connect.php");
include("../functions.php");
if (!is_admin()) {
  header('../error-404');
  die();
}
$stmt = $db->prepare("SELECT user_name,user_email,user_mobile,user_wallet from users where user_id=:id");
$stmt->bindParam(':id', $_GET['user'], PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// add money
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['add-balance'])) {
    $stmt = $db->prepare("update users set user_wallet = user_wallet+ :amount where user_id=:id");
    $stmt->bindParam(':amount', $_POST['amount'], PDO::PARAM_INT);
    $stmt->bindParam(':id', $_GET['user'], PDO::PARAM_INT);
    if ($stmt->execute()) {
      echo "<script> alert('تم إضافة الرصيد بنجاح ') </script>";
      header("location:".$_SERVER['PHP_SELF']."?user=".$_GET['user']."");
    }else
    {
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
  <title>عربة التسوق</title>
  <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>
  <nav class="navbar">
    <div class="logo">الملف الشخصي</div>
    <ul>
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
      <a href="../user-dashboard/edit-user.php?user-id=<?php echo $_GET['user']; ?>">
        <button class="update">تحديث البيانات</button>
      </a>
      <form method="POST" action="" class="add-balance-form">
        <input type="number" name="amount" placeholder="أدخل المبلغ" min="1" required>
        <button type="submit" name="add-balance" class="add-balance">إضافة رصيد</button>
      </form>
    </div>



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