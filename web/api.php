<?php
require "/var/www/html/inc/bootstrap.php";


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode( '/', $uri );


// 0 -> root
// 1 -> api.php
// 2 -> table rest_api_db required an defiend
// 3 -> action to perform requiered



if ((isset($uri[2]) && $uri[2] != 'itlang') || !isset($uri[3])) {
    header("HTTP/1.1 404 Not Found");

    exit();
}

require "/var/www/html/controller/ITLangController.php";
$objFeedController = new ITLangController();
$strMethodName = $uri[3] . 'Action';
$objFeedController->{$strMethodName}();






?>