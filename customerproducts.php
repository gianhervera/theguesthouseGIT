<?php  
	session_start();
	require('connect.php');
	

	$orderBy = !empty($_GET["orderby"]) ? $_GET["orderby"] : "productName";
    $order = !empty($_GET["order"]) ? $_GET["order"] : "asc";
 
    $query = "SELECT * FROM products ORDER BY " . $orderBy . " " . $order;
  
    $statement = $db->prepare($query);
	$statement->execute();
  
    $product = "asc";
    $customer = "asc";
    $date = "asc";
  
    if($orderBy == "productName" && $order == "asc") {
      $product = "desc";
    }
    if($orderBy == "username" && $order == "asc") {
      $customer = "desc";
    }
     if($orderBy == "DateGiven" && $order == "asc") {
      $date = "desc";
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Customer Products</title>
</head>
<body>
	<h1><a href="index.php">Home</a></h1>
	<?php if(isset($_SESSION['userID'])):?>

<table>
  <thead>
  	<tr><th>Sort By:</th></tr>
    <tr>
      <th><a href="?orderby=productName&order=<?=  $product ?>">Product Name</a></th>
      <th><a href="?orderby=username&order=<?= $customer ?>">Submitted Customer</a></th>
      <th><a href="?orderby=DateGiven&order=<?= $date ?>">Date</a></th>
    </tr>
  </thead>
  	</table>
  <?php endif?>
  
	<?php while($row = $statement->fetch()): ?>
	<ul>
		<li><p>Submitted by Customer: <?= $row['username'] ?></p></li>
		<p>Product Name: <?= $row['productName'] ?></p>
		<p>Product Description: <?= $row['description'] ?></p>
		<p>Date: <?= $row['DateGiven'] ?></p>
		<p>Product Image:</p>
		<img src="uploads/<?= $row['productPicture']?>" alt="Product" height="400" width ="400">
		<?php if(isset($_SESSION['userID']) && $_SESSION['userID'] == $row['userID']): ?>
             <a href="editcustomerproduct.php?productID=<?= $row['productID']?>">Edit</a>
             <?php elseif(isset($_SESSION['userID']) && $_SESSION['accountType'] == "Admin"):?>
             <a href="editcustomerproduct.php?productID=<?= $row['productID']?>">Edit</a>
                <?php endif ?>
	</ul>
	<?php endwhile?>
</body>
</html>