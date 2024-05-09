
<?php
require_once '../vendor/autoload.php';

use Aws\S3\S3Client;

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'ap-southeast-2',
    'credentials' => [
        'key'    => 'AKIAZI2LI37WZ5FT53DM',
        'secret' => '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',
    ],
]);

try {
    $s3->putObject([
        'Bucket' => 'iuhcongnghemoi',
        'Key'    => 'tailieu/my-file.txt',
        'Body'   => fopen('../uploads/Danh mục.docx', 'r'),
    ]);
    echo "File uploaded successfully.\n";
} catch (Aws\S3\Exception\S3Exception $e) {
    echo "There was an error uploading the file.\n" . $e->getMessage();
}
