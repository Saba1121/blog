<?php

  $db = mysqli_connect("localhost", "root", "", "social");

  $username = mysqli_real_escape_string($db, $_POST["username"]);
  $email = mysqli_real_escape_string($db, $_POST["email"]);
  $password = mysqli_real_escape_string($db, $_POST["password"]);
  $rpassword = mysqli_real_escape_string($db, $_POST["rpassword"]);

  $errors = "";

  $stmt = mysqli_stmt_init($db);

  if (mysqli_stmt_prepare($stmt, "SELECT id FROM accounts WHERE email = ? LIMIT 1")) {
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
    //Checks if mail is taken
    if (mysqli_num_rows($results)) {
      $errors .= " Mail is Taken <br>";
    }
    //Checks if passowrd doesnt match
    if ($password != $rpassword) {
      $errors .= "  Passwords Doesn't Match <br>";
    }
    //Checks if there are some erros during registration
    if (strlen($errors) > 0) {
      header("Location:Register.php?errors=$errors & username=$username & email=$email");
    }

    //Registers if everything is ok
    if (mysqli_num_rows($results) == 0 && strlen($errors) == 0) {
      if (mysqli_stmt_prepare($stmt,"INSERT INTO accounts(username, email, password) VALUES(?,?,?)")) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password_hashed);
        mysqli_stmt_execute($stmt);
      };


      //Initialises db again to get updated table
      $stmt = mysqli_stmt_init($db);
      $db2 = mysqli_connect("localhost", "root", "", "messages");

      //Creates table for messaging
      if (mysqli_stmt_prepare($stmt, "SELECT * FROM accounts WHERE email = ? LIMIT 1")) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $id_got_result = mysqli_stmt_get_result($stmt);
        $id_fetched_result = mysqli_fetch_assoc($id_got_result);
        $id_create_table = $id_fetched_result["id"];
        //Query for creating table
        mysqli_query($db2, "CREATE TABLE `messages`.`$id_create_table` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `message` VARCHAR(255) NOT NULL , `reciever` VARCHAR(255) NOT NULL , `time` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB");
      }
      //Redirects back to login page
      header("Location:Login.php");
    }

  }



?>
