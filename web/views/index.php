<!DOCTYPE html>
<html lang="en">
<head>
	<title>Dockerized API</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/css/homepage.css" >
</head>
<body>
<?php
require_once _PROJECT_PATH_."/views/parts/sidenav.php";
?>

<div class="content">
	<?php
	
		require_once _PROJECT_PATH_."/inc/config.php";

		echo "<h1>Welcome to Dockerized REST API</h1>";

		echo"<ul>

				<p>Trying to connect:: ".DB_HOST.":".DB_PORT."</p>
				<p><b>Credentials Used</b><br>
				User     : ".DB_USERNAME."<br>
				Password : ".DB_PASSWORD."
				</p>";
						
			 
		$conn = new mysqli(DB_HOST.":".DB_PORT, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
		// Test connection
		if ($conn->connect_error) {
			echo "<ul>";
			die("<h3 class=\"msgERR\">Failed to connect " . $conn->connect_error."<h3>");
		}
		else{
			echo "<h3 class=\"msgOK\"> Success connection test </h3>";
			echo"<p>Host IP  : ".DB_HOST."</p>
				 <p>Host port: ".DB_PORT."</p>";



			if (!($result=mysqli_query($conn,'SHOW TABLES'))){
				echo"Error: ".mysqli_error($conn)."<ul>";
			}else{
				include_once _PROJECT_PATH_.'/AutoLoader.php';

				echo "<h3>Databases inside `".DB_DATABASE_NAME."`</h3>";
				
				while($row = mysqli_fetch_row( $result ))
					echo "- ".$row[0]."<br/>";
				echo "<ul>";

				$result -> free_result();
			}
		}
		$conn->close();
	?>

</div>



</body>
</html>

