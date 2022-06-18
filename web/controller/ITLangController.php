<?php
class ITLangController extends BaseController{
    /**
     * "/ITLang/list" Endpoint - Get list of IT Languages
     */
    public function listAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        $content = null; // Associative Array
        $responseData = null; // json_encode($content); 
        $header = null; // HTTP header status


        switch($requestMethod){
            
            case 'GET':

                try {
                    $userModel = new ITLangModel();

                    if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit'] &&  ((int) $arrQueryStringParams['limit'] )>0 ) {
                        $intLimit = $arrQueryStringParams['limit'];
                        $content = $userModel->getLanguages($intLimit);
                    }else{
                        $content = $userModel->getLanguages_nolimit();

                    }
                    $responseData = json_encode($content);
                    $header = 'HTTP/1.1 200 OK';
                } catch (Error $e) {
                    $content = array('error' => $e->getMessage().' Something went wrong! Please contact support.');
                    $responseData = json_encode($content); 
                    $header = 'HTTP/1.1 500 Internal Server Error';
                }
                break;

            default:
                $content = array('error' => "Method not supported");
                $responseData = json_encode($content); 
                $header = 'HTTP/1.1 422 Unprocessable Entity';
                break;
        }
        $this->sendOutput($responseData,array('Content-Type: application/json', $header));
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
