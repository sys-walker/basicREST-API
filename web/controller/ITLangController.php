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

        switch (strtoupper($requestMethod)) {
            case 'GET':
                try {
                    $userModel = new ITLangModel();

                    if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                        $intLimit = $arrQueryStringParams['limit'];
                        $arrUsers = $userModel->getLanguages($intLimit);
                    } else {
                        $arrUsers = $userModel->getLanguages_nolimit();
                    }

                    $responseData = json_encode($arrUsers);
                } catch (Error $e) {
                    $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
                break;

            default:
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                break;
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
    public function addAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        switch (strtoupper($requestMethod)) {
            case 'POST':
                    try {
                        $postData = json_decode(file_get_contents('php://input'), true);
                        $responseData = json_encode($postData);
                        // Validate the required fields in the $postData array
                        if (isset($postData['name']) && isset($postData['documentation_url'])) {
                            $name = $postData['name'];
                            $description = $postData['documentation_url'];

                            $description = isset($postData['description']) ? $postData['description'] : '';
                            $comment = isset($postData['comment']) ? $postData['comment'] : '';
                            // Create a new ITLangModel instance
                            $itLangModel = new ITLangModel();
                            // Call the createLanguage method to create a new IT language
                            $newLanguage = $itLangModel->insertIntoModel($name, $description,$description,$comment);
                            $responseData = json_encode($newLanguage);
                        } else {
                            $strErrorDesc = 'Missing required fields';
                            $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                        }
                    } catch (Error $e) {
                        $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
                        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                    }
                    break;
            default:
                $strErrorDesc = 'Method not supported for this endpoint';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                break;
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
