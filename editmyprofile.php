<?php
    require('connect.php');
    session_start();
    $query = "SELECT * FROM users where userID = '$_GET[userID]'"; 
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
                $query1 = "UPDATE users SET profilepicture = '".$image_filename."' WHERE userID = '$_SESSION[userID]'";
                $statement1 = $db->prepare($query1);
                $statement1->bindValue(':profilepicture', $image_filename);
                $statement1->execute();
           }
        }
    }
    if(isset($_POST['update']))
    {
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $userID = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_NUMBER_INT);

        if((strlen($firstname) > 0) && (strlen($lastname) > 0) && (strlen($email) > 0) && (strlen($address) > 0) && (strlen($description) > 0))
        {
            $query = "UPDATE users SET firstname ='$_POST[firstname]', lastname ='$_POST[lastname]', email ='$_POST[email]', address ='$_POST[address]', description ='$_POST[description]'
            WHERE userID = '$_GET[userID]' ";  

            $statement = $db->prepare($query);
            $statement->bindValue(':firstname', $firstname);
            $statement->bindValue(':lastname', $lastname);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':address', $address);
            $statement->bindValue(':description', $description);
            $statement->bindValue(':userID', $userID , PDO::PARAM_INT);

            $statement->execute();

            header("Location:myprofile.php");
        }
        else
        {
            header("Location:error.php");
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>
    <form method="post" onsubmit="return confirm('Confirm Changes?');">
        <?php if($row = $statement->fetch()):?>
            <img src="uploads/<?= $row['profilepicture']?>" alt="profilepicture" height="200" width="200">
            <p>First name: <input type ="text" name="firstname" value="<?= $row['firstname']?>"</p>
            <p>Last Name: <input type ="text" name="lastname" value="<?= $row['lastname']?>"</p>
            <p>Email: <input type ="text" name="email" value="<?= $row['email']?>"</p>
            <p>Address: <input type ="text" name="address" value="<?= $row['address']?>"</p>
            <p>Description:</p>
            <textarea name='comment' COLS='90' ROWS='10'><?= $row['description'] ?></textarea></br>
              <INPUT id='update' type='submit' name='update' value='Update Profile'></INPUT>
        </form>
        <?php endif ?>
        <?php if(isset($_SESSION['userID'])): ?> 
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
         <?php endif ?>
       
         <button><a href="myprofile.php">Back</a></button>
  
</body>
</html>