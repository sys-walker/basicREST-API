<?php

class HttpStatus {
    const E422 = 'HTTP/1.1 422 Unprocessable Entity';
    const C200 = 'HTTP/1.1 200 OK';
    const E404 = 'HTTP/1.1 404 Not Found';
    const E406 = 'HTTP/1.1 406 Not Acceptable';
    const E403 = 'HTTP/1.1 403 Forbidden';
    const E405 = 'HTTP/1.1 405 Method Not Allowed';
    const E501 = 'HTTP/1.1 501 Not Implemented';
    const E500 = 'HTTP/1.1 500 Internal Server Error';
}

//

class ITLangController extends BaseController{
    /**
     * "/ITLang/list" Endpoint - Get list of IT Languages
     */
    public function getElements(){
        $paramsQuery = $this->getQueryStringParams();
        $params = $this->getUriSegments();

        $content = null; // Associative Array
        $responseData = null; // json_encode($content); 
        $header = null; // HTTP header status

        if (!isset($params[3]) || $params[3]=="list" || is_numeric($params[3])) {
               $userModel = new ITLangModel();
            if (isset($params[3]) && is_numeric($params[3])){
                $id = $params[3];
                $content = $userModel->getLanguagemodel($id);
                if (count($content)==0){
                    $content = array('error' => "Element not found");
                    $responseData = json_encode($content); 
                    $header = HttpStatus::E404;
                    send_error_json($responseData,array('Content-Type: application/json', $header));
                }else{
                    $responseData = json_encode($content);
                    $header = HttpStatus::C200;
                }
            }else if (isset($params[3]) && $params[3]=="list"){
                $content = $userModel->getLanguages_nolimit();
                $responseData = json_encode($content);
                $header = HttpStatus::C200;
            }else{
                if (count($paramsQuery)==1 && array_key_exists("id", $paramsQuery)){
                    $id = $paramsQuery['id'];
                    $content = $userModel->getLanguagemodel($id);
                    if (count($content)==0){
                        $content = array('error' => "Element not found");
                        $responseData = json_encode($content); 
                        $header = HttpStatus::E404;
                        send_error_json($responseData,array('Content-Type: application/json', $header));
                    }else{
                        $responseData = json_encode($content);
                        $header = HttpStatus::C200;
                    }
                }else{
                    $content = $userModel->getLanguages_nolimit();
                    $responseData = json_encode($content);
                    $header = HttpStatus::C200;
                }
            }
            $this->sendOutput($responseData,array('Content-Type: application/json', $header));
        }else{
            $content = array('error' => "Invalid operation");
            $responseData = json_encode($content); 
            $header = 'HTTP/1.1 422 Unprocessable Entity';
            send_error_json($responseData,array('Content-Type: application/json', $header));
        }


    }

    /**
     * "/ITLang/add" Endpoint - Add new IT Language
     */
    public function addElement(){
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

        if (strpos($contentType, 'application/json') !== 0) {
            $content = array('error' => "Content-Type not supported");
            $responseData = json_encode($content); 
            $header = HttpStatus::E406;
            send_error_json($responseData,array('Content-Type: application/json', $header));
        }

            $entityBody = file_get_contents('php://input');
            $array = json_decode($entityBody, true);
            $scopedValues = Array();
            $scopedValues['name'] = $array['name'];
            $scopedValues['documentation_url'] = $array['documentation_url'];
            $scopedValues['description'] =  $array['description'] ?? '';
            $scopedValues['comment'] = $array['comment'] ?? '';


            if (isset($scopedValues['name'],$scopedValues['documentation_url']) ){
    
            //   $scopedValues['createdAt'] = date('Y-m-d H:i:s');
            //   $scopedValues['updatedAt'] = date('Y-m-d H:i:s');

                echo "<pre>".print_r($array,true)."</pre>";
            }else{
                $content = array('error' => "Invalid JSON");
                $responseData = json_encode($content); 
                $header = HttpStatus::E422;
                send_error_json($responseData,array('Content-Type: application/json', $header));
            }        
        // $entityBody = file_get_contents('php://input');
        // $content = array('error' => "Not implemented");
        // $responseData =  $entityBody ;
        // $header = HttpStatus::E422;
        // send_error_json($responseData,array('Content-Type: application/json', $header));
    }

    public function editAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        switch($requestMethod){
            case 'POST':
                $content = array('error' => "It will be implemented in the future");
                $responseData = json_encode($content); 
                $header = 'HTTP/1.1 501 Not Implemented';
                break;
            default:
                $content = array('error' => "Method not supported");
                $responseData = json_encode($content); 
                $header = 'HTTP/1.1 422 Unprocessable Entity';
                break;
        }
        $this->sendOutput($responseData,array('Content-Type: application/json', $header));

    }
}
?>
