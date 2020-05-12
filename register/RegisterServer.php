<?php

require_once('../dbClass.php');

if(isset($_POST{'submit'})) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password2 = $_POST["rpassword"];

    try {
        $accountsDB->register($username, $email, $password, $password2);
        header('Location: ../mainPage/MainPage.php');
    } catch(Exception $e) {
        header('Location: Register.php?errors=' . $e->getMessage());
    }
} else {
    header('Location: Register.php');
}
