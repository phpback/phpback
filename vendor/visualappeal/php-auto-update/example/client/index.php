<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>PHP Auto Update</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	</head>
	<body>
		<div class="container">
			<p>This is the test index.</p>

			<p><a class="btn btn-primary" href="update/index.php">Update now!</a></p>

			<p>Contents of "somefile.php":</p>
			<pre><?php require(__DIR__ . '/somefile.php'); ?></pre>
		</div>
	</body>
</html>
