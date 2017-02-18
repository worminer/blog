<?php

class View {

    private $layoutName; // layout to load
    private $viewName; // view to load
    private $data = []; // data to load
    private $html;

    public function __construct(string $view, array $data, string $layout = DEFOULT_LAYOUT) {
        $this->viewName = $view;
        $this->data = $data;
        $this->layoutName = $layout;


        $this->render();
    }


    public function set($key, $value) {
        $this->data[$key] = $value;
    }
    public function loadLayout(){
        $layoutPath = VIEWS.DIRECTORY_SEPARATOR.$this->getLayoutName().TEMPLATE_EXT;
        if (!file_exists($layoutPath)) {
            return "Error loading template file ($layoutPath).";
        }
        $this->setHtml(file_get_contents($layoutPath));
    }

    public function loadView(string $viewName){
        $viewPath = VIEWS.DIRECTORY_SEPARATOR.$viewName."View".TEMPLATE_EXT;
        if (!file_exists($viewPath)) {
            return "Error loading View file ($viewPath).";
        }
        echo file_get_contents($viewPath) ;
    }

    public function insertBodyView() {
            $replaceTag = "{%body%}";
            $this->setHtml(str_replace($replaceTag, $this->loadView($this->getViewName()), $this->getHtml()));

    }

    public function replaceVariables() {

        foreach ($this->data as $key => $value) {
            $replaceTag = "{#$key}";
            $this->setHtml(str_replace($replaceTag, $value, $this->getHtml()));
        }
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }
    /**
     * @param int|string $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @return string
     */
    public function getViewName(): string
    {
        return $this->viewName;
    }

    /**
     * @param string $viewName
     */
    public function setViewName(string $viewName)
    {
        $this->viewName = $viewName;
    }
     /**
     * @return string
     */

    public function getLayoutName(): string
    {
        return $this->layoutName;
    }

    public function render(){
        $this->loadLayout();
        $this->insertBodyView();
        $this->replaceVariables();

        echo $this->getHtml();
    }

}