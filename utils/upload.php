<?php
if ($_FILES) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    // Allow only certain file extensions
    $allowed = array('jpg', 'jpeg', 'png', 'gif');
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize <= 2 * 1024 * 1024) { // 2MB
                $fileNameNew = uniqid('', true) . "." . $fileExt;
                $fileDestination = 'uploads/' . $fileNameNew;

                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    echo json_encode(["status" => "success", "message" => "File uploaded successfully: $fileNameNew"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error uploading file."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "File is too large. Max size is 2MB."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Error uploading file."]);
            
        }
    } else {
        echo json_encode(["status" => "error", "message" => "File type not allowed."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No file uploaded."]);
}
?>
