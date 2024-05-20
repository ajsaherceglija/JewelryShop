<?php
session_start();

if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
    exit();
}
if (isset($_SESSION['user_type']) && isset($_GET['wishlist'])) {
    header("Location: wishlist.php");
    exit();
}
if (isset($_SESSION['user_type']) && isset($_GET['shopping_bag'])) {
    header("Location: shopping_bag.php");
    exit();
}
if (isset($_SESSION['user_type']) && isset($_GET['user_profile'])) {
    header("Location: user_profile.php");
    exit();
}
if (!isset($_SESSION['user_type']) &&(isset($_GET['wishlist']) ||
        isset($_GET['shopping_bag']) || isset($_GET['user_profile']))) {
    header("Location: login.php");
    exit();
}
?>