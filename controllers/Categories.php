<?php
namespace Controllers;

use Models\CategoriesModel;
use MVC\DefaultController;

class Categories extends DefaultController
{
    /* Displays the view for manageing all categories */
    public function manage()
    {
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not logged in!");
        }

        if (!$this->auth->isInRole("admin")) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not Admin!");
        }

        $categoryModel = new CategoriesModel();
        try{
            $categories = $categoryModel->getCategories();
            $this->view->render("category/manage",["categories"=> $categories]);
        }catch (\Exception $exception){
            $this->view->redirect("/user/login/",$exception->getMessage());
        }

    }

    /* display the delete category by id form */
    public function delete()
    {
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not logged in!");
        }
        if (!$this->auth->isInRole("admin")) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not Admin!");
        }

        if (empty($this->input->get(0,"int"))){
            $this->view->redirect("/categories/manage");
        }

        $categoryId = $this->input->get(0,"int");

        $categoryModel = new CategoriesModel();

        if (!$categoryModel->existCategoryId($categoryId)){
            $this->view->redirect("/categories/manage","This category do not exist!");
        }

        try{
            $categoryName = $categoryModel->getCategoryNameById($categoryId);

            $this->view->render("category/delete",[
                    "name"=> $categoryName,
                    "id"=> $categoryId
                ]
            );
        }catch (\Exception $exception) {
            $this->view->redirect("/categories/manage", $exception->getMessage());
        }

    }

    /* handles the delete post logic */
    public function deletePost(){

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not add new category if you are not logged in!");
        }

        if (!$this->auth->isInRole("admin")) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not Admin!");
        }

        if ($this->input->post("delete_category") === null){
            $this->view->redirect("/categories/manage");
        }

        if (empty($this->input->get(0,"int"))){
            $this->view->redirect("/categories/manage");
        }

        $categoryId = $this->input->get(0,"int");

        $categoryModel = new CategoriesModel();
        try{
            if (!$categoryModel->existCategoryId($categoryId)){
                $this->view->redirect("/categories/manage","This category do not exist!");
            }
            if ($categoryModel->deleteCategory($categoryId)){
                $this->view->redirect("/categories/manage","Category deleted successfully!","success");
            }
        } catch (\Exception $exception) {
            $this->view->redirect("/categories/manage", $exception->getMessage());
        }
    }

    /* handles adding new category */
    public function addPost(){

        if ($this->input->post("category_create") === null){
            $this->view->redirect("/categories/manage");
        }

        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not add new category if you are not logged in!");
        }

        if (!$this->auth->isInRole("admin")) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not Admin!");
        }

        $categoryName = $this->input->post("category_name");

        $this->validate->setRule("minlength",$categoryName,3, "Category name length must be more then 3 symbols!");

        if ($this->validate->validate() === false){
            $error = $this->validate->getErrors();
            $this->view->redirect("/categories/manage",$error);
        }

        $categoryModel = new CategoriesModel();
        try{
            if ($categoryModel->hasCategory($categoryName)){
                $this->view->redirect("/categories/manage","This category already exist!");
            }

            if($categoryModel->addNewCategory($categoryName)){
                $this->view->redirect("/categories/manage","Category created successfully!","success");
            }
        }catch (\Exception $exception){
            $this->view->redirect("/categories/manage",$exception);
        }
    }

    /* Display Edit form for categories */
    public function edit()
    {
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not logged in!");
        }
        if (!$this->auth->isInRole("admin")) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not Admin!");
        }

        if (empty($this->input->get(0,"int"))){
            $this->view->redirect("/categories/manage");
        }

        $categoryId = $this->input->get(0,"int");

        $categoryModel = new CategoriesModel();
        try{

            if (!$categoryModel->existCategoryId($categoryId)){
                $this->view->redirect("/categories/manage","This category do not exist!");
            }
            $categoryName = $categoryModel->getCategoryNameById($categoryId);

            $this->view->render("category/edit",[
                    "name"=> $categoryName,
                    "id"=> $categoryId
                ]
            );

        } catch (\Exception $exception){
            $this->view->redirect("/categories/manage",$exception->getMessage());
        }

    }

    /* Handle the post from the edit form */
    public function editPost()
    {
        if (!$this->auth->isLogged()) {
            $this->view->redirect("/user/login", "You can not add new category if you are not logged in!");
        }

        if (!$this->auth->isInRole("admin")) {
            $this->view->redirect("/user/login", "You can not manage categories if you are not Admin!");
        }

        if ($this->input->post("edit_category") === null){
            $this->view->redirect("/categories/manage");
        }

        if (empty($this->input->get(0,"int"))){
            $this->view->redirect("/categories/manage");
        }

        $categoryId = $this->input->get(0,"int");

        $categoryName = $this->input->post("name");

        $categoryModel = new CategoriesModel();
        try{
            if (!$categoryModel->existCategoryId($categoryId)){
                $this->view->redirect("/categories/manage","This category do not exist!");
            }

            if ($categoryModel->editCategory($categoryName, $categoryId)){
                $this->view->redirect("/categories/manage","Category edited successfully!","success");
            }
        }catch (\Exception $exception) {
            $this->view->redirect("/categories/manage", $exception->getMessage());
        }
    }

}
