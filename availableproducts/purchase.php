<?php
include("../functions.php");
if(!is_buyer()){
    header('location:../login');
    die();
}
$qunatity_err=0;
$price_err=0;
include_once("../connect.php");
$stmt=$db->prepare("select * from items where item_id=:id");
$stmt->bindParam(':id',$_GET['product-id'],PDO::PARAM_INT);
$stmt->execute();
$result=$stmt->fetch(PDO::FETCH_ASSOC);
if(!isset($_GET['product-id'])){
    header('location:../error-404');
    exit();
}
if(!$result){
    echo '...المنتج غير متوفر';
    die();
}
//________________________________________ rate section
$stmt0=$db->prepare("SELECT user_name,buyer_rate,comment,rate_date from users,ratings where ratings.item_id=:id AND ratings.buyer_id=users.user_id;");
$stmt0->bindParam(':id',$_GET['product-id'],PDO::PARAM_INT);
$stmt0->execute();
$comments=$stmt0->fetchAll(PDO::FETCH_ASSOC);
//
//
//____________________________________________________
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['rate_b'])){
        try {
        $stmt01=$db->prepare('select rate_id from ratings where buyer_id=:id');
        $stmt01->bindParam(':id',$_SESSION['user_id'],PDO::PARAM_INT);
        $stmt01->execute();
        $buyer_comment=$stmt01->fetch(PDO::FETCH_ASSOC);
        //لو موجود هنحدثه
        if($buyer_comment){
            $r_date=date("D M Y");
            $stmt02=$db->prepare('update ratings set buyer_rate=:rate ,comment=:comment,rate_date=:date where buyer_id=:id 
            AND item_id=:iid');
            $stmt02->bindParam(':rate',$_POST['rating'],PDO::PARAM_INT);
            $stmt02->bindParam(':comment',$_POST['comment'],PDO::PARAM_STR);
            $stmt02->bindParam(':date',$r_date,PDO::PARAM_STR);
            $stmt02->bindParam(':id',$_SESSION['user_id'],PDO::PARAM_INT);
            $stmt02->bindParam(':iid',$_GET['product-id'],PDO::PARAM_INT);
            $stmt02->execute();
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
            // لو لا هننشا تقييم في جدول التقييمات
        }else{
            $r_date=date("D M Y");
            $stmt03=$db->prepare('insert into ratings (buyer_id,item_id,buyer_rate,comment,rate_date)
            values(:bid,:iid,:rate,:comment,:date)
            ');
            $stmt03->bindParam(':bid',$_SESSION['user_id'],PDO::PARAM_INT);
            $stmt03->bindParam(':iid',$_GET['product-id'],PDO::PARAM_INT);
            $stmt03->bindParam(':rate',$_POST['rating'],PDO::PARAM_INT);
            $stmt03->bindParam(':comment',$_POST['comment'],PDO::PARAM_STR);
            $stmt03->bindParam(':date',$r_date,PDO::PARAM_STR);
            $stmt03->execute();
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        }
        } catch (PDOException $e) {
            echo'CONNECTION ERROR..'.$e->getMessage();
        }
    }
    //------------------------------------------
    if(isset($_POST['quantity'])){
        if($_POST['quantity']> $result['item_quantity']){
            $qunatity_err=1;
    }else{
        $stmt1=$db->prepare('select user_id,user_wallet,user_ItemsNumber from users where user_id =:id');
        $stmt1->bindParam(':id',$_SESSION['user_id'],PDO::PARAM_INT);
        $stmt1->execute();
        $result1=$stmt1->fetch(PDO::FETCH_ASSOC);
        if($result1['user_wallet']<$_POST['quantity']*$result['item_price']){
            $price_err=1;
    }else{
        //خصم الفلوس من اليوزر 
        $stmt2_0=$db->prepare('update users set user_wallet = user_wallet-:price where user_id = :id');
        $total=$_POST['quantity']*$result['item_price'];
        $stmt2_0->bindParam(':price',$total,PDO::PARAM_INT);
        $stmt2_0->bindParam(':id',$_SESSION['user_id'],PDO::PARAM_INT);
        $stmt2_0->execute();
    
        //-----------------------------------------
        // اضافة الرصيد للبائع
        $stmt2_1=$db->prepare('update users set user_wallet = user_wallet+:price where user_id=:id');
        $total=$_POST['quantity']*$result['item_price'];
        $stmt2_1->bindParam(':price',$total,PDO::PARAM_INT);
        $stmt2_1->bindParam(':id',$result['user_id'],PDO::PARAM_INT);
        $stmt2_1->execute();
        //------------------------------------------
        //تقليل كمية المنتج المتاحه فالمخزون
        $stmt2_2=$db->prepare('update items set item_quantity=item_quantity-:sold where item_id=:id');
        $stmt2_2->bindParam(':sold',$_POST['quantity'],PDO::PARAM_INT);
        $stmt2_2->bindParam(':id',$result['item_id'],PDO::PARAM_INT);
        $stmt2_2->execute();
        //------------------------
        //اضافة الاوردر الي جدول الاوردرات
        $total=$result['item_price']*$_POST['quantity'];
        $order_date = date("d M Y");
    
        $stmt2_3=$db->prepare("insert into orders(buyer_id,item_id,order_name,quantity,price,order_date) values (:buyerid,:id,:name,:total_sold,:price,:date)");
        $stmt2_3->bindParam(':buyerid',$_SESSION['user_id'],PDO::PARAM_INT);
        $stmt2_3->bindParam(':id',$_GET['product-id'],PDO::PARAM_INT);
        $stmt2_3->bindParam(':name',$result['item_name'],PDO::PARAM_STR);
        $stmt2_3->bindParam(':total_sold',$_POST['quantity'],PDO::PARAM_INT);
        $stmt2_3->bindParam(':price',$total,PDO::PARAM_INT);
        $stmt2_3->bindParam(':date',$order_date,PDO::PARAM_STR);
        $stmt2_3->execute();
        // هنا جزء احصائيات المنتجات ومين اتباع اكتر وكده
        $stmt2_4=$db->prepare('select * from stas where item_id=:id');
        $stmt2_4->bindParam(':id',$_GET['product-id'],PDO::PARAM_INT);
        $stmt2_4->execute();
        if($stmt2_4->rowCount()> 0){
            $stmt2_4_0=$db->prepare('update stas set total_sold=total_sold+:sold where item_id=:id');
            $stmt2_4_0->bindParam(':id',$_GET['product-id'],PDO::PARAM_INT);
            $stmt2_4_0->bindParam(':sold',$_POST['quantity'],PDO::PARAM_INT);
            $stmt2_4_0->execute();
    }else{
        //user-id --> البائع بتاع المنتج
        $stmt2_4_1=$db->prepare('insert into stas(user_id,total_sold,item_id) values(:uid,:sold,:iid)');
        $stmt2_4_1->bindParam('uid',$result['user_id'],PDO::PARAM_INT);
        $stmt2_4_1->bindParam('sold',$_POST['quantity'],PDO::PARAM_INT);
        $stmt2_4_1->bindParam('iid',$result['item_id'],PDO::PARAM_INT);
        $stmt2_4_1->execute();
    }
        header("location:index.php");
        exit();
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
    <title>صفحة بيع المنتج</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="nav">
        <h2 style="color:#fff;">هاند ميد</h2>
        <div>
            <ul>
                <li><a href="../main">الصفحه الرئيسيه</a></li>
                <li><a href="../availableproducts">تابع التسوق</a></li>
                <li><a href="../user-dashboard/#cart">عربتك</a></li>
                <li><a href="../user-dashboard">الملف الشخصي</a></li>
                <li><a href="../seller-dashboard/session.php">تسجيل خروج</a></li>
            </ul>
        </div>
    </div>
    <div class="product-container">
        <img src="<?php echo '../addproduct/'.$result['item_image_path']?>" alt="صورة المنتج">
        <h1>اسم المنتج: <?php echo $result['item_name'] ?></h1>
        <p style="color: #f39c12; font-size:20px;">التقييم : <?php echo str_repeat('★',$result['final_rating']).str_repeat('☆',5-$result['final_rating']) ?></p>
        <p>وصف : <?php echo $result['item_description'] ?></p>
        <h3>السعر : <?php echo $result['item_price'] ?></h3>
        <p>الكمية المتاحة : <?php echo $result['item_quantity'] ?></p>

        <!-- Form for purchase -->
        <form action="" method="post">
            <label for="quantity">الكمية المطلوبة:</label>
            <input type="number" id="quantity" name="quantity" min="1" max="10" required>

            <?php
            if(isset($_POST['quantity'])){
                if($qunatity_err==1){
                    echo "<p class='error'>الكمية المطلوبة غير متاحة</p>";
                }else if($price_err==1){
                    echo "<p class='error'>رصيد غير كاف</p>";
                }
            }
            ?>

            <button class="button" type="submit">تأكيد الشراء</button>
            <a href="index.php" class="button cancel">إلغاء</a>
        </form>
    </div>

    <!-- Review Section -->
    <div class="review-container">
        <h2>التقييمات</h2>
        <form action="" method="post">
            <label for="rating">التقييم:</label>
            <select name="rating" id="rating" class="select-rating" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>

            <label for="comment">التعليق:</label>
            <textarea name="comment" id="comment" rows="4" placeholder="اكتب تعليقك هنا..." ></textarea>

            <button type="submit" class="button" name="rate_b">إرسال التقييم</button>
        </form>
    </div>

    <div class="comments">
        <?php foreach($comments as $comment){?>
            <div class="comment-container">
                <div class="comment-header"><?php echo $comment['user_name'] ?></div>
                <div class="comment-date"><?php echo $comment['rate_date'] ?></div>
                <div class="comment-rating"><?php echo str_repeat('★',$comment['buyer_rate']) . str_repeat('☆',(5-$comment['buyer_rate']) ) ?></div>
                <div class="comment-text">
                    <?php echo $comment['comment'] ?>
                </div>
            </div>
        <?php } ?>
        
    </div>
</body>
</html>


<!-- $filled_stars = str_repeat('★', $rating); // النجوم المكتملة
$empty_stars = str_repeat('☆', $total_stars - $rating); // النجوم الفارغة -->
