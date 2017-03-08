<?php
namespace Controllers;

use Models\CategoriesModel;
use MVC\DefaultController;

class Categories extends DefaultController
{
    public function manage()
    {
        $categoryModel = new CategoriesModel();
        $categories = $categoryModel->getCategories();

        $this->view->render("category/manage",["categories"=> $categories]);
    }

    public function addPost(){

    }
    public function deletePost(){

    }

    public function editPost()
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
    public function edit()
    {
        $this->view->render("category/manage"); // render edit view
    }
}
