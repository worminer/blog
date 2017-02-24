<?php
namespace MVC;

class View {
    // this probably should get singleton but i am to lazy and it will be used just once anyway
    private $layoutName; // layout to load
    private $viewName; // view to load
    private $data = []; // data to load
    private $html;
    private $_config = null;
    private $viewConfig = null;

    public function __construct(string $view, array $data, string $layout = null) {
        $this->_config = Config::getInstance();
        $this->viewConfig = $this->_config->view;
        $this->viewName = $view;
        $this->data = $data;
        if ($layout == null) {
            $this->layoutName = $this->viewConfig["DEFAULT_LAYOUT"];
        } else {
            $this->layoutName = $layout;
        }


        $this->render();
    }

    public function loadLayout(){

        $layoutPath = $this->viewConfig["VIEW_FOLDER"].DIRECTORY_SEPARATOR.$this->getLayoutName().$this->viewConfig["TEMPLATE_EXT"];

        if (!file_exists($layoutPath)) {
            return "Error loading template file ($layoutPath).";
        }
        $this->setHtml(file_get_contents($layoutPath));
    }

    public function loadView(string $viewName){
        $viewPath = $this->viewConfig["VIEW_FOLDER"].DIRECTORY_SEPARATOR.$viewName."View".$this->viewConfig["TEMPLATE_EXT"];
        if (!file_exists($viewPath)) {
            return "Error loading View file ($viewPath).";
        }
        return file_get_contents($viewPath) ;
    }

    public function insertBodyView() {
            $this->setHtml(str_replace($this->viewConfig["TEMPLATE_BODY_TAG"], $this->loadView(trim($this->getViewName())), $this->getHtml()));
    }

    public function replaceVariables() {
        foreach ($this->data as $key => $value) {
            $replaceTag = str_replace("%VAR_NAME%"," *".$key." *",$this->viewConfig["TEMPLATE_VARIABLE"]);
            $this->setHtml(preg_replace("/$replaceTag/i", $value, $this->getHtml()));
        }
    }

    public function replaceGlobalVariables() {
        foreach ($this->_config->app as $key => $value) {
            // ignore arrays because they are not supported
            if (!is_array($value)) {
                $replaceTag = str_replace("%VAR_NAME%"," *".$key." *",$this->viewConfig["TEMPLATE_GLOBAL_VARIABLE"]);
                $this->setHtml(preg_replace("/$replaceTag/i", $value, $this->getHtml()));
                //$this->setHtml(str_replace($replaceTag, $value, $this->getHtml()));
            }
        }
    }

    private function insertAllPartials(){
        foreach ($this->getListAllPartials() as $partialName) {
            $replaceTag = str_replace("%PARTIAL_NAME%"," *". $partialName ." *",$this->viewConfig["TEMPLATE_PARTIAL"]);
            //echo $replaceTag.PHP_EOL;
            $this->setHtml(preg_replace("/$replaceTag/i", $this->loadPartial($partialName), $this->getHtml()));
            //$this->setHtml(str_replace($replaceTag, "test", $this->getHtml()));
        }
    }

    private function getListAllPartials (){
        $replaceTag = str_replace("%PARTIAL_NAME%"," *(\\w+) *",$this->viewConfig["TEMPLATE_PARTIAL"]);
        preg_match_all($replaceTag,$this->getHtml(),$matches);
        return $matches[1];
    }

    private function loadPartial(string $name){
        $partialPath = $this->viewConfig["PARTIALS_FOLDER"].DIRECTORY_SEPARATOR.$name."Partial".$this->viewConfig["TEMPLATE_EXT"];
        if (!file_exists($partialPath)) {
            return "Error loading Partial file ($partialPath).";
        }
        return file_get_contents($partialPath) ;
    }

    /**
     * @return mixed
     */
    private function getHtml()
    {
        return $this->html;
    }
    /**
     * @param int|string $html
     */
    private function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @return string
     */
    private function getViewName(): string
    {
        return $this->viewName;
    }

    /**
     * @param string $viewName
     */
    private function setViewName(string $viewName)
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
        $this->insertAllPartials();
        $this->replaceGlobalVariables();
        $this->replaceVariables();

        echo $this->getHtml();
    }

}