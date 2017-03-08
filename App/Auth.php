<?php


namespace MVC;


use MVC\Database\PdoMysql;

/**
 * Class Auth
 * @package MVC
 * authentication logic
 */
class Auth extends PdoMysql
{
    /**
     * @var null
     */
    private static $_instance = null;
    
    private $_session = null;

    private $currentUserID = null;


    /**
     * @var Crypt
     * holds an instance of Crypt
     */
    public $crypt;
    public $errorMessage;

    /**
     * Auth constructor.
     */
    public function __construct()    {
        parent::__construct();
        $this->isValidSessionToken();
    }


    /**
     * @return Auth
     */
    public static function getInstance():Auth{
        if (self::$_instance == null) {
            self::$_instance = new Auth();
        }
        return self::$_instance;
    }

    /**
     * @param string $username
     * @return bool
     * well check if username exist in DB
     */
    public function userExist(string $username):bool{
        // in prepare you have to put "sql query with ? for placeholder", [array of values for the placeholder]
        // we don't use SELECT * because we dont need all the info..
        // instead we get only the id..
        //  and that makes the query way faster then if we use SELECT *
        $result = $this->prepare("SELECT id FROM users WHERE username=?",[$username]) // make a prepared statement
        ->execute(); // and after that this needs to be executed so you can receive the result if there is any
        // after that we need to get the results from the PDO return so we do fetchRllAssoc() ..
        // this will return the fist result or false if there are no results
        $result = $result->fetchRllAssoc();

        // if there are no result, we return false
        if ($result === false) {
            return false;
        }
        // if there is an result, return true
        return true;
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     * @throws \Exception
     * checks if the password matches for this user
     */
    public function authenticate(string $username, string $password):bool {
        if (!isset($username) || !isset($password)) {
            throw new \Exception("Error: Usermodel->Authenticate-> you did not set Username or Password");
        }
        $result = $this ->prepare("SELECT pass_hash,pass_salt FROM users WHERE username=?",[$username]) // make a prepared statement
        ->execute(); // and after that this needs to be executed so you can receive the result if there is any
        // after that we need to get the results from the PDO return so we do fetchRllAssoc() ..
        // this will return the fist result or false if there are no results
        // if there are no result, we return false
        if ($result === false) {
            // you should never come here because there is a check if the user exist before that.. but better be save then sorry
            $this->setErrorMessage("Error:There is no such user in Database..");
            return false;
        }

        $result = $result->fetchRllAssoc(); // getting the result

        $passSalt = $result["pass_salt"]; // salt from database
        $passHash = $result["pass_hash"]; // password hash from database


        //var_dump(password_verify($password,$passHash));
        if (!password_verify($password,$passHash)) {
            $this->setErrorMessage("Password does not match!");
            return false;
        }

        // create unique token to identify user
        $sessionToken = $this->getCrypt()->getNewSalt().$this->getCrypt()->getNewSalt();
        

        $sessionLifeTime = time() + $this->getSession()->getLifeTime();

        // try save it to DB
        $result = $this->prepare("UPDATE `users` SET `session_token`=?,`session_token_expire`=? WHERE username=?",
            [$sessionToken,$sessionLifeTime,$username])->execute();

        // if db change is not successful we return an error
        if (!(bool)$result->getAffectedRows()) {
            $this->setErrorMessage("Something went wrong with DB! .. session token did not get saved!");
            return false;
        }
        // if everything is ok we save the same token in session
        $this->getSession()->AuthToken = $sessionToken;

        return true;
    }

    public function isValidSessionToken(){
        // if there is no session token .. session is not valid so return false

        if ($this->getSession()->AuthToken === null) {
            return false;
        }

        $sessionToken = $this->getSession()->AuthToken;
        $result = $this ->prepare("SELECT id,session_token_expire FROM users WHERE session_token=?",[$sessionToken])->execute();
        //if there is no such token in the user table .. session is not valid.. so return false
        if ($result === false) {
            return false;
        }

        $result = $result->fetchRllAssoc(); // getting the result

        // if expiration time is bigger then current time.. session expired .. soo return false
        if ($result['session_token_expire'] < time()) {
            return false;
        }

        // if we are here in code that means that everything is ok and we can save the user and update the time
        $this->setCurrentUserId($result['id']);

        // and we extend the lifetime of the users token
        if ($this->renewSessionLifeTime($sessionToken)) {
            return false;
        }

        return true;
    }

    private function renewSessionLifeTime($sessionToken){
        $session = $this->getSession();
        $lifeTime = time() + $session->getLifeTime();
        $session->AuthToken = $sessionToken;

        $result = $this->prepare("UPDATE `users` SET `session_token_expire`=? WHERE session_token=?",
            [$lifeTime,$sessionToken])->execute();
        return (bool) $result->getAffectedRows();
    }

    private function setCurrentUserId(int $id){
        $this->currentUserID = $id;
    }

    public function getCurrentUserId(){
        if ($this->currentUserID === null) {
            $this->isValidSessionToken();
        }
        return $this->currentUserID;
    }
    public function isLogged():bool{
        if ($this->getCurrentUserId() == null) {
            return false;
        }
        return true;
    }
    
    public function getRole(){
        //TODO:implement get role
    }

    public function logOut(){
        $this->getSession()->AuthToken = '';
    }

    public function getErrorMessage(){
        return $this->errorMessage;
    }

    private function setErrorMessage(string $messsage){
        $this->errorMessage = $messsage;
    }

    private function getCrypt() {
        if ($this->crypt === null) {
            $this->crypt    = \MVC\Crypt::getInstance();
        }
        return $this->crypt;
    }

    private function getSession() {
        if ($this->_session === null) {
            $this->_session    = \MVC\App::getInstance()->getSession();
        }
        return $this->_session;
    }
}