<?php 
session_start();
	require('connect.php');
	$query = "SELECT * FROM products";
	$statement = $db->prepare($query);
	$statement->execute();
	
	

    if($_POST && isset($_POST['productName']) && isset($_POST['description']) && isset($_POST['username'])) 
  {
    $productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = $_SESSION['username'];
    $userID = $_SESSION['userID'];

    if((strlen($productName) > 0) && (strlen($description) > 0))
    { 

        $query = "INSERT INTO products (userID, username, productName, description) VALUES (:userID, :username, :productName, :description)";

        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userID);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':productName', $productName);
        $statement->bindValue(':description', $description);
        $statement->execute();
        echo "Product has been added";
    } 
    else  
    {
        echo "Error in adding product!";
    }  
      

  }


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
                $query1 = "UPDATE products SET productPicture = '".$image_filename."' WHERE productID = LAST_INSERT_ID()";
                $statement1 = $db->prepare($query1);
                $statement1->bindValue(':productPicture', $image_filename);
                $statement1->execute();
           }
        }
    }

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Add Product</title>
 </head>
 <body>
 	    <form method='post' enctype='multipart/form-data'>
 	<div>
      <INPUT id='username' name='username' type='hidden'>
    </div>
    <div>
      <label>Product Name:</label>
      <INPUT id='productName' name='productName'>
    </div>
   <label>Description:</label>
   <div>
   <textarea name='description' COLS='90' ROWS='10'></textarea>
   </div>
   


         <label for='image'>Image Filename:</label>
         <input type='file' name='image' id='image'>
          <div>
      <INPUT id='submit' type='submit'>
   </div>
             <button><a href="index.php">Back</a></button>
     </form>
 </body>
 </html>