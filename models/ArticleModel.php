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

    public function editPost($title,$content,$article_id):bool {
        $result = $this->prepare("UPDATE `articles` SET `title`=?,`content`=? WHERE `article_id`=?", [$title, $content, $article_id]);
        $result->execute();
        if (!$result->getAffectedRows()) {
            $this->setErrorMessage("Error: Post  was not edit in DB!");
            return false;
        } else {
            return true;
        }
    }

    public function deletePost($article_id){
        $result=$this->prepare("DELETE FROM `articles` WHERE `article_id`=?",[$article_id]);
        $result->execute();
        if  (!$result->getAffectedRows()){
            $this->setErrorMessage("Error: Post  was not edit in DB!");
            return false;
        }else {
            return true;
        }
    }

    public function getAuthorIdFromPost($article_id) {

        $result=$this->prepare("SELECT `author_id` FROM `articles` WHERE `article_id`=?",[$article_id]);
        $result->execute();
        if  (!$result->getAffectedRows()){
            $this->setErrorMessage("Error: Post   not exist  in DB!");
        }
       return $result->fetchRawNum();
    }

    public function showAllPost():array
    {
        $result = $this->prepare("Select article_id,author_id,title,content,username FROM articles as a INNER JOIN USERS as u ON a.author_id=u.id");
        $result->execute();
        if  (!$result->getAffectedRows()){
            $this->setErrorMessage("Error: The request is not fulfilled!");

        }
        return $result = $result->fetchAllAssoc();
    }

    public function getArticleByAuthorId($author_id):array {
        $result=$this->prepare("Select article_id,author_id,title,content,username FROM articles as a INNER JOIN USERS as u ON a.author_id=u.id WHERE `author_id`=?",[$author_id]);
        $result->execute();
        return $result->fetchAllAssoc();
    }
    public function getArticleByArticleId($article_id){
        $result=$this->prepare("SELECT * FROM `articles` WHERE `article_id`=?",[$article_id]);
        $result->execute();
        return $result->fetchAllAssoc();
    }

    public function getAuthorNameById($author_id){
        $result=$this->prepare("SELECT `username` FROM `users` WHERE `id`=?",[$author_id]);
        $result->execute();
        return $result->fetchAllAssoc();
    }

    public function setErrorMessage(string $message){
        $this->errorMessage = $message;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}