<?php

namespace Models;


use MVC\Crypt;

class UserModel extends \MVC\Auth
{
    /**
     * @var Crypt
     * holds an instance of Crypt
     */
    public $crypt;

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return bool
     * @throws \Exception
     */
    public function tryRegisterUser(string $username, string $email, string $password):bool{

        if (!isset($username) || !isset($email) && !isset($passwordHash) && !isset($salt)) {
            throw new \Exception("ERROR: tryRegisterUser -> all parameters are Mandatory user={$username}, email={$email}, password={$password} <-");
        }

        //  create Salt for the user password .. this makes it more unique
        $salt = $this->getCrypt()->getNewSalt();
        // hashing the password by using the salt ..
        $passwordHash = $this->getCrypt()->getHash($password,$salt);

        $result = $this->prepare("INSERT INTO `users` (`username`, `email`, `pass_hash`, `pass_salt`) VALUES (?, ?, ?, ?)",[$username, $email, $passwordHash, $salt])->execute();

        // if that failed .. go to return
        if (!$result->getAffectedRows()) {
            return false;
        }

        // inserting roles
        $result = $this->prepare("INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES ((SELECT users.id FROM users WHERE username=?), (SELECT roles.id FROM roles WHERE role_name='user'))", [$username])->execute();

        // if that failed .. go to return
        if (!$result->getAffectedRows()) {
            return false;
        }
        // or just return true ..
        return (bool) $result->getAffectedRows();
    }
    public function test(){


    }

    private function getCrypt() {
        if ($this->crypt === null) {
            $this->crypt    = \MVC\Crypt::getInstance();
        }
        return $this->crypt;
    }


}