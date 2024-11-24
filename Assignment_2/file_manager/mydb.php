<?php
/**
* 	Interacting with MySQL DB in RDS
*
*	@author Swinburne University of Technology
*/
require 'file.php';
require_once 'constants.php';
require_once 'utils.php';

class MyDB 
{
	private $dbh;
	private $utils;
	
	public function __construct() {
		try {
			$this->utils = new Utils();
			$dsn = "mysql:host=".DB_ENDPOINT.";dbname=".DB_NAME;
			$this->dbh = new PDO ( $dsn, DB_USERNAME, DB_PWD );
			$this->dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			error_log($e);
			$GLOBALS['html_template'] = $this->utils ->showErrorMsg($GLOBALS['html_template'], $e->getMessage() . PHP_EOL);
		}
	}
	
	public function getAllFiles() {
		$files = array ();
		try {
			$stm = $this->dbh->query ( 'SELECT * FROM ' . DB_FILE_TABLE_NAME );
			foreach ( $stm as $row ) {
				array_push ( $files, new File ( $row [DB_FILE_FILENAME_COL_NAME], 
												$row [DB_FILE_DESCRIPTION_COL_NAME],
												$row [DB_FILE_CREATIONDATE_COL_NAME],
												$row [DB_FILE_KEYWORDS_COL_NAME],
												$row [DB_FILE_S3URL_COL_NAME],
												$row [DB_FILE_S3ZIPURL_COL_NAME]) );
			}
			return $files;
		} catch ( PDOException $e ) {
			error_log($e);
			$GLOBALS['html_template'] = $this->utils ->showErrorMsg($GLOBALS['html_template'], $e->getMessage() . PHP_EOL);
		}
	}
	
	public function addFile($file) {
		try {
			$sql = "INSERT INTO ".DB_FILE_TABLE_NAME." (".DB_FILE_FILENAME_COL_NAME.", ".DB_FILE_DESCRIPTION_COL_NAME.", ".DB_FILE_CREATIONDATE_COL_NAME.", ".DB_FILE_KEYWORDS_COL_NAME.", ".DB_FILE_S3URL_COL_NAME .", ".DB_FILE_S3ZIPURL_COL_NAME .") VALUES(?,?,?,?,?,?);";
			$this->dbh->prepare ( $sql )->execute ( [
					$file->getName(),
					$file->getDescription(),
					$file->getCreationDate(),
					$file->getKeywords(),
					$file->getS3Url(),
					$file->getS3ZipUrl(),
			] );
			return $this->dbh->lastInsertId();
		} catch ( PDOException $e ) {
			error_log($e);
			$GLOBALS['html_template'] = $this->utils ->showErrorMsg($GLOBALS['html_template'], $e->getMessage() . PHP_EOL);
		}
	}
	
	public function getFileByName($name){
		try {
			$stmt = $this->dbh->prepare("SELECT * FROM ".DB_FILE_TABLE_NAME." WHERE ".DB_FILE_FILENAME_COL_NAME."='$name' LIMIT 1");
			$stmt->execute();
			$row = $stmt->fetch();
			if($row){
				$file = new File ( $row [DB_FILE_FILENAME_COL_NAME], $row [DB_FILE_DESCRIPTION_COL_NAME]) ;
				return $file;
			}else{
				return null;
			}
		} catch ( PDOException $e ) {
			error_log($e);
			$GLOBALS['html_template'] = $this->utils ->showErrorMsg($GLOBALS['html_template'], $e->getMessage() . PHP_EOL);
		}
	}
}
?>