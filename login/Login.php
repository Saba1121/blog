<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
   	<meta charset="utf-8">
   	<title>Login</title>
   	<link rel="stylesheet" href="Login.css">
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


    <form class="" action="LoginServer.php" method="post">
    	<div class="login">
        	<div class="box">

				<p>Login</p>
				<input type="text" value="<?php if(isset($_GET["email"])) {echo $_GET["email"];} ?>" name="email" placeholder="Enter Your Email" required pattern=".{6,}" title="Six or more characters">
				<br>
				<input type="password" name="password" placeholder="Enter Your Password" required pattern=".{6,}" title="Six or more characters">
				<br>
				<button type="submit" name="login">Log In</button>
				<br>
				<a href="../register/Register.php">Register</a>
				<br>
				<br>
				<?php if(isset($_GET['errors'])) echo strip_tags($_GET['errors'], '<br>') ?>

        	</div>
	    </div>
    </form>


  </body>
</html>
