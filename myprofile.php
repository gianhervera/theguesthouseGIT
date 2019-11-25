<?php
	require('connect.php');
	session_start();
	$query = "SELECT * FROM users where userID = '$_SESSION[userID]'"; 
	$statement = $db->prepare($query);
	$statement->execute();


?>

<!DOCTYPE html>
<html>
<head>
	<title>My Account</title>
</head>
<body>
	<h1>Welcome, <?= $_SESSION['firstname'] ?>! </h1>
	<?php if($row = $statement->fetch()): ?>
		<div>
		<img src="uploads/<?= $row['profilepicture']?>" alt="profilepicture" height="200" width="200">
		<p>First Name: <?= $row['firstname'] ?></p>
		<p>Last Name: <?= $row['lastname'] ?></p>
		<p>Email: <?= $row['email']?></p>
        <p>Address: <?= $row['address'] ?></p>
        <p>Description: <?= $row['description'] ?></p>
		</div>

	<?php endif ?>
Edit</a></button>
     <button><a href="index.php">Back</a></button>
</body>
</html>