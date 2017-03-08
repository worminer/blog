<?php

namespace Controllers;


use MVC\DefaultController;


class Contact extends DefaultController
{
//    public function about()
//    {
//        $this->view->render("user/contact");
//    }
    public function show()
    {

        $this->view->render("contact/show");
    }

    public function message()
    {
        $input = [];
        $input['name'] = $this->input->get(0);
        $this->view->render("contact/sendingMessage", $input);
    }

}