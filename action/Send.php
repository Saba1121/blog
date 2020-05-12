<?php
require_once '../dbClass.php';
session_start();

if($_SESSION['logged'] && isset($_POST['send'])) {
    $messagesDB -> upload($_POST['message'], $_SESSION['id'], $_POST['adress']);

    header("Location: ../messanger/Messanger.php?Adress_id_linked=" . $_POST['adress']);
} else {
    header('Location: ../login/login.php'); 
}




?>
