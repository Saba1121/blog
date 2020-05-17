<?php
	session_start();

	//If visitor isn't logged in redirect to login page
	if(!$_SESSION['logged']) header('Location: ../login/login.php');

	
	require_once '../dbClass.php';
	
	//Get current users info
	$stmt = $accountsDB -> getCurrentUser();
	$email = $stmt['email'];
	$username = $stmt['username'];

	//fetching posts
	$fetchedPosts = $postsDB -> fetch_all('id DESC');

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="MainPage.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Blog</title>
</head>
  
<body>


	<?php require_once '../header/header.php' ?>



    <div class="container">

    	<div class="column1">
        	
			<div class="profile">
        		<div class="upper">
            
            		<p><?php echo $username?></p>

            		<h6><?php echo $email?></h6>
        		
				</div>
        	</div>

    	</div>





		<div class="column2">

			<div class="post">

				<p>Social Media</p>

				<form class="" action="../action/Post.php" method="post">
					<input type="text" name="post" placeholder="Status: Feeling Red">

					<button type="submit" name="postact">Post</button>
				</form>

			</div>


			<!-- Displaying posts -->
			<?php if($fetchedPosts->rowCount() > 0): ?>
				<?php while($row = $fetchedPosts->fetch(PDO::FETCH_ASSOC)): ?>
					<div class='posts'>

						<p><?php echo htmlspecialchars($row["post"]) ?></p>

						<h6><?php echo $row['time'] ?></h6>

					</div>
				<?php endwhile ?>
			<?php endif ?>

		</div>


		<div class="column3">

		</div>

	</div>



</body>
</html>
