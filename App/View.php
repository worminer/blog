<?php
namespace MVC;

class View {

    /**
     * @var View
     */
    private static $_instance = null;
    /**
     * @var Config
     */
    private $_config = null;

    /**
     * @var string
     */
    private $layoutName; // layout to load
    /**
     * @var string
     */
    private $viewName; // view to load
    /**
     * @var array
     */
    private $data = []; // data to load
    /**
     * @var MessagesManager
     *  holds instance of massage manager
     */
    private $_messageManager = null;
    /**
     * @var
     * holds the loaded html
     */
    private $html;
    /**
     * @var array
     * hold the view configuration array
     */
    private $viewConfig = null;

    /**
     * @param string $view
     * @param array  $data
     * @param array  $messages
     * @param string|null $layout
     */
    public function render(string $view, array $data = [], array $messages = [], string $layout = null){
        
        $this->viewConfig = $this->getConfig()->view;
        $this->viewName = $view;
        $this->data = array_merge($this->data, $data);
        if ($layout == null) {
            $this->layoutName = $this->viewConfig["DEFAULT_LAYOUT"];
        } else {
            $this->layoutName = $layout;
        }

        // start doing the hard work

        if ($this->getConfig()->app["debugging"]) {
            $startTime = microtime(true);
        }

        $this->loadLayout();

        $this->insertBodyView();

        $this->insertAllPartials();

        $this->insertMessages();

        $this->replaceGlobalVariables();

        $this->replaceVariables();

        $this->executeHelperBlocks();

        if (isset($this->getConfig()->app["auto_insert_site_root"]) && $this->getConfig()->app["auto_insert_site_root"] === true) {
            $this->insertURLPublicFolderPrefix($this->getConfig()->app["site_root"]);
        }

        if ($this->getConfig()->app["debugging"]) {
            echo "Template Engine -> View Preparation time : ". (microtime(true) - $startTime) ." seconds".PHP_EOL;
        }

        die($this->getHtml()) ;


    }

    /**
     * @param string$path
     * @param array|string $messages
     * @param string $type
     */
    public function redirect(string $path,$messages = null, $type = "error") {
        // TODO: да се направи проверка дали съобщението е от позволените типове..
        if ($messages != null) {
            $this->getMessageManager()->setMessage($type, $messages);
        }
        if ($this->getConfig()->app["auto_insert_site_root"]) {
            header("Location: {$this->getConfig()->app["site_root"]}{$path}");
        } else {
            header("Location: {$path}");
        }

        die();
    }

    /**
     *
     */
    public function insertMessages(){
        // TODO: FIX THIS so messages will be inserted inside the template
        var_dump($this->getMessageManager()->getAllMessages());
        $this->getMessageManager()->flushAllMessages();
    }

    /**
     * @return MessagesManager
     */
    public function getMessageManager(){

        if (!$this->_messageManager INSTANCEOF MessagesManager) {
            $this->_messageManager = MessagesManager::getInstance();
        }
        return $this->_messageManager;
    }

    public function getConfig(){
        if ($this->_config == null) {
            $this->_config = Config::getInstance();
        }
        return $this->_config;
    }

    /**
     * @return View
     */
    public static function getInstance():View{
        if (self::$_instance == null) {
            self::$_instance = new View();
        }
        return self::$_instance;
    }


    /**
     * @throws \Exception
     */
    public function loadLayout(){

        $layoutPath = $this->viewConfig["VIEW_FOLDER"].DIRECTORY_SEPARATOR.$this->getLayoutName().$this->viewConfig["TEMPLATE_EXT"];

        if (!file_exists($layoutPath)) {
            throw new \Exception("Error loading template file ($layoutPath).");
        }
        $this->setHtml(file_get_contents($layoutPath));
    }

    /**
     * @param string $viewName
     * @return string
     */
    public function loadView(string $viewName){
        $viewPath = $this->viewConfig["VIEW_FOLDER"].DIRECTORY_SEPARATOR.$viewName."View".$this->viewConfig["TEMPLATE_EXT"];
        if (!file_exists($viewPath)) {
            return "Error loading View file ($viewPath).";
        }
        return file_get_contents($viewPath) ;
    }

    /**
     *
     */
    public function insertBodyView() {
            $this->setHtml(str_replace($this->viewConfig["TEMPLATE_BODY_TAG"], $this->loadView(trim($this->getViewName())), $this->getHtml()));
    }

    /**
     *  will check for helpers and execute them
     */
    public function executeHelperBlocks(){
        $viewConfig =  $this->viewConfig;
        $findIfElseBlock = '/' . $viewConfig["IF_BLOCK_START"] . '(?:.*?)' . $viewConfig["IF_BLOCK_END"] . '/si'; // matches everything between {{#if author}} and {{/if}}
        $findUnlessBlock = '/'. $viewConfig["UNLESS_BLOCK_START"] . '(?:.*?)' . $viewConfig["UNLESS_BLOCK_END"] .'/si'; // matches Unless block
        $findEachBlock   = '/'. $viewConfig["EACH_BLOCK_START"] . '(?:.*?)' . $viewConfig["EACH_BLOCK_END"] .'/si'; // matches Each block

        $this->setHtml(preg_replace_callback_array(
            [
                $findIfElseBlock    => function ($match) {return $this->preformIfBlock($match[0]);},
                $findUnlessBlock    => function ($match) {return $this->preformUnlessBlock($match[0]);},
                $findEachBlock      => function ($match) {return $this->preformEachBlock($match[0]);},
            ]
            ,$this->getHtml(),-1,$expressionCounter));
    }

    public function preformEachBlock(string $block){
        $viewConfig =  $this->viewConfig;
        preg_match('/' . $viewConfig["EACH_BLOCK_START"] . '/i',$block,$matchesIF); // find if there is else in the if block
        $varName = $matchesIF[2];  // get the variable name

        $arrayVariable = null;
        // check if variable is set and if it is not we return the original input
        if (isset($this->data[$varName])) {
            $arrayVariable = $this->data[$varName];
        } else {
            return $block;
        }

        // if this is not an array return the block back
        if (!is_array($arrayVariable)) {
            return $block;
        }

        // check if there is and else block
        preg_match('/' . $viewConfig["EACH_BLOCK_ELSE"] . '/i',$block,$matchesElse); // find if there is else in the if block

        if (count($matchesElse) > 0) {
            preg_match('/' . $viewConfig["EACH_BLOCK_START"] . '(.*)' . $viewConfig["EACH_BLOCK_ELSE"] . '(.*)' . $viewConfig["EACH_BLOCK_END"] . '/si',$block,$matchesParts);
            // check if the if statement is true or false and return the proper awnser
            //var_dump($matchesParts);
            if (count($arrayVariable) > 0) {
                $matchedString = $matchesParts[3];
            } else {
                $matchedString = $matchesParts[5];
            }

        } else {
            preg_match('/' . $viewConfig["EACH_BLOCK_START"] . '(.*)' . $viewConfig["EACH_BLOCK_END"] . '/si',$block,$matchesParts);
            // check if the if statement is true or false and return the proper awnser
            if (count($arrayVariable) > 0) {
                $matchedString = $matchesParts[3];
            } else {
                $matchedString = '';
            }
        }
        $result = "";
        $paterns = [
            '/'.$viewConfig["EACH_BLOCK_KEY_PARAM"].'/i',
            '/'.$viewConfig["EACH_BLOCK_VALUE_PARAM"].'/i',
            '/'.$viewConfig["EACH_BLOCK_INDEX_PARAM"].'/i',
            '/'.$viewConfig["EACH_BLOCK_NUMBER_PARAM"].'/i',
        ] ;
        $counter = 0;
        // parse the string and put the variables inside
        foreach ($arrayVariable as $key => $value){
            $values = [
                $key,
                $value,
                $counter,
                $counter+1,

            ] ;
            $result .= preg_replace($paterns, $values, $matchedString);
            $counter++;
        }
        return $result ;


    }

    public function preformUnlessBlock(string $block){

        preg_match('/' . $this->viewConfig["UNLESS_BLOCK_START"] . '/i',$block,$matchesIF); // find if there is else in the if block
        $varName = $matchesIF[2];  // get the variable name

        $boolVariable = null;
        // check if variable is set and if it is not we return the original input
        if (isset($this->data[$varName])) {
            $boolVariable = $this->data[$varName];
        } else {
            return $block;
        }

        preg_match('/' . $this->viewConfig["UNLESS_BLOCK_START"] . '(.*)' . $this->viewConfig["UNLESS_BLOCK_END"] . '/si',$block,$matchesParts);
        // check if the if statement is true or false and return the proper awnser
        if ($boolVariable === false) {
            return $matchesParts[3];
        } else {
            return '';
        }
    }

    public function preformIfBlock(string $block):string {

        preg_match('/' . $this->viewConfig["IF_BLOCK_START"] . '/i',$block,$matchesIF); // find if there is else in the if block
        $varName = $matchesIF[2];  // get the variable name
        $boolVariable = null;
        // check if variable is set and if it is not we return the original input
        if (isset($this->data[$varName])) {
            $boolVariable = $this->data[$varName];
        } else {
            return $block;
        }

        // check if there is and else block
        preg_match('/' . $this->viewConfig["IF_BLOCK_ELSE"] . '/i',$block,$matchesElse); // find if there is else in the if block

        if (count($matchesElse) > 0) {
            preg_match('/' . $this->viewConfig["IF_BLOCK_START"] . '(.*)' . $this->viewConfig["IF_BLOCK_ELSE"] . '(.*)' . $this->viewConfig["IF_BLOCK_END"] . '/si',$block,$matchesParts);
            // check if the if statement is true or false and return the proper awnser
            if ($boolVariable === true) {
                return $matchesParts[3];
            } else {
                return $matchesParts[5];
            }
        } else {
            preg_match('/' . $this->viewConfig["IF_BLOCK_START"] . '(.*)' . $this->viewConfig["IF_BLOCK_END"] . '/si',$block,$matchesParts);
            // check if the if statement is true or false and return the proper awnser
            if ($boolVariable === true) {
                return $matchesParts[3];
            } else {
                return '';
            }
        }
    }
    /**
     * inserts path to public dir in all local links
     * href="home/index" becomes href="/Path/To/Public/home/index"
     * @param $publicFolder
     */
    public function insertURLPublicFolderPrefix($publicFolder){
        // matches src="" or src=''
        $patern[] = '/(?<=(?:src=)(?:\'|"))((?:\/|)[^http](?:\w+(?:\/|.|))*)(?=\'|")/i';
        // matches href="" or hreg=''
        $patern[] = '/(?<=(?:href=)(?:\'|"))((?:\/|)[^http](?:\w+(?:\/|.|))*)(?=\'|")/i';
//        preg_match_all($patern[0], $this->getHtml(),$matches);
//        var_dump($matches);
        $this->setHtml(preg_replace($patern, "{$publicFolder}$1", $this->getHtml()));
    }

    /**
     *
     */
    public function replaceVariables() {
        foreach ($this->data as $key => $value) {
            if (is_array($value)) {
                $replaceTag = str_replace("%VAR_NAME%"," *".$key." *",$this->viewConfig["TEMPLATE_VARIABLE"]);
                $this->setHtml(preg_replace("/$replaceTag/i", implode(" ", $value), $this->getHtml()));
            } else if (is_bool($value)) {
                if ($value) {
                    $value = "true";
                } else {
                    $value = "false";
                }
                $replaceTag = str_replace("%VAR_NAME%"," *".$key." *",$this->viewConfig["TEMPLATE_VARIABLE"]);
                $this->setHtml(preg_replace("/$replaceTag/i", $value  , $this->getHtml()));
            } else {
                $replaceTag = str_replace("%VAR_NAME%"," *".$key." *",$this->viewConfig["TEMPLATE_VARIABLE"]);
                $this->setHtml(preg_replace("/$replaceTag/i", $value, $this->getHtml()));
            }
        }
    }

    /**
     *
     */
    public function replaceGlobalVariables() {
        foreach ($this->getConfig()->app as $key => $value) {
            // ignore arrays because they are not supported
            if (!is_array($value)) {
                $replaceTag = str_replace("%VAR_NAME%"," *".$key." *",$this->viewConfig["TEMPLATE_GLOBAL_VARIABLE"]);
                $this->setHtml(preg_replace("/$replaceTag/i", $value, $this->getHtml()));
                //$this->setHtml(str_replace($replaceTag, $value, $this->getHtml()));
            }
        }
    }

    /**
     *
     */
    private function insertAllPartials(){
        foreach ($this->getListAllPartials() as $partialName) {
            $replaceTag = str_replace("%PARTIAL_NAME%"," *". $partialName ." *",$this->viewConfig["TEMPLATE_PARTIAL"]);
            //echo $replaceTag.PHP_EOL;
            $this->setHtml(preg_replace("/$replaceTag/i", $this->loadPartial($partialName), $this->getHtml()));
            //$this->setHtml(str_replace($replaceTag, "test", $this->getHtml()));
        }
    }

    /**
     * @return mixed
     */
    private function getListAllPartials (){
        $replaceTag = str_replace("%PARTIAL_NAME%"," *(\\w+) *",$this->viewConfig["TEMPLATE_PARTIAL"]);
        preg_match_all($replaceTag,$this->getHtml(),$matches);
        return $matches[1];
    }

    /**
     * @param string $name
     * @return string
     */
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
     * @return string
     */

    public function getLayoutName(): string
    {
        return $this->layoutName;
    }



}