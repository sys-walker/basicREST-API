<?php
require_once "/var/www/html//model/Database.php";

class ITLangModel extends Database
{
    public function getLanguages($limit){
        return $this->select("SELECT * FROM it_languages ORDER BY id ASC LIMIT  ". $limit);
    }
    
    public function getLanguages_nolimit(){
        return $this->select("SELECT * FROM it_languages ORDER BY id ASC");
    }
    public function insertIntoModel($name_language, $doc_language)
    {
         //echo("Sucess1");
         return $this->insert(
            "INSERT INTO it_languages ( name, documentation_url, description, comment) VALUES ( '".$name_language."', '" .$doc_language." ' , 'test1', 'test2')");
    }
}
?>