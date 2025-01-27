<?php
include "../connect.php";
include "../functions.php";
if (!is_seller()) {
    if(is_buyer()){
        header("location:../portal");
        die();
    }
    header("location:../login");
}
$upload_done1 = "";
$upload_done2 = "";
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['product_image']['tmp_name'];
        $imageName = $_FILES['product_image']['name'];
        $a=explode('.', $imageName);
        $imageExtension = end($a);

        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        if (in_array(strtolower($imageExtension), $allowedExtensions)) {
            
            $newImageName = uniqid() . '.' . $imageExtension;
            //--> uploads/user_id
            $uploadDir = "uploads/" . $user_id;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $uploadFilePath = $uploadDir . '/' . $newImageName;

            if (move_uploaded_file($imageTmpPath, $uploadFilePath)) {
                $stmt = $db->prepare("INSERT INTO items (item_name, item_price, item_description, user_id, item_quantity, item_image_path) VALUES (?,?,?,?,?,?)");
                $stmt->execute([$name, $price, $description, $user_id, $quantity, $uploadFilePath]);
                $upload_done1 = "تم إضافة المنتج بنجاح!";
                header("location:../seller-dashboard");
            } else {
                $upload_done1 = "حدث خطأ أثناء رفع الصورة.";
            }
        } else {
            $upload_done2 = "صيغة الصورة غير صحيحة. الصيغ المسموح بها هي JPG, JPEG, PNG.";
        }
    } else {
        $upload_done1 = "لم يتم تحميل صورة.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>إضافة منتج جديد</title>
</head>

<body>
    <div class="nav">
        <h2 style="color:#fff;">هاند ميد</h2>
        <div>
            <ul>
                <li><a href="../main_page">الصفحه الرئيسيه</a></li>
                <li><a href="../availableproducts">المنتجات</a></li>
                <li><a href="../seller-dashboard/#available-products">عربتك</a></li>
                <li><a href="../seller-dashboard">صفحتك</a></li>
            </ul>
        </div>
    </div>
    <div class="flex">
        <div class="product-container">
            <h1>إضافة منتج جديد</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">اسم المنتج</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="price">السعر</label>
                    <input type="number" id="price" name="price" required>
                </div>
                <div class="form-group">
                    <label for="description">الوصف</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="quantity">الكمية</label>
                    <input type="number" id="quantity" name="quantity" required>
                </div>
                <div class="form-group">
                    <label for="product_image">صورة المنتج</label>
                    <input type="file" id="product_image" name="product_image" accept="image/*" required>
                </div>
                <?php if (!empty($upload_done1)) { ?>
                    <span class="success-message"><?php echo $upload_done1; ?></span>
                <?php } ?>
                <?php if (!empty($upload_done2)) { ?>
                    <span class="error-message"><?php echo $upload_done2; ?></span>
                <?php } ?>
                <button type="submit">إضافة المنتج</button>
            </form>
        </div>
    </div>
</body>

</html>