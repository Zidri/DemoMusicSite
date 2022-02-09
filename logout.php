<?php
    require('connect.php');

    unset($_SESSION['username']);
    unset($_SESSION['logged']);
    header('Location: login.php');
?>