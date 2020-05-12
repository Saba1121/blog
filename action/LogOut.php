<?php
//Destroirs session to log out


session_start();

session_unset();

session_destroy();

header("Location: ../login/Login.php");
