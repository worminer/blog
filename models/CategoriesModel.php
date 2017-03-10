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

    public function hasCategory(string $categoryName){
        try{
            $result = $this->prepare('SELECT `id` FROM `categories` WHERE name=?', [$categoryName])->execute();
        }catch (\Exception $exception){
            return false;
        }
        return (bool) $result->fetchRllAssoc();
    }

    public function addNewCategory(string $categoryName):bool {
        try{
            $result = $this->prepare('INSERT INTO `categories` (`name`) VALUES (?)', [$categoryName])->execute();
        }catch (\Exception $exception){
            return false;
        }


        return (bool) $result->getAffectedRows();
    }
}