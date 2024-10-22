<?php
/**
* 	Showing all files in DB
*
*	@author Swinburne University of Technology
*/
ini_set('display_errors', 1);
require 'mydb.php';
require_once 'constants.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="defaultstyle.css">
		<title>File Album</title>
	</head>
	<body>
		<h4>Student name: <?php echo STUDENT_NAME; ?></h4>
		<h4>Student ID: <?php echo STUDENT_ID; ?></h4>
		<h3>Uploaded files:</h3>
		<table id="file_table" border = "1">
		  <tr>
			<th>File</th>
			<th>Name</th> 
			<th>Description</th>
			<th>Creation date</th>
			<th>Keywords</th>
		  </tr>
		<?php 
		$my_db = new MyDB();
		$files = $my_db->getAllFiles();
		foreach ($files as $file) {
			echo "<tr><td><a href='".$file->getS3Reference()."'>".$file->getS3Reference()."</a></td><td>".$file->getName()."<td>".$file->getDescription()."</td><td>".$file->getCreationDate()."</td><td>".$file->getKeywords()."</td></tr>";
		}
		?>
		</table>
	</body>
</html>