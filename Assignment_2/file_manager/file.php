<?php
/**
* 	File class
*
*	@author Swinburne University of Technology
*/
class File 
{
	
	private $name;
	private $description;
	private $creation_date;
	private $keywords;
	private $s3_url;
	private $s3_zip_url;
	
	public function __construct($name, $description, $creation_date, $keywords, $s3_url, $s3_zip_url) {
		$this->name = $name;
		$this->description = $description;
		$this->creation_date = $creation_date;
		$this->keywords = $keywords;
		$this->s3_url = $s3_url;
		$this->s3_zip_url = $s3_zip_url;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($value) {
		$this->name = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getCreationDate() {
		return $this->creation_date;
	}
	
	public function setCreationDate($value) {
		$this->creation_date = $value;
	}
	
	public function getKeywords() {
		return $this->keywords;
	}
	
	public function setKeywords($value) {
		$this->keywords = $value;
	}
	
	public function getS3Url() {
		return $this->s3_url;
	}
	
	public function setS3Url($value) {
		$this->s3_url = $value;
	}
	
	public function getS3ZipUrl() {
		return $this->s3_zip_url;
	}
	
	public function setS3ZipUrl($value) {
		$this->s3_zip_url = $value;
	}
}
?>