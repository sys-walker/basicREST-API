<?php
define("DB_HOST", getenv('MYSQL_DBHOST') ? getenv('MYSQL_DBHOST') : "localhost");
define("DB_USERNAME", getenv('MYSQL_DBUSER') ? getenv('MYSQL_DBUSER') : "root");
define("DB_PASSWORD", getenv('MYSQL_DBPASS') ? getenv('MYSQL_DBPASS') : "toor");
define("DB_PORT",getenv('MYSQL_DBPORT') ? getenv('MYSQL_DBPORT') : "3306");
define("DB_DATABASE_NAME", "rest_api_db");

?>