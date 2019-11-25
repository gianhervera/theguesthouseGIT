<?php

require('connect.php');


$query = "SELECT * FROM users WHERE userID = '$_GET[userID]'";
$statement = $db->prepare($query);
$statement->execute();  

if(isset($_POST['update']))
{
	$firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $userID = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_NUMBER_INT);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    

    if((strlen($firstname) > 0) && (strlen($lastname) > 0) && (strlen($address) > 0) && (strlen($username) > 0) && (strlen($password) > 0) && (strlen($email) > 0))
    { 
 
      $query = "UPDATE users SET firstname ='$_POST[firstname]', lastname = '$_POST[lastname]', address = '$_POST[address]', username = '$_POST[username]', password = '$_POST[password]', email = '$_POST[email]'
		WHERE userID = '$_GET[userID]' ";  
      
      $statement = $db->prepare($query);

      $statement->bindValue(':firstname', $firstname);
      $statement->bindValue(':lastname', $lastname);
      $statement->bindValue(':address', $address);
      $statement->bindValue(':userID', $userID , PDO::PARAM_INT);
      $statement->bindValue(':username', $username);
      $statement->bindValue(':password', $password);
      $statement->bindValue(':email', $email);

      $statement->execute();
      
      header("Location:customer.php");      
    } 
    else  
    {
        header("Location:error.php");
    } 
}

if(isset($_POST['delete']))
{
	$firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $userID = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_NUMBER_INT);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	$query = "DELETE FROM users WHERE userID = '$_GET[userID]'";  
	$statement = $db->prepare($query);
	$statement->bindValue(':firstname', $firstname);
    $statement->bindValue(':lastname', $lastname);
    $statement->bindValue(':address', $address);
    $statement->bindValue(':userID', $userID , PDO::PARAM_INT);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':email', $email);

	$statement->execute();

	header("Location:customer.php");
}

function call()
{
	header("Location:index.php");
	exit;
}

if(!isset($_GET['userID']) ||   ($_GET['userID']) < 1 || (!is_numeric($_GET['userID'])))
{
	call();
}

?>

<!DOCTYPE html>
<html lang=en>
<head>
	<?php while($row = $statement->fetch()): ?>
	<title>Edit Customer</title>
</head>
<body>
	<h1>Edit Customer</h1>
	<nav>
		<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="insert.php">Register</a></li>
		</ul>
	</nav>
	<div>
		<form method="post" onsubmit="return confirm('Confirm Changes?');">
			<h2>First Name:</h2>
			<INPUT value= '<?= $row['firstname']?>' id='firstname' name='firstname'>
			<h2>Last Name:</h2>
			<INPUT value= '<?= $row['lastname']?>' id='lastname' name='lastname'>
			<h2>Address:</h2>
			<INPUT value= '<?= $row['address']?>' id='address' name='address'>
			<h2>Username:</h2>
			<INPUT value= '<?= $row['username']?>' id='username' name='username'>
			<h2>Password:</h2>
			<INPUT value= '<?= $row['password']?>' id='password' name='password'>
			<h2>Email:</h2>
			<INPUT value= '<?= $row['email']?>' id='email' name='email'>
			<div>
				<INPUT id='update' type='submit' name='update' value='Update Customer'>
				<INPUT id='submit' name='delete' type='submit' value='Delete'>
			</div>
		</form>
	<?php endwhile ?>
	</div>
</body>
</html>