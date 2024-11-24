<?php
/**
* 	Showing all files in DB
*
*	@author Swinburne University of Technology
*/
ini_set('display_errors', 1);
require 'mydb.php';
require_once 'constants.php';
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Initialize S3 client
$s3 = new S3Client([
    'version' => 'latest',
    'region'  => REGION
]);

// Function to generate a pre-signed URL
function generatePresignedUrl($s3, $bucketName, $objectKey, $expiration = '+10 minutes') {
    try {
        // Create a pre-signed URL for the object in S3
        $cmd = $s3->getCommand('GetObject', [
            'Bucket' => $bucketName,
            'Key'    => $objectKey
        ]);

        // Generate the request with expiration time
        $request = $s3->createPresignedRequest($cmd, $expiration);

        // Return the pre-signed URL as a string
        return (string) $request->getUri();
    } catch (AwsException $e) {
        echo "Error generating pre-signed URL: " . $e->getMessage() . PHP_EOL;
        return null;
    }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="defaultstyle.css">
		<title>File Manager</title>
	</head>
	<body>
		<h4>Student name: <?php echo STUDENT_NAME; ?></h4>
		<h4>Student ID: <?php echo STUDENT_ID; ?></h4>
		<h3>Uploaded files:</h3>
		<a href="file_uploader.php">Upload more files</a><br/><br/>
		<table id="file_table" border = "1">
		  <tr>
			<th>File</th>
			<th>Zipped File</th>
			<th>Name</th> 
			<th>Description</th>
			<th>Creation date</th>
			<th>Keywords</th>
		  </tr>
		<?php 
		$my_db = new MyDB();
		$files = $my_db->getAllFiles();
		foreach ($files as $file) {
			$s3Url = generatePresignedUrl($s3, BUCKET_NAME, $file->getS3Url());
			$s3ZipUrl = generatePresignedUrl($s3, BUCKET_NAME, $file->getS3ZipUrl());
			
			echo "
			<tr>
				<td><a href=".$s3Url.">Download File</a></td>
				<td><a href=".$s3ZipUrl.">Download Zipped File</a></td>
				<td>".$file->getName()."</td>
				<td>".$file->getDescription()."</td>
				<td>".$file->getCreationDate()."</td>
				<td>".$file->getKeywords()."</td>
			</tr>
			";
		}
		?>
		</table>
	</body>
</html>