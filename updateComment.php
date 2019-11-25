<?php
require('connect.php');
$query = "SELECT * FROM room WHERE commentID = '$_GET[commentID]'";
$statement = $db->prepare($query);
$statement->execute();  
session_start();

if(isset($_POST['update']))
{
	$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$commentID = filter_input(INPUT_POST, 'commentID', FILTER_SANITIZE_NUMBER_INT);

	if((strlen($comment) > 0))
	{
		$query = "UPDATE room SET comment ='$_POST[comment]'
		WHERE commentID = '$_GET[commentID]' ";  

		$statement = $db->prepare($query);
		$statement->bindValue(':comment', $comment);
		$statement->bindValue(':commentID', $commentID , PDO::PARAM_INT);

		$statement->execute();

		header("Location:single.php");
	}
	else
	{
		header("Location:error.php");
	}
}

if(isset($_POST['delete']))
{
	$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$commentID = filter_input(INPUT_POST, 'commentID', FILTER_SANITIZE_NUMBER_INT);

	$query = "DELETE FROM room WHERE commentID = '$_GET[commentID]'";  
	$statement = $db->prepare($query);
	$statement->bindValue(':comment', $comment);
	$statement->bindValue(':commentID', $commentID , PDO::PARAM_INT);

	$statement->execute();

	header("Location:single.php");
}

function call()
{
	header("Location:index.php");
	exit;
}

if(!isset($_GET['commentID']) ||   ($_GET['commentID']) < 1 || (!is_numeric($_GET['commentID'])))
{
	call();
}

?>

<!DOCTYPE html>
<html lang=en>
<head>
	<title>Edit Comment</title>
	
</head>
<body>
	<h1>Edit Comment</h1>
	<nav>
		<ul>
				<li><a href="index.php">Home</a></li>
				<script type="text/javascript" src="ckeditor\ckeditor.js"></script>
		</ul>
	</nav>
	<div>
		<?php while($row = $statement->fetch()): ?>
		<form method="post" onsubmit="return confirm('Confirm Changes?');">
			<h2>Comment</h2>        
			
			<div>
			<?php if(isset($_SESSION['userID'])): ?>
					<?php if($_SESSION['accountType'] == "Admin"): ?>
						<p><?= html_entity_decode($row['comment'])?></p>
						<p>Posted by: <?= $row ['name'] ?></p>
						<INPUT id='submit' name='delete' type='submit' value='Delete'>
					<?php else:?>
						<textarea name='comment' COLS='90' ROWS='10'><?= html_entity_decode($row['comment']) ?></textarea>
			<script type="text/javascript">
        CKEDITOR.replace( 'comment' );
      </script>
						<INPUT id='update' type='submit' name='update' value='Update Comment'>
					<?php endif ?>	
			<?php endif ?>
					<div>
      			<button> <a href ="index.php">Back</a></button>
      		</div>
			</div>
		</form>
	<?php endwhile ?>
	</div>
</body>
</html>