<?php
class ITLangController extends BaseController{
    /**
     * "/ITLang/list" Endpoint - Get list of IT Languages
     */
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new ITLangModel();

                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                    $arrUsers = $userModel->getLanguages($intLimit);
                }else{
                    $arrUsers = $userModel->getLanguages_nolimit();

                }

                
                $responseData = json_encode($arrUsers);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }

        }elseif (strtoupper($requestMethod) == 'POST') {
        echo print_r($arrQueryStringParams);
                
       die();/*
            $postdata = file_get_contents("php://input");
                  if (isset($postdata)) {
                   $request = json_decode($postdata);
                   $request->recibido = 'OK';
                   echo json_encode($request);
                  }
        die();
               

            try {
                $arrQueryStringParams = file_get_contents("php://input");
                $userModel = new ITLangModel();
                if (!isset($arrQueryStringParams['name']) && !$arrQueryStringParams['doc']) {

                    $name_language = $arrQueryStringParams['name'];
                    $doc_language = $arrQueryStringParams['doc'];

                    $arrUsers = $userModel->insertIntoModel($name_language, $doc_language);

                    $responseData = json_encode($arrQueryStringParams);
                    $this->sendOutput( $responseData, array('Content-Type: application/json', 'HTTP/1.1 201 Created'));
                    exit();
                }else{
                    $strErrorDesc = 'Missing parameters';
                    $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';

                }

                
            } catch (Error $e) {
                die("Die after error");
            }*/

            
        }else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}
?>
