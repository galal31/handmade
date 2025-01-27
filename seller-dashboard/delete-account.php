<?php
include("../connect.php");
include("../functions.php");
if (!is_seller()) {
    header("location:../error-404");
    die();
} elseif (isset($_SESSION['user_id']) && $_GET['delete'] == 'true') {

    try {
        $stmt0 = $db->prepare('select user_id,item_image_path from items where user_id=:id');
        $stmt0->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt0->execute();
        $images = $stmt0->fetchAll(PDO::FETCH_ASSOC);
        foreach ($images as $image) {
            $img_path = $image['item_image_path'];
            unlink("../addproduct/$img_path");
        }
        $user_path = "../addproduct/uploads/" . $_SESSION['user_id'];
        rmdir($user_path);
        $stmt = $db->prepare('DELETE FROM items WHERE user_id = :id');
        $stmt->bindparam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $stmt2 = $db->prepare('DELETE FROM users WHERE user_id = :id');
        $stmt2->bindparam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt2->execute();
        session_unset();
        session_destroy();
        header("location:../login");
        die();
    } catch (Exception $e) {
        echo 'an error happend' . $e->getMessage();
    }
}
