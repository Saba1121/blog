<?php

require_once '../dbClass.php';
session_start();

if($_SESSION['logged'] && isset($_POST['postact'])) {
	try {
    	$postsDB -> upload($_POST['post'], $_SESSION['id']);
    	header("Location: ../mainPage/MainPage.php");
	} catch (Exception $e) {
    	header("Location: ../mainPage/MainPage.php?e=" . $e->getMessage());
	}
} else {
	header('Location: ../action/login.php');
}

?>
