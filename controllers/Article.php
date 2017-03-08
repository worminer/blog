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

}
