<?php
/**
* 	File uploading functionality
*
*	@author Swinburne University of Technology
* 
*/
ini_set('display_errors', 1);
require 'utils.php';
require_once 'constants.php';
require 'mydb.php';
require 'vendor/autoload.php';

use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client; 
use Aws\Lambda\LambdaClient;

// get the html template file and make it global
$GLOBALS['html_template'] = file_get_contents ("upload.php");

if (isset($_FILES["file_field"]["name"]) && isset($_POST ['filename_field']) && isset($_POST ['description_field']) && isset($_POST ['date_field']) && isset($_POST ['keywords_field'])) {
	$utils = new Utils();
	$target_dir = "/uploads/";
	
	$title = $_POST["filename_field"];
	$file_name = basename($_FILES["file_field"]["name"]);
	$description = $_POST["description_field"];
	$creation_date = $_POST["date_field"];
	$keywords = $_POST["keywords_field"];
	
	// check if the selected file is an actual image file and if all input fields are filled
	$is_inputs_valid = true;
	if(isset($_POST["submit"])) {
		if(empty($title) || empty($description) || empty($creation_date) || empty($keywords)){
			$GLOBALS['html_template'] = $utils->showErrorMsg($GLOBALS['html_template'], "All input fields must not be empty.");
			$is_inputs_valid = false;
		}
	}
	
	// if all input fields are valid then proceed to uploading file
	if ($is_inputs_valid) {
		// upload file to a temporary folder called 'uploads' on EC2
		// (it is possible to directly upload files to S3 using presigned URLs but this way is easier to implement at this stage)
		if (move_uploaded_file($_FILES["file_field"]["tmp_name"], dirname(__FILE__).$target_dir.$file_name)) {
			// set up S3 client
			$s3 = new Aws\S3\S3Client([
				'version'     => 'latest',
				'region'      => REGION
			]);
			//set up Lambda client
			$lambda = new Aws\Lambda\LambdaClient([
				'version' => 'latest',
				'region'  => REGION
			]);
			// perform the upload.
			try {
				//upload file to s3
				$uploaded_file = fopen(dirname(__FILE__).$target_dir.$file_name, 'rb');
				
				try {
					// Upload data.
					$result = $s3->putObject([
						'Bucket' => BUCKET_NAME,
						'Key'    => $file_name,
						'Body'   => $uploaded_file
					]);
				} catch (S3Exception $e) {
					echo $e->getMessage() . PHP_EOL;
				}
				
				//then insert meta-data into DB in RDS
				$my_db = new MyDB();
				$s3url = $file_name;
				$s3zipurl = "zipped-" . $file_name . ".zip";
				$file = new File($title, $description, $creation_date, $keywords, $s3url, $s3zipurl);
				$new_id = $my_db->addFile($file);
				
				//then invoke Lambda function
				$payload = "{\"bucketName\":\"".BUCKET_NAME."\",\"fileName\":\"".$file_name."\"}";
				$lambda_invoke_result = $lambda->invoke(array(
					// FunctionName is required
					'FunctionName' => LAMBDA_FUNC_NAME,
					'InvocationType' => 'Event',
					'LogType' => 'Tail',
					'Payload' => $payload
				));
				if(!empty(json_decode((string) $lambda_invoke_result->get('Payload')))){
					throw new Exception("Lambda function invoked and executed. ".json_decode((string) $lambda_invoke_result->get('Payload')));
				}
				//redirect users to get_files.php once upload completed
				header("Location: get_files.php");
				exit();
			} catch (Exception $e){
				$GLOBALS['html_template'] = $utils->showErrorMsg($GLOBALS['html_template'], $e->getMessage() . PHP_EOL);
			}
		} else {
			$GLOBALS['html_template'] = $utils->showErrorMsg($GLOBALS['html_template'], "Sorry, there was an error uploading your file. Error code:".$_FILES["file_field"]["error"]);
		}
	}
}

//print out the html template
echo $GLOBALS['html_template'];
?>