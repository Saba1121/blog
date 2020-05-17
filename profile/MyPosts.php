<?php

	session_start();

	//If user is not logged in redirect to login page
  	if(!$_SESSION['logged']) header('Location: ../login/login.php');

	require_once '../dbClass.php';

	$user = $accountsDB -> getCurrentUser();

	$usersPosts = $postsDB -> find('posters_id = ?', [$_SESSION['id']], 'id desc');

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>My Posts</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="MyPosts.css">
</head>
	
<body>


	<?php require_once '../header/header.php'; ?>


    <div class="container">
	  
		<div class="profile">
        	<div class="upper">
				
				<p><?php echo $user['username'] ?></p>

				<h6><?php echo $user['email'] ?></h6>
        		
			</div>
        </div>




    	<?php if($usersPosts -> rowCount() > 0): ?>

        	<?php while ($row =  $usersPosts->fetch(PDO::FETCH_ASSOC)): ?>

				<div class='posts'>

					<p><?php echo htmlspecialchars($row["post"]);?></p>

					<h6><?php echo $row['time'] ?><h6>


 					<form action='../action/DeletePost.php' method='post'>
						<input type='hidden' name='id' value='<?php echo $row['id']; ?>'>
						<button type='submit' name='dlt'> Delete </button>
					</form>
					
				</div>
				
			<?php endwhile ?>
		
		<?php endif ?> 

    </div>

</body>
</html>
