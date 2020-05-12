<?php

session_start();
require_once '../dbClass.php';

if($_SESSION['logged'] && isset($_POST['delete_prof'])) {
    $postsDB -> delete($_POST['id']);

    header("Location: ../profile/MyPosts.php");
}

else if($_SESSION['logged'] && isset($_POST['delete_main'])) {
    $postsDB -> delete($_POST['id']);

    header("Location: ../mainPage/MainPage.php");
} else {    
    header('Location: ../login/login.php');
}