<?php
	session_start();

	if(!$_SESSION['logged']) header('Location: ../login/login.php'); 
	
	require_once '../dbClass.php';

	$localid = $_SESSION['id'];
	$results = $accountsDB -> find('id != ?', [$localid]); 

	$row = $results->fetchColumn();
	$Adress_id = $row;

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
    <title>Messanger</title>
    <link rel="stylesheet" href="Messanger.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

	<div class="nav">
    	<ul>
        	<a href="../mainPage/MainPage.php"><li>HOME</li></a>
        	<a href="../messanger/Messanger.php"><li>MESSAGES</li></a>
        	<a href="../profile/MyPosts.php"><li>MY POSTS</li></a>
      	</ul>
	</div>


    <div class="friends">

		<?php while ($row = $results->fetch(PDO::FETCH_ASSOC)): ?>
	
	       	<form action='../action/ChangeAdress.php' method='POST'>
            	<input type='hidden' name='change' value='<?php echo $row['id']?>'>
           		<button name='submit'> <?php echo $row['email'] ?> </button>
          	</form>

		<?php endwhile ?>

    </div>



    <?php
        // Checks if recievers id is set if not sets
    	if (isset($_GET["Adress_id_linked"])) {
			$Adress_id = $_GET["Adress_id_linked"];
        }
		
        //Gets info of friend using id
        $results2 = $accountsDB -> find('id = ?', [$Adress_id]);
        $row2 = $results2 -> fetch(PDO::FETCH_ASSOC);
        

        //displaying messages
		$stmt = $messagesDB -> fetch_messages($localid, $Adress_id);
	?>
		
	<div class="messages">
		<p>
			<?php echo $row2['username']; ?>
		</p>
		
		<div name='textarea' class='message_box'>
			<?php if($stmt -> rowCount() > 0): ?>
				<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?> 	

					<?php if ($row['reciever'] == $localid): ?> 
							
						<form>
							<h5 class='guest'> <?php echo htmlspecialchars($row['message'])?> </h5>
						</form>

					<?php else: ?>

						<form>
							<h5 disabled class='local' colls='10'> <?php echo htmlspecialchars($row['message'])?> </h5>
						</form>
						
					<?php endif ?>
					
				<?php endwhile ?>
			<?php endif ?>
		</div>


			<form autocomplete="off" class="send_form" action="../action/Send.php" method="post">
				<textarea type='text' name='message'></textarea>
				<input type='hidden' name='adress' value='<?php echo $Adress_id ?>'>
				<button type='submit' name='send'>Send</button>
			</form>
   		</div>
	</div>




    <script src="Messanger.js"></script>

</body>
</html>
