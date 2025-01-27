<?php
$host="localhost";
$dbname="handmade";
$user="galal";
$password="123";

try {
    $db =new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user,$password);
} catch(PDOException $i){
    echo $i->getMessage();
}
?>
