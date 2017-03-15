<?php
namespace Models;

class ArticleModel extends \MVC\Database\PdoMysql
{


    /**
     * @param int $author_id
     * @param string $title
     * @param string $content
     * @return bool
     * @throws \Exception
     */

    public function createArticle(int $author_id, string $title, string $content):bool
    {
        if (!isset($author_id) && !isset($title) && !isset($content)) {
            throw new \Exception("ERROR: ArticleModel->createArticle -> all parameters are Mandatory title={$title}, content={$content} <-");
        }

        $result = $this->prepare("INSERT INTO `articles`( `author_id`, `title`, `content`) VALUES (?,?,?)", [$author_id,$title,$content]);

        $result->execute();

        if  (!$result->getAffectedRows()){
            throw new \Exception("Error:Something went wrong! Article was not saved in DB!");
        }

        return true;
    }

    public function editPost($title,$content,$article_id):bool {
        $result = $this->prepare("UPDATE `articles` SET `title`=?,`content`=? WHERE `article_id`=?", [$title, $content, $article_id]);
        $result->execute();
        if (!$result->getAffectedRows()) {
            throw new \Exception("Error: Article edit was not was not saved in DB!");
        }
        return true;
    }

    public function deletePost($article_id){
        $result=$this->prepare("DELETE FROM `articles` WHERE `article_id`=?",[$article_id]);
        $result->execute();
        if  (!$result->getAffectedRows()){
            throw new \Exception("Error: Article was not edit in DB!");

        }
        return true;
    }

    public function getAuthorIdFromPost($article_id):int {

        $result=$this->prepare("SELECT `author_id` FROM `articles` WHERE `article_id`=?",[$article_id]);
        $result->execute();

        if  (!$result->getAffectedRows()){
            throw new \Exception("Error: Article not exist in DB!");
        }
       return $result->fetchRllAssoc()['author_id'];
    }

    public function getAllArticles():array
    {
        $result = $this->prepare("SELECT article_id,author_id,title,content,real_name,created_at FROM articles as a INNER JOIN USERS as u ON a.author_id=u.id");
        $result->execute();
        if  (!$result->getAffectedRows()){
            throw new \Exception("Error: The request is not fulfilled!");
        }
        return $result = $result->fetchAllAssoc();
    }

    public function getArticlesByAuthorId($author_id):array {
        $result=$this->prepare("SELECT article_id,author_id,title,content,real_name,created_at FROM articles as a INNER JOIN USERS as u ON a.author_id=u.id WHERE `author_id`=? ORDER BY created_at DESC ",[$author_id])->execute();
        return $result->fetchAllAssoc();
    }

    public function getArticleByArticleId($article_id):array {
        $result=$this->prepare("SELECT article_id,author_id,title,content,real_name,created_at FROM articles as a INNER JOIN USERS as u ON a.author_id=u.id WHERE `article_id`=?",[$article_id])->execute();
        $result = $result->fetchRllAssoc(); // this is just because it returns false or array and the interpreter gets the return type wrong
        if ($result === false) {
            throw new \Exception("Data for this article cant be found!");
        }
        return $result;
    }

    public function getAuthorNameById($author_id) {
        $result=$this->prepare("SELECT `username` FROM `users` WHERE `id`=?",[$author_id])->execute();
        return $result->fetchAllAssoc();
    }

}