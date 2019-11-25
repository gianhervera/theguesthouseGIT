<?php

	require('connect.php');
	$query = "SELECT * FROM users";
	$statement = $db->prepare($query);
	$statement->execute();

?>

<!DOCTYPE html>
<html lang=en>
<head>
	<title>Customer Registration</title>
</head>
<body>
	<div id="header">
		<h1>The Guest House</h1>
	</div>
		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
			</ul>
		</nav>

	<p>Registered Users:</p>
	<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for Last Name..">

	<div>
		<table id="myTable">
		
			<tr>
				<th>Account ID</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Address</th>
				<th>Username</th>
				<th>Password</th>
				<th>Email</th>
				<th>Account Type</th>
			</tr>
				<?php while($row = $statement->fetch()): ?>
			<tr>
				<td><a href="update.php?userID=<?= $row['userID']?>">Update</a></td>
				<td><?= $row['firstname'] ?></td>
				<td><?= $row['lastname'] ?></td>
				<td><?= $row['address'] ?></td>
				<td><?= $row['username'] ?></td>
				<td><?= $row['password'] ?></td>
				<td><?= $row['email'] ?></td>
				<td><?= $row['accountType'] ?></td>
			</tr>	
				<?php endwhile ?>
		</table>
	</div>
	<script>
	function myFunction() {
	  var input, filter, table, tr, td, i, txtValue;
	  input = document.getElementById("myInput");
	  filter = input.value.toUpperCase();
	  table = document.getElementById("myTable");
	  tr = table.getElementsByTagName("tr");


	  for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[2];
	    if (td) {
	      txtValue = td.textContent || td.innerText;
	      if (txtValue.toUpperCase().indexOf(filter) > -1) {
	        tr[i].style.display = "";
	      } else {
	        tr[i].style.display = "none";
	      }
	    }
	  }
	}
	</script>  
</body>
</html>