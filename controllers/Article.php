<?php
namespace Controllers;

use Models\ArticleModel;
use MVC\DefaultController;

class Article extends DefaultController
{
    public function create()
    {
        $this->view->render("article/create");
    }

    public function createPost()
    {
        $input = $this->input;

        if ($input->post('create') === null){
            $this->view->redirect("/article/edit");
        }

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You cant read an post if you are not logged in!");
        }
        $author_id = $this->auth->getCurrentUserId();

        $title = $input->post('title',"trim");
        $content = $input->post('content');

        $this->validate->setRule('minlength', $title , 5 , "Article title is to short!");
        $this->validate->setRule('minlength', $content , 10 , "Article content is to short!");

        if ($this->validate->validate() === false) {
            $errors = $this->validate->getErrors();
            $this->view->redirect("/article/create",$errors);
        }


        $articleModel = new ArticleModel();

        if ($articleModel->create($author_id, $title, $content)) {
            $this->view->redirect("/article/create", "Post Created Successfully.", "success");
        } else {
            $errorMessage = $articleModel->getErrorMessage();
            $this->view->redirect("/article/create", $errorMessage, "error");
        }
    }
    public function edit(){
        $input = $this->input;
        $article_id = $input->get(0);

        $articleModel = new ArticleModel();
        $articleData = $articleModel->getArticleByArticleId($article_id)[0];


        $this->view->render("article/editPost",[
            "article_id" => $article_id,
                "title" => $articleData["title"],
                "content" => $articleData["content"]
            ]
        );
    }

    public function editPost()
    {

        $input = $this->input;

        if ($input->get(0) === null){
            $this->view->redirect("/article/edit", "There is no article id");
        }

        if ($input->post('edit') === null){
            $this->view->redirect("/article/edit");
        }

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You cant read an post if you are not  logged in!");
        }

        $articleID = $input->get(0);


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
        $articleAuthorId = $articleModel->getAuthorIdFromPost($articleID)[0];


        if ($articleAuthorId == $user_id) {
            if ($articleModel->editPost($title, $content, $articleID))
            {
                $this->view->redirect("/article/allPost", "Post edit Successfully.", "success");
            } else {
                $errorMessage = $articleModel->getErrorMessage();
                $this->view->redirect("/article/edit", $errorMessage, "error");
            }
        }else{
            $this->view->redirect("/article/allPost", "Sorry you are not the author of this post!");
        }

    }

    public function delete()
    {
        $input = $this->input;
        $article_id = $input->get(0);
        $articleModel = new ArticleModel();
        $articleData = $articleModel->getArticleByArticleId($article_id)[0];


        $this->view->render("article/deletePost",[
                "article_id" => $article_id,
                "title" => $articleData["title"],
                "content" => $articleData["content"]
            ]
        );
    }

    public function deletePost()
    {

        $input = $this->input;

        if ($input->get(0) === null) {
            $this->view->redirect("/article/delete", "There is no article id");
        }

        if ($input->post('delete') === null) {
            $this->view->redirect("/article/delete");
        }

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You cant delete an post if you are not  logged in!");
        }

        $articleID = $input->post('article_id');
        $articleModel=new ArticleModel();
        $user_id = $this->auth->getCurrentUserId();
        $articleUserId = $articleModel->getAuthorIdFromPost($articleID)[0];

        if ($articleUserId === false){
            $this->view->redirect("/article/allPost", "Sorry something got wrong!");
        }

        $articleAuthorId = $articleUserId[0];

        if ($articleAuthorId == $user_id) {
            if ($articleModel->deletePost($articleID)) {
                $this->view->redirect("/article/allPost", "Post deleted Successfully.", "success");
            } else {
                $errorMessage = $articleModel->getErrorMessage();
                $this->view->redirect("/article/delete", $errorMessage, "error");
            }
        }else{
            $this->view->redirect("/article/allPost", "Sorry you are not the author of this post!");
        }
    }

    public function show(){
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You cant view an article if you are not logged in!");
        }

        $author_id = $this->auth->getCurrentUserId();
        $articleModel = new ArticleModel();
        $result = $articleModel->getArticleByAuthorId($author_id);
        //var_dump($result);
        $this->view->render("article/showPost",["results" => $result]);
    }

    public function allPost(){
        $articleModel=new ArticleModel();

        if ($articleModel->showAllPost()) {
            $result=$articleModel->showAllPost();
            $this->view->render("article/allPost",["result"=>$result]);
        } else {
            $errorMessage = $articleModel->getErrorMessage();
            $this->view->redirect("/article/create", $errorMessage, "error");
        }
    }

    public function showById()
    {

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You cant view an article if you are not logged in!");
        }
        $author_id = $this->auth->getCurrentUserId();

        $articleModel = new ArticleModel();

        $result = $articleModel->getArticleByAuthorId($author_id);
        var_dump($result);
        $this->view->render("/article/showPost", ["result" => $result[0]]);
    }
}
