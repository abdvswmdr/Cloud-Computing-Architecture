<?php
/**
* 	All constants defined in this file
*
*	@author Swinburne University of Technology
*
*	============ READ ME !!! ============ 
*
*	The values of the constant variables with "[ACTION REQUIRED]" in the comment must be updated. The current values are just examples.
*	You need to replace the values of those constant variables with values specific to your setup.
*
*/

// [ACTION REQUIRED] your full name
define('STUDENT_NAME', 'YOUR NAME');
// [ACTION REQUIRED] your Student ID
define('STUDENT_ID', 'YOUR ID');

// [ACTION REQUIRED] name of the S3 bucket that stores images
define('BUCKET_NAME', 'YOUR BUCKET NAME');
// [ACTION REQUIRED] region of the above bucket
define('REGION', 'us-east-1');
// no need to update this const
define('S3_BASE_URL','https://'.BUCKET_NAME.'.s3.amazonaws.com/');

// [ACTION REQUIRED] name of the database that stores file meta-data (note that this is not the DB identifier of the RDS instance)
define('DB_NAME', 'file_manager');
// [ACTION REQUIRED] endpoint of RDS instance
define('DB_ENDPOINT', 'YOUR RDS ENDPOINT');
// [ACTION REQUIRED] username of your RDS instance 
define('DB_USERNAME', 'admin');
// [ACTION REQUIRED] password of your RDS instance
define('DB_PWD', 'password');

// [ACTION REQUIRED] name of the DB table that stores file's meta-data
define('DB_FILE_TABLE_NAME', 'files');
// The table above has 5 columns:
// [ACTION REQUIRED] name of the column in the above table that stores file's names
define('DB_FILE_FILENAME_COL_NAME', 'filename');
// [ACTION REQUIRED] name of the column in the above table that stores file's descriptions
define('DB_FILE_DESCRIPTION_COL_NAME', 'description');
// [ACTION REQUIRED] name of the column in the above table that stores file's creation dates
define('DB_FILE_CREATIONDATE_COL_NAME', 'creationdate');
// [ACTION REQUIRED] name of the column in the above table that stores file's keywords
define('DB_FILE_KEYWORDS_COL_NAME', 'keywords');
// [ACTION REQUIRED] name of the column in the above table that stores file's links in S3 
define('DB_FILE_S3URL_COL_NAME', 's3url');
?>