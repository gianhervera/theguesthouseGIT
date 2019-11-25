<?php

  require('connect.php');


  if($_POST && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['address']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['email'])) 
  {
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $accountType = filter_input(INPUT_POST, 'accountType', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if((strlen($firstname) > 0) && (strlen($lastname) > 0) && (strlen($address) > 0) && (strlen($username) > 0) && (strlen($password) > 0) && (strlen($email) > 0) && ($password == $password2))
    { 
      $password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (firstname, lastname, address, username, password, email, accountType) VALUES (:firstname, :lastname , :address, :username, :password, :email, :accountType)";

        $statement = $db->prepare($query);

        $statement->bindValue(':firstname', $firstname);
        $statement->bindValue(':lastname', $lastname);
        $statement->bindValue(':address', $address);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':accountType', $accountType);

        $statement->execute();
        header("refresh:.5; url=index.php");
        echo '<script language ="javascript">';
        echo 'alert("Registered Successfully!")'; 
  		echo '</script>';
     
    } 
    else  
    {
        echo '<script language ="javascript">';
        echo 'alert("Registration Error!")'; 
  		echo '</script>';
    }  
      

  }



?>

<!DOCTYPE html>
<html lang=en>
<head>
  <title>Account Registration</title>
  <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
  <div>
    <h1>New Account</h1>
  </div>
  <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
      </ul>
    </nav>
  <div>
    <form method="post" action="insert.php">
    <div>
      <label>First Name:</label>
      <INPUT id='firstname' name='firstname'>
    </div>
    <div>
      <label>Last Name:</label>
      <INPUT id='lastname' name='lastname'>
    </div>
    <div>
      <label>Address:</label>
      <INPUT id='address' name='address'>
    </div>
      <label>Email:</label>
      <INPUT type ='email' id='email' name='email'>
    <div>
      <label>Username:</label>
      <INPUT id='username' name='username'>
    </div>
    <div>
      <label>Password:</label>
      <INPUT type ='password' id='password' name='password'>
    </div>
    <div>
      <label>Confirm Password:</label>
      <INPUT type ='password' id='password' name='password2'>
    </div>
      <INPUT type ='hidden' value='Customer' id ='accountType' name='accountType'>
    <div>
      <INPUT id='submit' type='submit'>
   </div>
    </form>
      <div>
        Already Registered? <a href="login.php">Sign In</a>
      </div>
  </div>
<script src="main.js"></script>
</body>
</html>