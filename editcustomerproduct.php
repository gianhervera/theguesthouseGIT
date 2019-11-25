<?php  
	session_start();
	require('connect.php');
	$query = "SELECT * FROM products WHERE productID = '$_GET[productID]'";
	$statement = $db->prepare($query);
	$statement->execute();


	if(isset($_POST['update']))
{
	$productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$productID = filter_input(INPUT_POST, 'productID', FILTER_SANITIZE_NUMBER_INT);

	if((strlen($description) > 0) && (strlen($productName) > 0))
	{
		$query = "UPDATE products SET productName ='$_POST[productName]', description ='$_POST[description]'
		WHERE productID = '$_GET[productID]' ";  

		$statement = $db->prepare($query);
		$statement->bindValue(':productName', $productName);
		$statement->bindValue(':description', $description);
		$statement->bindValue(':productID', $productID , PDO::PARAM_INT);

		$statement->execute();

		header("Location:customerproducts.php");
	}
	else
	{
		header("Location:error.php");
	}
}

if(isset($_POST['delete']))
{
	$productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$productID = filter_input(INPUT_POST, 'productID', FILTER_SANITIZE_NUMBER_INT);

	$query = "DELETE FROM products WHERE productID = '$_GET[productID]'";  
	$statement = $db->prepare($query);
	$statement->bindValue(':productName', $productName);
	$statement->bindValue(':description', $description);
	$statement->bindValue(':productID', $productID , PDO::PARAM_INT);
	$statement->execute();
	unlink('uploads/' .$row['productPicture']);
	header("Location:customerproducts.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Product</title>
</head>
<body>
	<h2>Edit Product</h2>
	<?php while($row = $statement->fetch()): ?>
		<form method="post" onsubmit="return confirm('Confirm Changes?');">      		
			<div>
			<?php if(isset($_SESSION['userID'])): ?>
					<?php if($_SESSION['accountType'] == "Admin"): ?>
						<img src="uploads/<?= $row['productPicture']?>" alt="productPicture" height="200" width="200">
						<p>Username: <?=$row['username']?></p>
						<p>Product Name: <?= $row['productName'] ?></p>
						<p>Description: <?=$row['description']?></p>
						<INPUT id='submit' name='delete' type='submit' value='Delete'>
					<?php else:?>
						<img src="uploads/<?= $row['productPicture']?>" alt="productPicture" height="200" width="200">
						<p>Product Name: <input type ="text" name="productName" value="<?= $row['productName']?>"</p>
						<div>
						<p>Description:</p>
						<textarea name='description' COLS='90' ROWS='10'><?= $row['description'] ?></textarea>
						</div>
						<INPUT id='update' type='submit' name='update' value='Update'>
						<INPUT id='delete' type='submit' name='delete' value='Delete'>
					<?php endif ?>	
			<?php endif ?>
					<div>
      			<button> <a href ="customerproducts.php">Back</a></button>
      		</div>
			</div>
		</form>
	<?php endwhile ?>
</body>
</html>