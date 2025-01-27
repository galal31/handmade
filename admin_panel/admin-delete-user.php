<?php
include('../connect.php');
require_once('../functions.php');
if (!is_admin()) {
    header('../error-404');
    die();
}
$user_id = $_GET['user'];
if (is_admin()) {
    if (is_seller() && is_dir("../addproduct/uploads/$user_id")) {
        $stmt0 = $db->prepare('select item_image_path from items where user_id=:id');
        $stmt0->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt0->execute();
        $images = $stmt0->fetchAll(PDO::FETCH_ASSOC);
        //uploads/5/675de40f4cc34.jpg
        foreach ($images as $img) {
            $img_1 = $img['item_image_path'];
            unlink("../addproduct/$img_1");
        }
        rmdir("../addproduct/uploads/$user_id");
    }
    $stmt1 = $db->prepare('DELETE FROM users WHERE user_id = :id');
    $stmt1->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt1->execute();
    header('location:index.php');
} else {
    header('location:../error-404');
}
