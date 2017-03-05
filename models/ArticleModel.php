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
        //INSERT INTO `articles`( `author_id`, `title`, `content`) VALUES ('3','new article','gergtgrgyrrtge')
        // приготвяме заявката за записване на артикъла
        $result = $this->prepare("INSERT INTO `articles`( `author_id`, `title`, `content`) VALUES (?,?,?)", [$author_id,$title,$content]);
        // опитваме се да запишем артикъла в базата с данни
        $result->execute();
        // ако е Не успешна заявката то тогава getAffectedRows() ще върне 0 което като булева е false
        if  (!$result->getAffectedRows()){
            // ако е върнало 0/false трябва да покажем грешка
            $this->setErrorMessage("Error: article was not saved in DB!");
            return false;
        }else {
            // ако е успяло да се запише в базата с данни то тогава ние връщаме true
            return true;
        }
    }

    public function show(){
        $result=$this->prepare("SELECT * FROM `articles`");
        $result->execute();

        return $result->fetchAllAssoc();

    }

    private function setErrorMessage(string $message){
        $this->errorMessage = $message;
    }
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}