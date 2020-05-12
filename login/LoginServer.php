<?php


require_once ('../dbClass.php');
// $accountsDB = new accounts_db();


if(isset($_POST['login'])) {
	try {
    	$accountsDB -> login($_POST['email'], $_POST['password']);
		header('Location: ../mainPage/MainPage.php');
		
  	} catch(Exception $e) {
    	header('Location: login.php?errors='.$e->getMessage());
  	}
} else {
  	header('Location: login.php');
}







?>
