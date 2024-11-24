<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="defaultstyle.css">
	<title>File Manager</title>
</head>
<body>
	<h1>File uploader</h1>
	<div class="form_border">
		<form action="file_uploader.php" method="POST" enctype="multipart/form-data">
			<label>
				<strong>Filename: </strong> 
				<input type="text" maxlength="100" name="filename_field" id="filename_field" required>
			</label><br/><br/>
			<label>
				<strong>Select a file:</strong> 
				<input type="file" name="file_field" id="file_field" required>
			</label><br/><br/>
			<label>
				<strong>Description:</strong> 
				<input type="text" maxlength="100" name="description_field" id="description_field" required>
			</label><br/><br/>
			<label>
				<strong>Date:</strong> 
				<input type="date" maxlength="100" name="date_field" id="date_field" required>
			</label><br/><br/>
			<label>
				<strong>Keywords (comma-delimited, e.g. keyword1, keyword2, ...):</strong> 
				<input type="datetime" maxlength="100" name="keywords_field" id="keywords_field" required>
			</label><br/><br/>
			<input type="submit" value="Upload" />
		</form>
		<div id="error_msg" style="display: {{error_msg_visibility}} !important;" >{{error_msg}}</div>
		<div id="success_msg" style="display: {{success_msg_visibility}} !important;">{{success_msg}}</div>
	</div>
	<br>
	<a href="get_files.php">File Manager</a>
</body>
</html>
