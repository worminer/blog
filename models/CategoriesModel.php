<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/8/2017
 * Time: 9:34 PM
 */

namespace Models;


use MVC\Database\PdoMysql;

class CategoriesModel extends PdoMysql
{
    public function getCategories():array {
        try{
            $result = $this->prepare('SELECT `id`, `name` FROM `categories`')->execute();
        }catch (\Exception $exception){
            return false;
        }

        return $result->fetchAllAssoc();
    }
}