<?php
	//The autoloader of dockerized api
	//loads the deafault table
	require_once "/var/www/html/inc/config.php";
	echo "<h2>AutoLoader information:</h2>";
	$connection = new mysqli(DB_HOST.":".DB_PORT, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
	 if (!$connection) {
		die("<h3 class=\"msgERR\">AutoLoader has failed: " . mysqli_error($connection)."</h3>");
	}else{
		if (exists_default_table($connection)) {
			echo "<p class=\"msgINFO\">[INFO]: default table `it_languages` exists</p>";
		} else {
			echo "<p class=\"msgERR\">[ERROR]:The default table not exists!</p>";
			create_default_table($connection);
		}
		if (is_empty_default_table($connection)){
			echo "<p class=\"msgWARN\">[WARN]:`it_languages` is empty!</p>";
			fill_default_table($connection);
		}else{
			echo "<p class=\"msgINFO\">[INFO]: default table `it_languages` has records</p>";
		}

		echo "<h3 class=\"msgOK\">Success Load <h3>";
		$connection->close();
		
	}


	function exists_default_table($conn){
		return (mysqli_query($conn,'SELECT 1 FROM `it_languages`')) ? true : false ;
	}
	function is_empty_default_table($conn){
		if ($rows=mysqli_query($conn,'SELECT * FROM `it_languages`')){
			  $rowcount=mysqli_num_rows($rows);
			  if($rowcount==0){
				mysqli_free_result($rows);
				return true;
			  }else{
				mysqli_free_result($rows);
				return false;
			  }
		}
		die("An error has occurred");
	}

	function create_default_table($conn){
		
		$sql = "CREATE TABLE IF NOT EXISTS `it_languages`( 
				`id` INT NOT NULL AUTO_INCREMENT, 
				`name` varchar(255) NOT NULL , 
				`documentation_url` varchar(255) NOT NULL DEFAULT '', 
				`description` varchar(255) NOT NULL DEFAULT '',
				`comment` varchar(255) NOT NULL DEFAULT '',
				 PRIMARY KEY (`id`)
				) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_0900_ai_ci; ";
		try {


			if (mysqli_query($conn, $sql)) {
			  echo "<p class=\"msgOK\">[SUCCESS]:Created new table `it_languages`!</p>";
			} else {
			  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
	function fill_default_table($conn){
		$sql = "INSERT INTO `it_languages` (`name`, `documentation_url`, `description`, `comment`) VALUES
									('Fortran','https://www.fortran90.org/','',''),
									('Cobol','https://www.ibm.com/support/pages/enterprise-cobol-zos-documentation-library','',''),
									('Pascal','https://www.freepascal.org/docs.html','',''),
									('C++','https://devdocs.io/cpp/','',''),
									('Python','https://docs.python.org/3/','',''),
									('Java','https://docs.oracle.com/en/java/','',''), 
									('Ruby','https://www.ruby-lang.org/es/documentation/','',''), 
									('HTML','https://devdocs.io/html/','',''), 
									('JavaScript','https://jsdoc.app/','',''), 
									('C Language','https://devdocs.io/c/','',''), 
									('C#','https://docs.microsoft.com/es-es/dotnet/csharp/','',''),
									('Objective C','https://developer.apple.com/library/archive/documentation/Cocoa/Conceptual/ProgrammingWithObjectiveC/Introduction/Introduction.html','',''), 
									('PHP','https://www.php.net/docs.php','',''),
									('SQL','https://dev.mysql.com/doc/','',''), 
									('Kotlin','https://kotlinlang.org/docs/home.html','',''),
									('Haskell','https://www.haskell.org/documentation/','',''),
									('Swift','https://swift.org/documentation/','','');";
		try {
			if (mysqli_query($conn, "TRUNCATE `it_languages`;")) {
				if (mysqli_query($conn, $sql)) {
					echo "<p class=\"msgOK\">[SUCCESS]:`it_languages` filled with predefined records</p>";
				} else {
					echo "Error: " . $sql . "<br>" . mysqli_error($conn);
				}
			} else {
			  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}

		} catch (Exception $e) {

			die($e->getMessage());
			
		}
	}
?>