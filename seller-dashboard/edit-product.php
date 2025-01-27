<?php
include("../connect.php");
include('../functions.php');
if (!is_seller()) {
    header("location:../error-404");
    die();
}
//get vars
$product_id=$_GET['product_id'];
$product_name_url=$_GET['product_name'];
$product_price=$_GET['product_price'];
$product_description=$_GET['product_description'];
$product_quantity=$_GET['product_quantity'];
//get vars
if($_SERVER['REQUEST_METHOD']=='POST'){
    $stmt=$db->prepare("UPDATE items SET item_name=:name,item_price=:price,item_quantity=:quantity,item_description=:description where item_id=:id");
    $stmt->bindparam(':name',$_POST['product-name'],PDO::PARAM_STR);
    $stmt->bindParam(':price',$_POST['product-price'],PDO::PARAM_INT);
    $stmt->bindParam(':quantity',$_POST['product-quantity'],PDO::PARAM_INT);
    $stmt->bindParam(':description',$_POST['product-description'],PDO::PARAM_INT);
    $stmt->bindparam(':id',$product_id,PDO::PARAM_INT);
    $stmt->execute();
    header("location:index.php");
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المنتج - متجر الإبداع</title>
    <link rel="stylesheet" href="edit-product.css">
</head>
<body>
    <nav id="navbar">
        <ul>
            <li><a href="index.php">لوحة التحكم</a></li>
            <li><a href="edit-product.html">تعديل المنتج</a></li>
        </ul>
    </nav>

    <div id="dashboard">
        <section id="edit-product" class="section">
            <h2>تعديل المنتج</h2>

            <form action="" method="POST">
                <label for="product-name">اسم المنتج</label>
                <input type="text" name="product-name" value="<?php echo $product_name_url;?>" required>

                <label for="product-price">السعر</label>
                <input type="number" name="product-price" value="<?php echo $product_price;?>" required>

                <label for="product-quantity">الكمية</label>
                <input type="number" id="product-quantity" name="product-quantity" value="<?php echo $product_quantity;?>" required>

                <label for="product-description">الوصف</label>
                <textarea name="product-description" rows="4" required><?php echo $product_description;?></textarea>

                <div class="action-buttons">
                    <button type="submit" class="btn save-btn">حفظ التعديلات</button>
                    <button type="button" class="btn cancel-btn" onclick="window.location.href='dashboard.html'">إلغاء</button>
                </div>
            </form>
        </section>
    </div>
</body>
</html>