<?php

    require('connect.php');
    session_start();
    $query = "SELECT * FROM room WHERE RoomID = 2";
    $statement = $db->prepare($query);
    $statement->execute();
    


    include 'php-image-resize-master\lib\ImageResize.php';
    use \Gumlet\ImageResize;

    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
       $current_folder = dirname(__FILE__);
       
      
       $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
       
      
       return join(DIRECTORY_SEPARATOR, $path_segments);
    }

    function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png', 'application/pdf'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type        = getimagesize($temporary_path)['mime'];
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }
    
    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);
    if ($image_upload_detected) { 
        $image_filename        = $_FILES['image']['name'];
        $temporary_image_path  = $_FILES['image']['tmp_name'];
        $new_image_path        = file_upload_path($image_filename);
        
        if (file_is_an_image($temporary_image_path, $new_image_path)) {

           if(move_uploaded_file($temporary_image_path, $new_image_path)){
                $newImage = new ImageResize($new_image_path);
                $newImage -> resizeToWidth(400);
                $newImage -> save($new_image_path . '_medium.' . pathinfo($new_image_path, PATHINFO_EXTENSION)  );
                $query1 = "UPDATE room SET images = '".$image_filename."' WHERE RoomID = 2";
                $statement1 = $db->prepare($query1);
                $statement1->bindValue(':images', $image_filename);
                $statement1->execute();
           }
        }
    }

    if($_POST && isset($_POST['comment']) && isset($_POST['name']))
    {
        
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $username = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


            if((strlen($comment) > 0))
                {
                if ($_POST["captcha_code"] == $_SESSION["captcha_code"]) {
                    $query = "INSERT INTO room (comment, name, RoomID) VALUES (:comment, :name, '2')";
                    $statement = $db -> prepare($query);
                    $statement -> bindValue(':comment', $comment);
                    $statement -> bindValue(':name', $username);
                    $statement -> execute();
                    header("Location:double.php");
                }else{
                        echo '<script type="text/javascript">';
                        echo ' alert("Incorrect Captcha Code")'; 
                        echo '</script>';
                     }
                }
            else
             {
            header("Location:error.php");
             }
         
     }

    
?>

<!DOCTYPE html>
<html lang=en>
<head>
    <title>Room Information</title>
</head>
<body>
    <script type="text/javascript" src="ckeditor\ckeditor.js"></script>
    <div id="header">
        <h1>The Guest House: Room Information</h1>
    </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
            </ul>
        </nav>
        <div>
            <h1>Double Room</h1>
        </div>
        <?php if(isset($_SESSION['accountType']) && $_SESSION['accountType'] == "Admin"): ?>
    <form method='post' enctype='multipart/form-data'>
         <label for='image'>Image Filename:</label>
         <input type='file' name='image' id='image'>
         <input type='submit' name='submit' value='Upload Image'>
     </form>
     <?php if ($upload_error_detected): ?>

        <p>Error Number: <?= $_FILES['image']['error'] ?></p>
        <p>Failed to upload!</p>
    <?php elseif ($image_upload_detected): ?>
        <p>Image has been uploaded!</p>
    <?php endif ?>
    <?php else: ?>
<?php endif ?>
        <?php if($row = $statement->fetch()): ?>
        <img src="uploads/<?= $row['images']?>" alt="Double Room height="400" width ="400"">
        </div>
    <?php endif ?>
    <h3>Reviews:</h3>
    <?php while($row = $statement->fetch()): ?>
    <div>
        <div>
            
            <p><?= html_entity_decode($row['comment']) ?></p> 
            <p>Posted By: <?= $row['name']?></p>
            <?php if(isset($_SESSION['userID']) && $_SESSION['username'] == $row['name']): ?>
                    <a href="updateComment.php?commentID=<?= $row['commentID']?>">Edit</a>
                    <?php elseif(isset($_SESSION['userID']) && $_SESSION['accountType'] == "Admin"):?>
                        <a href="updateComment.php?commentID=<?= $row['commentID']?>">Edit</a>
                <?php endif ?>
            <p> <?= date("F d, Y, g:i a", strtotime($row['DateGiven'])); ?></p>   
        </div>
    </div>
    <?php endwhile ?>
     <form method="post" action="double.php">
<?php if(isset($_SESSION['userID']) && $_SESSION['accountType'] == "Admin"): ?>
            <?php elseif(isset($_SESSION['userID'])):?>
            <h2>Comment:</h2>
             <label>Name: <?= $_SESSION['username']?></label>
            <INPUT value= '<?= $_SESSION['username']?>' id='name' name='name'type='hidden'>
            <textarea name='comment' COLS='90' ROWS='10'></textarea>
        <script type="text/javascript">
        CKEDITOR.replace( 'comment' );
        </script>
        <div>
        <img src="captcha.php" /><input type="text" name="captcha_code" 
        />
        <div>
        <INPUT id='submit' type='submit'>
        </div>
        <?php else: ?>
            <h2>Comment:</h2>
            <label>Name:</label>
        <INPUT value= '<?= $row['name']?>' id='name' name='name'>
        <textarea name='comment' COLS='90' ROWS='10'></textarea>
        <script type="text/javascript">
        CKEDITOR.replace( 'comment' );
        </script>
        <div>
        <img src="captcha.php" /><input type="text" name="captcha_code" 
        />
        <div>
        <INPUT id='submit' type='submit'>
        </div>
    <?php endif ?>
</form >

</body>
</html>