<?php
	session_start();
	require('connect.php');
	$query = "SELECT * FROM users";
	$statement = $db->prepare($query);
	$statement->execute();
	
?>

<!DOCTYPE html>
<html lang=en>
<head>
	<title>The Guest House</title>
	<link rel="stylesheet" type="text/css" href="main.css">
	<script type="text/javascript" src="main.js"></script>
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
			<?php if(isset($_SESSION['userID'])): ?>
				<li><a href="logout.php">Logout</a></li>
				<p>Welcome, <?= $_SESSION['firstname'] ?>! </p>
				<li><a href="myprofile.php?userID=<?= $_SESSION['userID']?>">My Account</a></li>
			<?php if($row = $statement->fetch()): ?>
				<?php if($_SESSION['userID'] == $row['userID'] && $row['accountType'] == "Admin"): ?>
						<li><a href="customer.php">Registered Accounts</a></li>
						<?php else: ?>
					<?php endif ?>
				<?php endif ?>
			<?php else: ?>
				<li><a href="insert.php">Register</a></li>
				<li><a href="login.php">Login</a></li>
			<?php endif ?>
			</ul>
		</nav>

		
	
		<h1>ROOMS</h1>
		<?php if(isset($_SESSION['userID']) && $_SESSION['accountType'] != "Admin"): ?>
		<li><a href="product.php">Add Product</a></li>
	<?php endif ?>
		<div id="rooms">
			<ul>
			<li><h3 class ="name"><a href="single.php">Single Room</a></h3></li>
			<ul>
				<li><img src="images\single.jpg" height="200" widht="200"></li>
				<li>Furnished room</li>
				<li>Single bed & linens</li>
				<li>$500</li>
			</ul>	
			<li><h3 class ="name"><a href="double.php">Double Room</a></h3></li>
			<ul>
				<li><img src="images\double.jpg" height="200" widht="200"></li>
				<li>Furnished room</li>
				<li>Double bed & linens</li>
				<li>$1000</li>
			</ul>	
			<li><h3 class ="name"><a href="king.php">King Room</a></h3></li>
			<ul>
				<li><img src="images\king.jpg" height="200" widht="200">
				<li>Furnished room</li>
				<li>Twin bed and single bed.</li>
				<li>$1500</li>
			</ul>
			<li><h3 class ="name"><a href="queen.php">Queen Room</a></h3></li>
			<ul>
				<li><img src="images\queen.jpg" height="200" widht="200">
				<li>Furnished room</li>
				<li>Twin bed and single bed.</li>
				<li>$1500</li>
			</ul>
			</ul>
		</div>
		<h1>Products:</h1>
		<div>
			<ul>
				<li>Soap - $2</li>
				<li>Shampoo - $3</li>
				<li>Blanket - $8</li>
				<li>Pillow - $5</li>
			</ul>
		</div>
		<div><h3><a href="customerproducts.php">Customer Added Products</a></h3></div>

		<div class="pagination">
		  <a href="#">&laquo;</a>
		  <a href="#">1</a>
		  <a class="active" href="#">2</a>
		  <a href="#">3</a>
		  <a href="#">4</a>
		  <a href="#">5</a>
		  <a href="#">6</a>
		  <a href="#">&raquo;</a>
		</div>
</body>
</html>