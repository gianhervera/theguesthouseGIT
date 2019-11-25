<?php  
	require('connect.php');
	session_start();

	if($_POST && isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password']))
	{
		 $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


		$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
		$statement = $db->prepare($query);
		$statement->execute();

		if($statement->RowCount() >= 1) 	
		{

			if($row = $statement->fetch()) 
			{

				$_SESSION['userID'] =  $row['userID'];
				$_SESSION['username'] =  $row['username'];
				$_SESSION['firstname'] =  $row['firstname'];
				$_SESSION['accountType'] =  $row['accountType'];
			}
			header("Location:index.php");

		}
		else
		{
			echo '<script language ="javascript">';
        	echo 'alert("Username or Password is incorrect!")'; 
  			echo '</script>';
		}
	}	

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
</head>
<body>
	<div id="header">
		<h1>The Guest House</h1>
	</div>
		<div>
			<form method="post">
			<h2>Username:</h2>
			<INPUT type="text" id='username' name='username' required>
			<h2>Password:</h2>
			<INPUT type="password" id='password' name='password' required>
			</div>
			<div>
       			 <INPUT name='submit' id='submit' type='submit'>
      		</div>
      		<div>
      			<button> <a href ="index.php">Back</a></button>
      		</div>
      	 <p>
			Not yet a member? <a href="insert.php">Sign up</a>
		</p>
		</form>
		</div>
</body>
</html>

</body>
</html>