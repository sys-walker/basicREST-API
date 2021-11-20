<?php
define("_PROJECT_PATH_",$_SERVER['DOCUMENT_ROOT']);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
switch ($uri[1]) {
    case '/' :
        require '/var/www/html/views/index.php';
        break;
    case '' :
        require '/var/www/html/views/index.php';
        break;
    case 'about' :
        require '/var/www/html/views/about.php';
        break;
    case 'api' :
        require '/var/www/html/views/api.php';
        break;
    case 'use' :
        require '/var/www/html/views/instructions.php';
        break;
    case 'info' :
        require '/var/www/html/views/info.php';
        break;
    default:
        http_response_code(404);
        require '/var/www/html/views/404.php';
        break;
}