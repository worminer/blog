<?php
namespace Controllers;

use Models\ArticleModel;
use MVC\DefaultController;

class Article extends DefaultController
{
    public function create()
    {
        $this->view->render("article/create",[
            "articles" =>   [
                                "title of article 1" => " article 1 value",
                                "title of article 2" => "article 2 text"
                            ],
            "author"  => ["name" =>  "John Doe"]
        ]);

    }

    public function createPost()
    {
        $input = $this->input;
        // потребителя не е логнат.. за целта е редно 1во да видиш дали има логнат потребител
        if (!$this->auth->isLogged())
        {
            // ако не е логнат му кажи да годи да се разходи
            // за редиректване има функция написана
            // ако не е логнат и иска да пише артикъл.. то тогава трябва да го пратим да се логне
            $this->view->redirect("/user/login","You cant write an article if you are not logged in!");
        }
        // ако потребителя е логнат .. тоест това е върнало true то тогава ние със сигурнос
        // можем да вземем ид то на логнатия потребиел..
        $author_id = $this->auth->getCurrentUserId();

        $title = $input->post('title');
        $content = $input->post('content');

       $articleModel=new ArticleModel();

      if($articleModel->create($author_id,$title,$content)) {
          // ако се е записало в базата с дани то тогава трябва да насочим потребителя към
          // виуто което показва артикълите примерно .. за целта го редиректваме
          // впрочем нещо което не е видно е че 2рия параметър на редиректа е съобщение а 3тия е типа на съпбщението..
          $this->view->redirect("/article/show","Article Created Successfully.","success");
          //$this->view->render("article/create", [ "articles" => $result]);
      }else{
          //леле колко ме дразни като не ти дописва :)
          // ако поради някаква причина не се е записало правилно в базата с данни.. то ние искаме да видим защо и да го изведем на потребителя ..
          //съобщението можем да го вземем от модела..
          $errorMessage = $articleModel->getErrorMessage();
          $this->view->redirect("/article/create",$errorMessage,"error");
      }
    }

public function  show(){
    $results = [
        "key" => "value",
        "result2" => "value of result 2"
    ];
    $varsToSend = [
        "results" => $results
    ];
    $this->view->render("article/showPost",$varsToSend);
}

/*public function showPost(){
    $author_id = $this->auth->getCurrentUserId();
    $articleModel = new  \Models\ArticleModel();
    $result=$articleModel->show($author_id);
    $this->view->render("article/showPost",$result);
}*/

}
