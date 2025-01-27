<?php

include_once('../functions.php');
include_once('../connect.php');

if (!is_admin()) {
    header('../error-404');
    die();
}
try {
    $stmt0 = $db->prepare('select item_image_path from items where item_id=:iid');
    $stmt0->bindParam(':iid', $_GET['item_id'], PDO::PARAM_INT);
    $stmt0->execute();
    $item_image = $stmt0->fetch(PDO::FETCH_ASSOC);
    if (is_file("../addproduct/" .  $item_image['item_image_path'])) {
        unlink("../addproduct/" . $item_image['item_image_path']);
    } else {
        echo 'المنتج غير موجود';
        die();
    }
    $stmt = $db->prepare('delete from items where item_id=:id');
    $stmt->bindParam(':id', $_GET['item_id'], PDO::PARAM_INT);
    if ($stmt->execute()) {

        header('location:index.php');
    }
} catch (Exception $th) {
    echo 'error accessing file'.$th->getMessage();
}
