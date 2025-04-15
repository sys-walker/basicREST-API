<?php
require_once _PROJECT_PATH_."/model/Database.php";

class ITLangModel extends Database
{
    public function getLanguages($limit){
        return $this->select("SELECT * FROM it_languages ORDER BY id ASC LIMIT  ". $limit);
    }
    
    public function getLanguages_nolimit(){
        return $this->select("SELECT * FROM it_languages ORDER BY id ASC");
    }
    public function insertIntoModel($name_language, $doc_language, $description_language, $comment_language)
    {
        return $this->insert(
            "INSERT INTO it_languages ( name, documentation_url, description, comment) VALUES ( '".$name_language."', '" .$doc_language." ' , '".$description_language."', '".$comment_language."')");
    }
    
    public function getLanguagemodel($id)
    {
        return $this->select("SELECT * FROM it_languages WHERE id = ".$id);
    }

    
}
?>