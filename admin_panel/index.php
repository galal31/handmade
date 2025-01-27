<?php
include('../connect.php');
include('../functions.php');
if (!is_admin()) {
  header('location:../error-404/index.php');
  die();
}
$s_stmt = $db->prepare('select user_id,user_name,user_email,user_role from users');
$s_stmt->execute();
$users = $s_stmt->fetchAll(PDO::FETCH_ASSOC);
$s_stmt = $db->prepare('select items.item_name,users.user_name,stas.total_sold 
FROM items,users,stas 
where stas.item_id=items.item_id AND users.user_id=stas.user_id
order by stas.total_sold DESC;
');
$s_stmt->execute();
$statistics = $s_stmt->fetchAll(PDO::FETCH_ASSOC);
$p_stmt = $db->prepare('SELECT items.item_id,items.item_name,users.user_name,items.item_price,users.user_mobile
 FROM users,items 
 where items.user_id=users.user_id');
$p_stmt->execute();
$products = $p_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> Admin Dashboard</title>
  <!-- ======= Styles ====== -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
  <!-- =============== Navigation ================ -->
  <div class="container">
    <div class="navigation">
      <ul>
        <li>
          <a href="#">
            <span class="icon"><ion-icon></ion-icon></span>
            <span class="title">Hand Made</span>
          </a>
        </li>
        <li>
          <a href="#" id="customers-tab">
            <span class="icon">
              <ion-icon name="people-outline"></ion-icon>
            </span>
            <span class="title">Customers</span>
          </a>
        </li>
        <li>
          <a href="#" id="sellers-tab">
            <span class="icon"><ion-icon name="man-outline"></ion-icon></span>
            <span class="title">sellers</span>
          </a>
        </li>


        <li>
          <a href="#" id="orders-tab">
            <span class="icon">
              <ion-icon name="cart-outline"></ion-icon>
            </span>
            <span class="title">best products</span>
          </a>
        </li>

        <!-- <li>
            <a href="#">
              <span class="icon">
                <ion-icon name="settings-outline"></ion-icon>
              </span>
              <span class="title">admins</span>
            </a>
          </li> -->

        <li>
          <a href="#" id="products-tab">
            <span class="icon">
              <ion-icon name="apps-outline"></ion-icon>
            </span>
            <span class="title">products</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- ========================= Main ==================== -->
    <div class="main">
      <div class="topbar">
        <div class="toggle">
          <ion-icon name="menu-outline"></ion-icon>
        </div>

        <div class="search">
          <label>
            <input type="text" placeholder="Search here" />
            <ion-icon name="search-outline"></ion-icon>
          </label>
        </div>

        <div class="user">
          <img src="assets/imgs/customer01.jpg" alt="" />
        </div>
      </div>

      <!-- ================ content-area ================= -->
      <div class="details " id="content-area">
        <!-- ================ stas List ================= -->
        <div class="recentOrders" id="orders-content" style="display: none;">
          <div class="cardHeader">
            <h2>Recent Orders</h2>
            <a href="#" class="btn">View All</a>
          </div>

          <table>
            <thead>
              <tr>
                <td>Product</td>
                <td>seller</td>
                <td>total sold</td>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($statistics as $stas) { ?>
                <tr>
                  <td><?php echo $stas['item_name'] ?></td>
                  <td><?php echo $stas['user_name'] ?></td>
                  <td><?php echo $stas['total_sold'] ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>


        <!-- ================= New Customers ================ -->
        <div class="recentCustomers" id="customers-content">
          <div class="cardHeader">
            <h2>Recent Customers</h2>
            <a href="#" class="btn">View All</a>
          </div>

          <table>
            <?php foreach ($users as $user) { ?>
              <?php if ($user['user_role'] == 'buyer') { ?>
                <tr>
                  <td>
                    <div class="imgBx">
                      <img src="assets/imgs/customer02.jpg" alt="" />
                    </div>
                    <div>
                      <h4>
                        <?php echo $user['user_name'] ?> <br />
                        <span><?php echo $user['user_email'] ?></span>
                      </h4>
                    </div>
                  </td>

                  <td>
                    <div class="buttons">
                      <button style="border: 1px solid #e9b10a;"
                        class="btn"
                        onclick="confirmDelete(<?php echo $user['user_id'] ?>)">
                        Delete
                      </button>
                      <a href="user-info.php?user=<?php echo $user['user_id'] ?>" class="btn">More</a>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            <?php } ?>
          </table>
        </div>



        <!-- ================= New selllers ================ -->
        <div class="recentOrders" id="sellers-content" style="display: none;">
          <div class="cardHeader">
            <h2>Recent Sellers</h2>
            <a href="#" class="btn">View All</a>
          </div>

          <table>
            <thead>
                <tr>
                  <td>#</td>
                  <td>Name</td>
                  <td>Email</td>
                  <td> </td>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user) { ?>
                <?php if ($user['user_role'] == 'seller') { ?>
                  <tr>
                    <td><?php echo $user['user_id'] ?></td>
                    <td><?php echo $user['user_name'] ?></td>
                    <td><?php echo $user['user_email'] ?></td>
                    <td>
                      <div class="buttons">
                        <button style="border: 1px solid #e9b10a;"
                          class="btn"
                          onclick="confirmDelete(<?php echo $user['user_id'] ?>)">
                          Delete
                        </button>
                        <a href="seller-info.php?user=<?php echo $user['user_id'] ?>" class="btn">More</a>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <!-- ================= products ================ -->
        <div class="recentOrders" id="products-content" style="display: none;">
          <div class="cardHeader">
            <h2>Recent products</h2>
            <a href="#" class="btn">View All</a>
          </div>

          <table>
            <thead>
              <tr>
                <td>ID</td>
                <td>Name</td>
                <td>Price</td>
                <td>Selller's Name</td>
                <td>phone</td>
                <td> </td>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($products as $product) { ?>
                <tr>
                  <td><?php echo $product['item_id'] ?></td>
                  <td><?php echo $product['item_name'] ?></td>
                  <td><?php echo $product['item_price'] ?></td>
                  <td><?php echo $product['user_name'] ?></td>
                  <td><?php echo $product['user_mobile'] ?></td>
                  <td>
                    <div class="buttons">
                      <button
                        style="border: 1px solid #e9b10a;"
                        class="btn"
                        onclick="confirmDeleteProduct(<?php echo $product['item_id']; ?>)">
                        حذف المنتج
                      </button>
                    </div>

                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


  </div>

  <!-- =========== Scripts =========  -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <!-- ====== ionicons ======= -->
  <script
    type="module"
    src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js">
  </script>
  <script
    nomodule
    src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
    function confirmDelete(userId) {
      if (confirm('هل أنت متأكد من حذف هذا المستخدم ؟')) {
        window.location.href = 'admin-delete-user.php?user=' + userId;
      } else {
        alert('تم إلغاء الحذف.');
      }
    }

    function confirmDeleteProduct(item_id) {
      if (confirm('هل أنت متأكد من حذف هذا المنتج ؟')) {
        window.location.href = 'admin-delete-product.php?item_id=' + item_id;
      } else {
        alert('تم إلغاء الحذف.');
      }
    }
  </script>
</body>

</html>