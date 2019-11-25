<?php  
session_start();
require('connect.php');
$search_value=$_POST["search"];
$query= "select * from room where RoomType like '%$search_value%'";
$statement = $db->prepare($query);
$statement->execute();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Search Results</title>
</head>
<body>
		<form action="search.php" method="post">
		<input type="text" name="search">
		<input type="submit" name="submit" value="Search">
		</form>
	<div id="header">
		<h1>The Guest House</h1>
	</div>
	<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
			</ul>
	</nav>
	<h2>Search Results:</h2>
	<?php while($row = $statement->fetch()): ?>
			<p><?= $row['RoomType']?></p>
			<p><img src="images\<?= $row['images']?>"</p>
			<p><?= $row['AvailableRoom']?></p>
			<p><?= $row['comment']?></p>
	<?php endwhile?>
</body>
</html>