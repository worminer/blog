<?php
namespace Controllers;

use Models\CategoriesModel;
use MVC\DefaultController;

class Categories extends DefaultController
{
    public function manage()
    {
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not logged in!");
        }

        $categoryModel = new CategoriesModel();
        $categories = $categoryModel->getCategories();
        $this->view->render("category/manage",["categories"=> $categories]);
    }

    public function addPost(){

        if ($this->input->post("category_create") === null){
            $this->view->redirect("/categories/manage");
        }

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not add new category if you are not logged in!");
        }

        $categoryName = $this->input->post("category_name");

        $this->validate->setRule("minlength",$categoryName,3, "Category name length must be more then 3 symbols!");

        if ($this->validate->validate() === false){
            $error = $this->validate->getErrors();
            $this->view->redirect("/categories/manage",$error);
        }

        $categoryModel = new CategoriesModel();

        if ($categoryModel->hasCategory($categoryName)){
            $this->view->redirect("/categories/manage","This category already exist!");
        }

        if($categoryModel->addNewCategory($categoryName)){
            $this->view->redirect("/categories/manage","Category created successfully!","success");
        }else{
            $this->view->redirect("/categories/manage","Something goes wrong with the category creation!");
        }



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
