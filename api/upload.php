<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
$targetDir = '../uploads/';
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}
// Check if the file has been uploaded properly
if ($_FILES) {
    // Process each file
    foreach ($_FILES as $file) {
        // Check for errors
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Validate file size or other properties here
            if ($file['size'] <= 5000000) { // example limit: 5MB
                $targetFilePath = $targetDir . basename($file['name']);
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                // Validate file type, for example, allow only JPEG images
                if (in_array($fileType, ['jpg', 'jpeg', 'png'])) {
                    // Move the file to the target upload directory
                    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                        echo json_encode("File " . htmlspecialchars($file['name']) . " tải thành công.");
                    } else {
                        http_response_code(409);
                        echo json_encode("Không thể tải file " . htmlspecialchars($file['name']));
                    }
                } else {
                    http_response_code(409);
                    echo json_encode("File không hợp lệ " . htmlspecialchars($file['name']));
                }
            } else {
                echo "File " . htmlspecialchars($file['name']) . " File quá lớn";
            }
        } else {
            echo "Error uploading file " . htmlspecialchars($file['name']) . ". Error code: " . $file['error'];
        }
    }
} else {
    echo "No files received.";
}
