<?php
/**
* 	Interacting with MySQL DB in RDS
*
*	@author Swinburne University of Technology
*/
require 'file.php';
require_once 'constants.php';

class MyDB 
{
	private $dbh; 
	
	// Constructor, establish a connection to the database in RDS
	public function __construct() {
		try {
			$dsn = "mysql:host=".DB_ENDPOINT.";dbname=".DB_NAME;
			$this->dbh = new PDO ( $dsn, DB_USERNAME, DB_PWD );
			$this->dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			error_log($e);
			echo $e;
		}
	}
	
	// Retrieve all records stored in the database table DB_FILE_TABLE_NAME. Return an array of File objects
	public function getAllFiles() {
		$files = array ();
		try {
			$stm = $this->dbh->query ( 'SELECT * FROM ' . DB_FILE_TABLE_NAME );
			foreach ( $stm as $row ) {
				array_push ( $files, new File ( $row [DB_FILE_FILENAME_COL_NAME], 
												$row [DB_FILE_DESCRIPTION_COL_NAME],
												$row [DB_FILE_CREATIONDATE_COL_NAME],
												$row [DB_FILE_KEYWORDS_COL_NAME],
												$row [DB_FILE_S3URL_COL_NAME]) );
			}
			return $files;
		} catch ( PDOException $e ) {
			error_log($e);
			echo $e;
		}
	}
}
?>