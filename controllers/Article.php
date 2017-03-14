<?php
namespace Controllers;

use Models\ArticleModel;
use MVC\DefaultController;

class Article extends DefaultController
{
    /* display createArticle article view */
    public function create()
    {
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login","You have to be logged in to createArticle article!");
        }
        $this->view->render("article/createArticle",["sub_title" => "Create New Article"]);
    }
    /* create new article */
    public function createPost()
    {
        $input = $this->input;

        if ($input->post('createArticle') === null){
            $this->view->redirect("/article/createArticle");
        }

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You have to be logged in to createArticle article!");
        }
        $author_id = $this->auth->getCurrentUserId();

        $title = $input->post('title',"trim");
        $content = $input->post('content');

        $this->validate->setRule('minlength', $title , 5 , "Article title is to short!");
        $this->validate->setRule('minlength', $content , 10 , "Article content is to short!");

        if ($this->validate->validate() === false) {
            $errors = $this->validate->getErrors();
            $this->view->redirect("/article/createArticle",$errors);
        }

        $articleModel = new ArticleModel();

        try{
            if ($articleModel->createArticle($author_id, $title, $content)) {
                $this->view->redirect("/article/myArticles", "Article Created Successfully.", "success");
            }
        }catch (\Exception $exception) {
            $errorMessage = $exception->getMessage();
            $this->view->redirect("/article/create", $errorMessage, "error");
        }
    }
    /* display editArticle view */
    public function edit(){
        $input = $this->input;
        if (empty($input->get(0))) {
            $this->view->redirect("/article/myArticles", "There is no such article!", "error");
        }

        $article_id = $input->get(0,"int");
        if (empty($article_id)) {
            $this->view->redirect("/article/myArticles", "This is not an valid article id!", "error");
        }

        $articleModel = new ArticleModel();
        $articleData = []; // so it will not cry like a baby ..
        try{
            $articleData = $articleModel->getArticleByArticleId($article_id);
        }catch (\Exception $exception){
            $errorMessage = $exception->getMessage();
            $this->view->redirect("/article/myArticles", $errorMessage, "error");
        }

        $this->view->render("article/editArticle",[
                "article_id" => $article_id,
                "title" => $articleData["title"],
                "content" => $articleData["content"]
            ]
        );
    }

    /* edit an article */
    public function editPost()
    {

        $input = $this->input;

        if ($input->post('editArticle') === null){
            $this->view->redirect("/article/edit");
        }

        if (empty($input->get(0))){
            $this->view->redirect("/article/edit", "There is no article id");
        }

        $articleID = $input->get(0,'int');
        if (empty($articleID)){
            $this->view->redirect("/article/edit", "This is not an valid article id!");
        }

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You cant edit an Article if you are not logged in!");
        }

        $title = $input->post('title',"trim");
        $content = $input->post('content');

        $this->validate->setRule('numeric', $articleID , null , "Article id is not an valid id");
        $this->validate->setRule('minlength', $title , 5 , "Article title is to short!");
        $this->validate->setRule('minlength', $content , 10 , "Article content is to short!");

        if ($this->validate->validate() === false) {
            $errors = $this->validate->getErrors();
            $this->view->redirect("/article/edit/{$articleID}",$errors);
        }

        $user_id = $this->auth->getCurrentUserId();
        $articleModel = new ArticleModel();
        $articleAuthorId = null; // so it wont cry for it not been defined
        try{
            $articleAuthorId = $articleModel->getAuthorIdFromPost($articleID);
        }catch (\Exception $exception){
            $errorMessage = $exception->getMessage();
            $this->view->redirect("/article/edit", $errorMessage , "error");
        }



        if ($articleAuthorId == $user_id) {
            try{

                if ($articleModel->editPost($title, $content, $articleID)){
                    $this->view->redirect("/article/myArticles/{$articleID}", "Article Edited Successfully", "success");
                }

            }catch (\Exception $exception) {
                $errorMessage = $exception->getMessage();
                $this->view->redirect("/article/edit/{$articleID}", $errorMessage, "error");
            }
        }else{
            $this->view->redirect("/article/allPost", "Sorry you are not the author of this Article!");
        }

    }

    public function delete()
    {
        $input = $this->input;
        if (empty($input->get(0))){
            $this->view->redirect("/article/edit", "There is no article id!");
        }
        $articleID = $input->get(0,'int');
        if (empty($articleID)){
            $this->view->redirect("/article/edit", "This is not an valid article id!");
        }

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You cant delete an Article if you are not logged in!");
        }

        $articleModel = new ArticleModel();
        $articleData = []; // so it will not cry like a baby ..
        try{
            $articleData = $articleModel->getArticleByArticleId($articleID);
        }catch (\Exception $exception){
            $errorMessage = $exception->getMessage();
            $this->view->redirect("/article/myArticles", $errorMessage, "error");
        }

        $this->view->render("article/deleteArticle",[
                "article_id" => $articleID,
                "title" => $articleData["title"],
                "content" => $articleData["content"]
            ]
        );
    }

    public function deletePost()
    {
        $input = $this->input;

        if (empty($input->get(0))){
            $this->view->redirect("/article/edit", "There is no article id");
        }

        $articleID = $input->get(0,'int');
        if (empty($articleID)){
            $this->view->redirect("/article/edit", "This is not an valid article id!");
        }

        if ($input->post('deleteArticle') === null) {
            $this->view->redirect("/article/delete");
        }

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You cant delete an Article if you are not logged in!");
        }
        // new new
        $user_id = $this->auth->getCurrentUserId();
        $articleModel = new ArticleModel();
        $articleAuthorId = null; // so it wont cry for it not been defined
        try{
            $articleAuthorId = $articleModel->getAuthorIdFromPost($articleID);
        }catch (\Exception $exception){
            $errorMessage = $exception->getMessage();
            $this->view->redirect("/article/edit", $errorMessage , "error");
        }

        if ($articleAuthorId == $user_id) {
            try{

                if ($articleModel->deletePost($articleID)) {
                    $this->view->redirect("/article/myArticles", "Article deleted Successfully.", "success");
                }

            }catch (\Exception $exception) {
                $errorMessage = $exception->getMessage();
                $this->view->redirect("/article/delete/{$articleID}", $errorMessage, "error");
            }
        }else{
            $this->view->redirect("/article/allPost", "Sorry you are not the author of this Article!");
        }
        // new new
    }

    /* displays articles written by the current user */
    public function myArticles(){
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You cant view an article if you are not logged in!");
        }

        $author_id = $this->auth->getCurrentUserId();
        $articleModel = new ArticleModel();
        try{
            $result = $articleModel->getArticlesByAuthorId($author_id);
            $this->view->render("article/myArticles",["results" => $result]);
        }catch (\Exception $exception){
            $errorMessage = $exception->getMessage();
            $this->view->redirect("/home/index", $errorMessage, "error");
        }
    }

    /* show all articles by all users */
    public function AllArticles(){
        $articleModel=new ArticleModel();

        try{
            $result=$articleModel->getAllArticles();
            $this->view->render("article/allArticles",["results"=>$result]);
        }catch (\Exception $exception){
            $errorMessage = $exception->getMessage();
            $this->view->redirect("/article/createArticle", $errorMessage, "error");
        }

    }

    public function articleId()
    {
        $input = $this->input;
        if (empty($input->get(0))){
            $this->view->redirect("/article/AllArticles", "There is no article id");
        }

        $articleID = $input->get(0,'int');
        if (empty($articleID)){
            $this->view->redirect("/article/AllArticles", "This is not an valid article id!");
        }

        $articleModel = new ArticleModel();
        try{
            $articleData = $articleModel->getArticleByArticleId($articleID);
        }catch (\Exception $exception){
            $errorMessage = $exception->getMessage();
            $this->view->redirect("/article/myArticles", $errorMessage, "error");
        }
        var_dump($articleData);
        $this->view->render("/article/showArticle", ["result" => $articleData]);
    }
}
