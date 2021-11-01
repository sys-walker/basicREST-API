<!DOCTYPE html>
<html lang="en">
<head>
	<title>Dockerized API</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		.msgINFO {
		    color: #00529B;
		}
		.msgOK {
		    color: #4F8A10;
		}
		.msgWARN{
		    color: #9F6000;
		}
		.msgERR {
		    color: #D8000C;
		}
	</style>
</head>
<body>
	<?php
	
		require_once "/var/www/html/inc/config.php";

		echo "<h1>Welcome to Dockerized REST API built with MySQL and PHP</h1>";


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
				include_once '/var/www/html/AutoLoader.php';

				echo "<h3>Databases inside `".DB_DATABASE_NAME."`</h3>";
				
				while($row = mysqli_fetch_row( $result ))
					echo "- ".$row[0]."<br/>";
				echo "<ul>";

				$result -> free_result();
			}
		}
		$conn->close();

	?>
	  <p><a href="/phpMyAdmin">phpMyAdmin</a></p>
	<p><a href="instructions.php">Api instructions</a></p>



</body>
</html>

