<?php

include '../connect.php';
include '../functions.php';

$stmt=$db->prepare('SELECT items.final_rating,items.item_id,items.item_name,items.item_price,items.item_quantity,items.item_description,items.user_id,items.item_image_path,users.user_name
FROM items
INNER JOIN users ON
items.user_id=users.user_id');
$stmt->execute();
$items=$stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['add_to_cart'])) {
    if(!is_buyer()){
        header('location:../login');
        die();
    }
      $stmt1=$db->prepare('SELECT item_id, buyer_id FROM cart WHERE item_id=:item_id AND buyer_id=:user_id');
      $stmt1->bindParam(':item_id', $_POST['item_id'], PDO::PARAM_INT);
      $stmt1->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
      $stmt1->execute();
      $cart=$stmt1->fetch(PDO::FETCH_ASSOC);
      
      if ($cart) {
        echo "<script>alert('المنتج موجود بالفعل');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
      } else {
        try {
          $stmt2 = $db->prepare('INSERT INTO cart(item_id, buyer_id) VALUES(:item_id, :user_id)');
          $stmt2->bindParam(':item_id', $_POST['item_id'], PDO::PARAM_INT);
          $stmt2->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
          $stmt2->execute();
          echo "<script>alert('تمت إضافة المنتج في السلة');</script>";
          echo "<script>window.location.href = 'index.php';</script>";
          exit;
        } catch (PDOException $e) {
          echo 'Connection Error: ' . $e->getMessage();
        }
      }
  }
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>منتجات الهاند ميد</title>
  <link rel="stylesheet" href="style1.css">
</head>
<body>
  <header>
    <div class="nav">
      <h2>هاند ميد</h2>
      <div>
        <ul>
          <li><a href="../user-dashboard">الملف الشخصي</a></li>
          <li><a href="../user-dashboard">عربتك</a></li>
        </ul>
      </div>
    </div>
  </header>

  <main>
    <div class="container">
      <?php foreach($items as $item): ?>
      <div class="product">
        <div class="product-info">
          <img src="<?php echo '../addproduct/' . htmlspecialchars($item['item_image_path']); ?>" alt="صورة المنتج">
          <h2><?php echo htmlspecialchars($item['item_name']); ?></h2>
          <p class="price"><?php echo htmlspecialchars($item['item_price']) . ' جنيه'; ?></p>
          <p><?php echo "البائع: " . htmlspecialchars($item['user_name']); ?></p>
          <p style="color: #f39c12;">
            <?php
              if ($item['final_rating']) {
                echo str_repeat('★', (int)$item['final_rating']) . str_repeat('☆', 5 - (int)$item['final_rating']);
              } else {
                echo 'لا يوجد تقييم';
              }
            ?>
          </p>
          <p><?php echo "الكمية المتوفرة: " . htmlspecialchars($item['item_quantity']); ?></p>
        </div>
        <div>
          <?php if ((int)$item['item_quantity'] === 0): ?>
            <p class="stock">Out Of Stock</p>
          <?php else: ?>
            <form method="post">
              <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['item_id']); ?>">
              <button class="addcart" type="submit" name="add_to_cart">إضافة للسلة</button>
            </form>
            <button class="buyitem" onclick="window.location.href='purchase.php?product-id=<?php echo htmlspecialchars($item['item_id']); ?>'">شراء المنتج</button>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>
