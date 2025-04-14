<?php
//$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//$uri = explode( '/', $uri );
//echo "<pre>".print_r($uri,true)."</pre>";

require _PROJECT_PATH_."/inc/bootstrap.php";


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode( '/', $uri );


// 0 -> root
// 1 -> api.php
// 2 -> table
// 3 -> action type


if (!isset($uri[2])) {
    default_html_api($uri);
}
switch ($uri[2]) {

    case 'itlang' :
        itlang_operations($uri);
        break;
    case '' :
        default_html_api($uri);
        break;        
    default:
        $content = array('error' => "The table`".$uri[2]."` does not exists.",
                         'question' => "Which table are you trying to get the JSON?");
        $responseData = json_encode($content); 
        $header = 'HTTP/1.1 404 Not Found';
        send_error_json($responseData,array('Content-Type: application/json', $header));
}


function default_html_api($params){
    echo "How did you get here?<br>";
    echo "<pre>".print_r($params,true)."</pre>";
    exit();
}

function itlang_operations($params){
    /*
    GET
    - get list
    -get item
    POST
    - post item
    PUT
    - update item/replace
    PATCH
    - update item partially
    DELETE
    -delete item           
    */
    require _PROJECT_PATH_."/controller/ITLangController.php";
    
    $allowedMethods = ["GET", "POST", "PUT", "PATCH", "DELETE"];
    $requestMethod = $_SERVER["REQUEST_METHOD"];


    if (!in_array($requestMethod, $allowedMethods)) {
        $content = [
            'error' => "Método no permitido",
            'metodos_permitidos' => "XD"
        ];
        $responseData = json_encode($content);
        $header = 'HTTP/1.1 405 Method Not Allowed';
        send_error_json($responseData, ['Content-Type: application/json', $header]);
    }

    $objFeedController = new ITLangController();
    switch ($requestMethod) {
        case 'GET':
            $objFeedController->getElements(); 

            break;
        case 'POST':
            $objFeedController->addElement(); 
            // echo "<p>add resource</p>";
            // echo "<pre>".print_r($requestMethod,true)."</pre>";
            // echo "<pre>".print_r($params,true)."</pre>";
            # code...
            break;
        case 'PUT':
            echo "<p>update/replace resource</p>";
            echo "<pre>".print_r($requestMethod,true)."</pre>";
            echo "<pre>".print_r($params,true)."</pre>";
            # code...
            break;
        case 'PATCH':
            echo "<p>patch resource</p>";
            echo "<pre>".print_r($requestMethod,true)."</pre>";
            echo "<pre>".print_r($params,true)."</pre>";
            # code...
            break;
        case 'DELETE':
            echo "<p>delete resource</p>";
            echo "<pre>".print_r($requestMethod,true)."</pre>";
            echo "<pre>".print_r($params,true)."</pre>";
            # code...
            break;   
        default:
            # code...
            echo "XD";
            break;
    }
    //param[3] operation type list
    // if (!isset($params[3]) || $params[3]=="") {
    //     $content = array('error' => "Must specify a table operation");
    //     $responseData = json_encode($content); 
    //     $header = 'HTTP/1.1 422 Unprocessable Entity';
    //     send_error_json($responseData,array('Content-Type: application/json', $header));

    // }

    // $objFeedController = new ITLangController();
    // $strMethodName = $params[3] . 'Action';
    // $objFeedController->{$strMethodName}();  
    
 
    
}

function send_error_json($data, $httpHeaders=array()){
    header_remove('Set-Cookie');

    if (is_array($httpHeaders) && count($httpHeaders)) {
        foreach ($httpHeaders as $httpHeader) {
           header($httpHeader);
        }
    }

    echo $data;
    exit();
}

?>