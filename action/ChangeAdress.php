<?php
//Changes adress in messanger

session_start();

if($_SESSION['logged'] && isset($_POST['change'])) {
    $Adress_id = $_POST['change'];

    header("Location: ../messanger/Messanger.php?Adress_id_linked=$Adress_id");
} else {
    header('Location: ../login/login.php'); 
}

?>
