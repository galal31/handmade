<?php

session_start();
session_regenerate_id();
echo '<pre>';
print_r($_SESSION). '<br>';
print_r(session_id()). '<br>';

echo '</pre>';
?>