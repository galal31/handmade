<?php
require_once("../connect.php");
require_once("../functions.php");
if (is_buyer()) {
    header("location:../portal");
    die();
} else if (is_seller()) {
    header("location:../seller-dashboard");
    die();
}
$stmt = $db->query("select item_image_path from items");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>هاند ميد </title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg position-fixed ">
        <div class="container">
            <h4 class=" me-2" href="#">هاند ميد</h4>
            <button class="navbar-toggler bg-white text-warning-emphasis" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon "></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="active" aria-current="page" href="#Home">الصفحه الرئيسيه</a>
                    </li>
                    <li class="nav-item">
                        <a class="" aria-current="page" href="#Services">الخدمات</a>
                    </li>
                    <li class="nav-item">
                        <a class="" aria-current="page" href="#Contact_us">تواصل معنا</a>
                    </li>
                    <li class="nav-item dropdown">
                        <span class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            روابط
                        </span>
                        <ul class="dropdown-menu ">
                            <li><a class="dropdown-item" href="#">المنتجات</a></li>
                            <li><a class="dropdown-item " href="#">تسويق منتج</a></li>
                            <li><a class="dropdown-item" href="#">السله</a></li>
                            <li><a class="dropdown-item" href="#">المشتريات</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex">
                    <button onclick="window.location.href='../login';" class="btn me-3">تسجيل الدخول</button>
                    <button onclick="window.location.href='../Register';" class="btn">إنشاء حساب</button>
                </div>
            </div>
        </div>
    </nav>
    <section id="Home" class=" bg-secondary ">
        <div class="container">
            <div class="row d-flex justify-content-around align-items-center ">
                <h3 class="mt-3 mb-3 col-lg-6 col-XL-6 mt-sm-2 ">اكتشف جمال المنتجات اليدوية
                    المصنوعة بحب، كل قطعة فريدة تعكس الإبداع والتميز نقدم لك مجموعة مختارة من الحرف اليدوية التي تم
                    تصميمها بعناية
                    فائقة لتضيف لمسة خاصة إلى حياتك <br>
                    <button onclick="window.location.href='../login';" class="btn btn_about mb-3 mt-3 ">تسجيل
                        الدخول</button>
                </h3>
                <img class="col-lg-6 col-XL-6 col-md-0" src="1.png"
                    alt="">
            </div>
        </div>
    </section>
    <section id="About">
        <div class="container pt-5 pb-5 ">
            <div class="row d-flex justify-content-around align-items-center ">
                <div class="col-10 col-lg-5 col-md-6 ">
                    <img src="2.jpeg" alt="" style="width:100%;">
                </div>
                <div class="col-10  col-lg-5 col-md-6 ">
                    <p class=" fs-4 fw-semibold"> اذا كنت تبحث عن إضافة لمسة فنية إلى حياتك أو ترغب في اقتناء هدية
                        مميزة، هنا تجد ما يناسبك. نحن نقدم لك مجموعة واسعة من المنتجات اليدوية الفريدة التي تتميز
                        بالجودة العالية والدقة في التفاصيل. بالإضافة إلى ذلك، نقدم لك فرصة رائعة لبيع منتجاتك اليدوية
                        معنا، لتصل أعمالك إلى جمهور أوسع </p>
                    <button onclick="window.location.href='../availableproducts';" class="btn btn_about mb-3 mt-3 "
                        type="submit">تسوق المنتجات</button>
                </div>
            </div>
        </div>
    </section>
    <section id="Services">
        <div class="container pt-5 pb-5 ">
            <h2 class=" text-center text-white mb-4">جزء من خدماتنا على الموقع</h2>
            <div class="d-flex flex-wrap justify-content-center justify-content-md-center justify-content-sm-center">
                <?php foreach ($results as $row) {
                    echo "<img src=" . '../addproduct/' . $row['item_image_path'] . ">";
                } ?>

            </div>
        </div>
    </section>

    <section id="Contact_us" class=" position-relative">
        <div class="container">
            <div class="row">
                <form action="#Contact_us" method="post">
                    <div class="mb-3">
                        <label for="FullName" class="form-label">الاسم بالكامل</label>
                        <input type="text" class="form-control" id="exampleInputName" aria-describedby="FullNameHelp">
                        <div id="FullNameHelp" class="form-text"></div>
                    </div>
                    <div>
                        <label for="exampleInputEmail1" class="form-label">الايميل </label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">كلمه السر </label>
                        <input type="password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="d-flex">
                        <div class="mb-3 form-check">
                            <input type="radio" class="form-radio-input" id="exampleradio2" name="type_of_user" checked>
                            <label class="form-radiobutton2-label" for="exampleradio2">مشترى</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="radio" class="form-radio-input" id="exampleradio1" name="type_of_user">
                            <label class="form-radiobutton1-label" for="exampleradio1">بائع</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                            style="height: 100px"></textarea>
                        <label for="floatingTextarea2">تعليقك</label>
                    </div>
                    <button type="submit" class="sub btn btn-primary fs-5 mt-3">ارسال</button>
                    <button type="reset" class="btn btn-secondary fs-5  mt-3">تجاهل</button>
                </form>
                <div class="send position-absolute bg-black text-white p-4" id="hide_page">
                    <p>شكرا على تعليقك سنقوم باتخاذ الاجرات اللازمه فى اقرب وقت </p>
                    <div class="d-flex justify-content-evenly">
                        <button class="ok btn btn-success fs-5 mt-3">تاكيد</button>
                        <button class="discard btn btn-danger fs-5  mt-3">الغاء الارسال</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="text-center text-white ">
        <p class="p-2">&copy; 2024 Handmade Store</p>
    </footer>
    <script src="main1.js"></script>
    <script src="bootstrap.bundle.min.js"></script>
</body>

</html>