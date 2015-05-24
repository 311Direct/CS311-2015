<?php

// Create upload directory (if not exists yet)
$target_dir = '../uploads/' . $_POST['itemId'] . '/';
if (!file_exists($target_dir)) {
	if (!mkdir($target_dir)) {
		error_log('Failed to create upload directory for project: ' . $_POST['itemId']);
		die();
	}
}

// Set uploaded file path
$fileName = preg_replace('/\s+/', '-', $_FILES["fileToUpload"]["name"]);	// Remove spaes from filename, otherwise the path will be invalid
$target_file = $target_dir . basename($fileName);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$uploadOk = 1;

// Check if file already exists
if (file_exists($target_file)) {
    ?>
<pre>
function upload() {
	hmm();
	hmmmm();
	return "Please give your file a different name," +
		"Then <a href=<?php echo '"' . $_POST['originalUrl'] . '"'; ?>>here</a> and try again, cheers.";
}
</pre>
    <?php
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk != 0) {
    // Insert uploaded file into db
    session_start();
    $mysqli = new mysqli('localhost', 'vitawebs_csci311', 'hoaisking1337', 'vitawebs_csci311_v2');
    
    // Prepare upload...
    $dlPath = substr($target_file, 3);
    $fileSize = ceil(intVal($_FILES["fileToUpload"]["size"]) / 1024 / 1024);
    $forType = $_POST['itemType'];
    $id = $_POST['itemNum'];
    // Upload attachment
    $sql = 'INSERT INTO attachment (url, title, type, forType, forId) ' .
        "VALUES ('$dlPath', '$fileName', '$fileSize MB', '$forType', $id)";
    $success = $mysqli->query($sql);    

    if ($success == true && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	header('Location: ' . $_POST['originalUrl']);
    } else {
    ?>
<pre>
function upload() {
	hmm();
	hmmmm();	
	sigh();
	return "You appear to be on a slow network" +
		"Refresh the page to try again";
		
	while (true)
		fork();
}
</pre>
    <?php
    }
}