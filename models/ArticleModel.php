<?php
namespace Models;

class ArticleModel extends \MVC\Database\PdoMysql
{


    private $errorMessage = "";
    /**
     * @param int $author_id
     * @param string $title
     * @param string $content
     * @return bool
     * @throws \Exception
     */

    public function create(int $author_id, string $title, string $content):bool
    {
        if (!isset($author_id) && !isset($title) && !isset($content)) {
            throw new \Exception("ERROR: ArticleModel->create -> all parameters are Mandatory title={$title}, content={$content} <-");
        }

        $result = $this->prepare("INSERT INTO `articles`( `author_id`, `title`, `content`) VALUES (?,?,?)", [$author_id,$title,$content]);

        $result->execute();

        if  (!$result->getAffectedRows()){

            $this->setErrorMessage("Error: article was not saved in DB!");
            return false;
        }else {

            return true;
        }
    }


    private function setErrorMessage(string $message){
        $this->errorMessage = $message;
    }
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}