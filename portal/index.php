<?php
include("../connect.php");
include("../functions.php");
if (!is_buyer()) {
    if (is_seller()) {
        header("location:../seller-dashboard");
        die();
    }
    header("location:../login");
    die();
}
$stmt = $db->prepare("SELECT items.item_image_path as img,items.item_name,users.user_name as seller_name
 from items,users,stas
 where stas.item_id=items.item_id AND users.user_id=items.user_id LIMIT 3;
"); //الصورة واسم البائع واسم المنتج
$stmt->execute();
$statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متجر الهاند ميد</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../boots/css/bootstrap.min.css">
</head>

<body>

    <header>
        <h1>متجر الهاند ميد</h1>
    </header>

    <nav>
        <ul>
            <li><a href="../seller-dashboard/session.php"> تسجيل الخروج </a></li>
            <li><a href="../availableproducts">المنتجات</a></li>
            <li><a href="#services">خدماتنا</a></li>
            <li><a href="../user-dashboard/"> الملف الشخصي </a></li>
        </ul>
    </nav>


    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/9.jpg" class="d-block w-100" alt="صورة">
                <div class="carousel-caption d-none d-md-block">
                    <h5>أجمل الهاند ميد</h5>
                    <p>تصاميم إبداعية مميزة يدوية 100%.</p>
                </div>
            </div>
            <!-- <div class="carousel-item">
                <img src="img/6.jpg" class="d-block w-100" alt="صورة">
                <div class="carousel-caption d-none d-md-block">
                    <h5>تصاميم فريدة</h5>
                    <p>أضف لمسة خاصة لمنزلك.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/7.jpg" class="d-block w-100" alt="صورة">
                <div class="carousel-caption d-none d-md-block">
                    <h5>تصاميم فريدة</h5>
                    <p>أضف لمسة خاصة لمنزلك.</p>
                </div>
            </div> -->
            <!-- <div class="carousel-item">
                <img src="img/8.jpg" class="d-block w-100" alt="صورة">
                <div class="carousel-caption d-none d-md-block">
                    <h5>تصاميم فريدة</h5>
                    <p>أضف لمسة خاصة لمنزلك.</p>
                </div>
            </div> -->
            <!-- <div class="carousel-item">
                <img src="img/9.jpg" class="d-block w-100" alt="صورة">
                <div class="carousel-caption d-none d-md-block">
                    <h5>تصاميم فريدة</h5>
                    <p>أضف لمسة خاصة لمنزلك.</p>
                </div>
            </div> -->
            <!-- <div class="carousel-item">
                <img src="img/10.jpg" class="d-block w-100" alt="صورة">
                <div class="carousel-caption d-none d-md-block">
                    <h5>تصاميم فريدة</h5>
                    <p>أضف لمسة خاصة لمنزلك.</p>
                </div>
            </div> -->
            <div class="carousel-item">
                <img src="img/11.jpg" class="d-block w-100" alt="صورة">
                <div class="carousel-caption d-none d-md-block">
                    <h5>تصاميم فريدة</h5>
                    <p>أضف لمسة خاصة لمنزلك.</p>
                </div>
            </div>
            <!-- <div class="carousel-item">
                <img src="img/12.jpg" class="d-block w-100" alt="صورة">
                <div class="carousel-caption d-none d-md-block">
                    <h5>تصاميم فريدة</h5>
                    <p>أضف لمسة خاصة لمنزلك.</p>
                </div>
            </div> -->
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">السابق</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">التالي</span>
        </button>
    </div>
    <div class="container">
        <!-- Services Section -->
        <section id="services">
            <h2>خدماتنا</h2>
            <div class="services-container">
                <div class="service">
                    <h3>تصاميم مخصصة</h3>
                    <p>نصنع تصاميم تناسب ذوقك الشخصي.</p>
                </div>
                <div class="service">
                    <h3>التوصيل السريع</h3>
                    <p>نوصلك منتجاتنا إلى باب بيتك.</p>
                </div>
                <div class="service">
                    <h3>أسعار تنافسية</h3>
                    <p>أفضل الأسعار لأعلى جودة.</p>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="products">
            <a class="last-p" href="../availableproducts" target="_blank">
                <h2>تسوق تشكيلة من أفضل المشغولات اليدوية</h2>
            </a>
            <div class="products-grid">
                <?php foreach ($statistics as $stas) { ?>
                    <div class="product">
                        <img class="p_img" src="<?php echo '../addproduct/' . $stas['img'] ?>" alt="منتج">
                        <h3><?php echo $stas['item_name'] ?></h3>
                        <p>البائع: <?php echo $stas['seller_name'] ?></p>
                    </div>
                <?php } ?>
            </div>
        </section>

    </div>

    <!-- Contact Section -->
    <footer id="contact">
        <h2>تواصل معنا</h2>
        <p>EMAIL : <a href="mailto:galalahmednasser@outlook.com">galalahmednasser@outlook.com</a></p>
        <p>Whatsapp : <a href="wa.me/+201028730570">+20 123 456 789</a></p>
    </footer>

    <script src="../boots/js/bootstrap.js"></script>
    <script src="../boots/js/jquery-3.7.1.min.js"></script>
    <script src="../boots/js/popper.min.js"></script>
</body>

</html>