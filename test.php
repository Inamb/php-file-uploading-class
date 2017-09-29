<?php 

require 'Upload.php';

if (isset($_POST['submit'])) {
	$a = new Upload("file","uploads/",10,["png"]);
	if ($a->do_upload()) {
		die($a->file_name());
	}else{
		foreach ($a->errors() as $error) {
			echo $error."<br>";
		}
		die();
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<form method="POST" enctype="multipart/form-data">
		<input type="file" name="file" accept="image/*">
		<br><br>
		<input type="submit" name="submit">
	</form>

</body>
</html>