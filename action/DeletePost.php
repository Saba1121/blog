<?php

session_start();
require_once '../dbClass.php';


if($_SESSION['logged'] && isset($_POST['dlt'])) {
    try {
        $postsDB -> delete($_POST['id']);

        header("Location: ../profile/MyPosts.php");
    } catch(Exception $e) {
        header("Location: ../profile/MyPosts.php");
    }
} else {    
    header('Location: ../login/login.php');
}