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

    public function delete()
    {
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not logged in!");
        }
        if ($this->input->get(0) == "" || $this->input->get(0) === null){
            $this->view->redirect("/categories/manage");
        }

        $categoryId = $this->input->get(0,"int");

        $categoryModel = new CategoriesModel();



        if (!$categoryModel->hasId($categoryId)){
            $this->view->redirect("/categories/manage","This category do not exist!");
        }

        $categoryName = $categoryModel->getCategotyById($categoryId);

        $this->view->render("category/delete",[
            "name"=> $categoryName,
            "id"=> $categoryId
        ]
        );
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

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not add new category if you are not logged in!");
        }
        if ($this->input->post("delete_category") === null){
            $this->view->redirect("/categories/manage");
        }

        $categoryId = $this->input->get(0);

         $categoryModel = new CategoriesModel();

        if (!$categoryModel->hasId($categoryId)){
            $this->view->redirect("/categories/manage","This category do not exist!");
        }

        if ($categoryModel->deleteCategory($categoryId)){
            $this->view->redirect("/categories/manage","Category deleted successfully!","success");
        }else{
            $this->view->redirect("/categories/manage","Something goes wrong with the category deletion!");
        }
    }

    public function editPost()
    {
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not add new category if you are not logged in!");
        }
        if ($this->input->post("edit_category") === null){
            $this->view->redirect("/categories/manage");
        }

        $categoryId = $this->input->get(0);
        $categoryName = $this->input->post("name");

        $categoryModel = new CategoriesModel();

        if (!$categoryModel->hasId($categoryId)){
            $this->view->redirect("/categories/manage","This category do not exist!");
        }

        if ($categoryModel->editCategory($categoryName, $categoryId)){
            $this->view->redirect("/categories/manage","Category edited successfully!","success");
        }else{
            $this->view->redirect("/categories/manage","Something goes wrong with the category deletion!");
        }

    }
    public function edit()
    {
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not logged in!");
        }
        if ($this->input->get(0) == "" || $this->input->get(0) === null){
            $this->view->redirect("/categories/manage");
        }

        $categoryId = $this->input->get(0,"int");
        $categoryName = str_replace("%20" , " ", $this->input->get(1));


        $categoryModel = new CategoriesModel();

        if (!$categoryModel->hasId($categoryId)){
            $this->view->redirect("/categories/manage","This category do not exist!");
        }
        $categoryName = $categoryModel->getCategotyById($categoryId);

        $this->view->render("category/edit",[
                "name"=> $categoryName,
                "id"=> $categoryId
            ]
        );
    }
}
