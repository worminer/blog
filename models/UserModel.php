<?php

namespace Models;

class UserModel extends \MVC\Database\PdoMysql
{
    /**
     * @var Crypt
     * holds an instance of Crypt
     */
    public $crypt;

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
        $result = $this ->prepare("SELECT username FROM users WHERE username=?",[$username]) // make a prepared statement
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
     * @param string $email
     * @param string $password
     * @return bool
     * @throws \Exception
     */
    public function tryRegisterUser(string $username, string $email, string $password):bool{

        //  create Salt for the user password .. this makes it more unique
        $salt = $this->getCrypt()->getNewSalt();
        // hashing the password by using the salt ..
        $passwordHash = $this->getCrypt()->getHash($password,$salt);

        if (!isset($username) || !isset($email) && !isset($passwordHash) && !isset($salt)) {
            throw new \Exception("ERROR: tryRegisterUser -> all parameters are Mandatory user={$username}, email={$email}, password={$password} <-");
        }

        $result = $this->prepare("INSERT INTO `users` (`username`, `email`, `pass_hash`, `pass_salt`) VALUES (?, ?, ?, ?)",
                                                        [$username, $email, $passwordHash, $salt])->execute();

        return (bool) $result->getAffectedRows();
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
        $salt = $this->getUserSalt($username);
        return false;
    }
    private function getCrypt() {
        if ($this->crypt === null) {
            $this->crypt    = \MVC\Crypt::getInstance();
        }
        return $this->crypt;
    }

    private function getUserSalt(){

    }
}