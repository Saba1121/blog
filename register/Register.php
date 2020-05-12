<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="Register.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>

    <div class="nav">
    <div class="left">
        <p>LOGO</p>
      </div>

      <div class="right">
        <ul>
          <li>Home</li>
          <li>Prodcuts</li>
          <li>About</li>
          <li>Contact</li>
        </ul>
      </div>
    </div>

    <!---->

    <form class="" action="RegisterServer.php" method="post">
      <div class="register">
        <div class="box">

          <p>Register</p>
          <br>
          <input type="text" name="username" value="<?php if(isset($_GET["username"])) {echo $_GET["username"];}?>" placeholder="Enter Username" required pattern=".{6,}" title="Six or more characters" >
          <br>
          <input type="email" name="email" value="<?php if(isset($_GET["email"])) {echo $_GET["email"];}?>" placeholder="Enter Email" required pattern="+@globex.com">
          <br>
          <input type="password" name="password" value="" placeholder="Enter Password" required pattern=".{6,}" title="Six or more characters">
          <br>
          <input type="password" name="rpassword" value="" placeholder="Repeat Password" required>
          <br>
          <button type="submit" name="submit">Register</button>
          <br>
          <a href="../login/Login.php">Login</a>
          <br>
          <br>

          <?php
          if (isset($_GET["errors"])) {
            echo strip_tags($_GET["errors"], '<br>');
          }
          ?>

        </div>
      </div>
    </form>

  </body>
</html>
