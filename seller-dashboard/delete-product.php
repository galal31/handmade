<?php
include("../functions.php");
include("../connect.php");
if (!is_seller()) {
    header("location:../error-404");
    die();
}
try {
    if (!isset($_GET['product_id']) || !isset($_SESSION['user_id'])) {
        throw new Exception("Missing product_id or user_id.");
    }
    $stmt0 = $db->prepare('select item_image_path from items where item_id=:id and user_id=:uid');
    $stmt0->bindParam('id', $_GET['product_id'], PDO::PARAM_INT);
    $stmt0->bindParam('uid', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt0->execute();
    $img_path = $stmt0->fetch(PDO::FETCH_ASSOC);
    $final_path = '../addproduct/' . $img_path['item_image_path'];
    unlink($final_path);
    $stmt = $db->prepare('DELETE FROM items WHERE item_id=:id AND user_id=:uid');
    $stmt->bindParam(":id", $_GET['product_id'], PDO::PARAM_INT);
    $stmt->bindParam(":uid", $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    header('location:index.php');
} catch (Exception $th) {
    echo 'connection Error'. $th->getMessage();
}
