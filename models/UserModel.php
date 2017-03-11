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

        $result = $this->prepare("INSERT INTO `users` (`username`, `email`, `pass_hash`, `pass_salt`) VALUES (?, ?, ?, ?)",
                                                        [$username, $email, $passwordHash, $salt])->execute();
        return (bool) $result->getAffectedRows();
    }

    private function getCrypt() {
        if ($this->crypt === null) {
            $this->crypt    = \MVC\Crypt::getInstance();
        }
        return $this->crypt;
    }


}