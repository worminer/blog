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

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You cant write an article if you are not logged in!");
        }
        $author_id = $this->auth->getCurrentUserId();

        $title = $input->post('title');
        $content = $input->post('content');

        $articleModel = new ArticleModel();

        if ($articleModel->create($author_id, $title, $content)) {
            $this->view->redirect("/article/create", "Article Created Successfully.", "success");
        } else {
            $errorMessage = $articleModel->getErrorMessage();
            $this->view->redirect("/article/create", $errorMessage, "error");
        }
    }
}
