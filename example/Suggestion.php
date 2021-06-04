<?php

/**
 * Copyright 2021 FakyZDev

 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * Github:
 * @link https://github.com/FakyZDev/
 * 
*/

declare(strict_types=1);

//Get files
require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

use Database\DataBase;

/**
 * Class which allow you to query ```suggestion``` table.
 * 
 * @author FakyZDev
 */
class Suggestion extends Database{

    /**
     * Set table name.
     */
    public function __construct(){
        //call parent constructor
        parent::__construct('suggestion');
    }

    /**
     * Create the table.
     * 
     * @return bool False, if failed.
     */
    public function CreateTable() : bool{
        //open connection
        if($this->OpenConnection()){
            //get entry
            $entryInstance = $this->GetEntryInstance();

            //query request
            //escape string as didn't use prepare statement
            $query = "CREATE TABLE IF NOT EXISTS `" . $this->EscapeString($entryInstance->GetSchema()) . "`.`" . $this->EscapeString($this->GetTable()) . "` (`id` INT NOT NULL AUTO_INCREMENT, `email` VARCHAR(320) NOT NULL, `subject` VARCHAR(40) NOT NULL, `content` VARCHAR(1024), PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;";
            
            //query and return value
            return $this->connection->query($query);
        }

        //failed to open connection
        return false;
    }

    /**
     * Insert a row, in the database with data.
     * 
     * @param string $email The email address.
     * @param string $title The subject.
     * @param string $content The content.
     * 
     * Note: ```$email``` should be valid, otherwise will return false.
     * 
     * @return bool False, on failure.
     */
    public function InsertSuggestion(string $email, string $title, string $content){
        //check if valid email
        if(!self::ValidateEmail($email)){
            return false;
        }

        //open connection
        if($this->OpenConnection()){
            //query request
            $query = "INSERT INTO `" . $this->EscapeString($this->GetTable()). "` (`id`, `email`, `subject`, `content`) VALUES (NULL, ?, ?, ?);";
            
            //prepare query
            $queryStmt = $this->connection->prepare($query);
            //check if failed to prepare
            if($queryStmt === false){
                //create table if don't exists
                $this->TableTroubleshoot();

                //return false
                return false;
            }

            //bind parameter
            $queryStmt->bind_param('sss', $email, $title, $content);

            //perform query
            $perform = $queryStmt->execute();

            //return value
            return $perform;
        }

        //failed to connect
        return false;
    }

    /**
     * Perform a ```SELECT``` query on the table, with the parameters.
     * 
     * @param string $selected The fields to select.
     * @param string $conditions The conditions to apply. Leave if there is no condition.
     * 
     * IMPORTANT: ```$conditions``` should be string escape.
     * 
     * @return \mysqli_result|bool|void Depend on context and null if failed to connect.
     */
    private function SelectSuggestion(string $selected, string $conditions = null){
        //open connection
        if($this->OpenConnection()){
            if($conditions !== null){
                //query request
                $query = "SELECT " . $this->EscapeString($selected) . " FROM `" . $this->EscapeString($this->GetTable()) . "` WHERE " . $conditions . ";";
            } else {
                //query request
                $query = "SELECT " . $this->EscapeString($selected) . " FROM `" . $this->EscapeString($this->GetTable()) . "`;";
            }
            //perform query
            $result = $this->connection->query($query);

            //create table if don't exists
            $this->TableTroubleshoot();

            //return 
            return $result;
        }

        //fail to connect
        return null;
    }

    /**
     * Retrieves the suggestion whose id matched.
     * 
     * @param int $id The suggestion id.
     * 
     * @return bool|array False if failed otherwise array.
     */
    public function GetSuggestionById(int $id){
        //fields to select
        $fields = "`subject`, `content`, `email`";

        //conditions
        //didn't escape as int
        $conditions = "`id` = '" . $id . "'";

        //perform query
        $result = $this->SelectSuggestion($fields, $conditions);

        //check if failed to connect
        //or failed to perform
        if($result === false || $result == null){
            return false;
        }

        //retrieve result
        $result = $this->GetResult($result);
        //don't exists
        if(count($result) == 0){
            return false;
        }

        //return result
        return $result;
    }

    /**
     * Retrieves all suggestions sent by an email.
     * 
     * @param string $email The email
     * 
     * @return bool|array False if failed otherwise multi-dimensional array.
     */
    public function GetSuggestionsByEmail(string $email){
        //fields to select
        $fields = "`subject`, `content`, `id`";

        //conditions
        $conditions = "`email` = '" . $this->EscapeString($email) . "'";

        //perform query
        $result = $this->SelectSuggestion($fields, $conditions);

        //check if failed to connect
        //or failed to perform
        if($result === false || $result == null){
            return false;
        }

        //retrieve result
        $result = $this->GetResult($result);
        //don't exists
        if(count($result) == 0){
            return false;
        }

        //check if retrieve is only one row
        if(isset($result[0])){
            if(!is_array($result[0])){
                //convert to multi-dimensional
                $result = [[$result]];
            }
        } else {
            //convert to multi-dimensional
            $result = [[$result]];
        }

        //return result
        return $result;
    }

    /**
     * Retrieves all suggestions where subject matches.
     * 
     * @param string $subject The subject.
     * 
     * @return bool|array False if failed otherwise multi-dimensional array.
     */
    public function GetSuggestionsBySubject(string $subject){
        //fields to select
        $fields = "`email`, `subject`, `content`, `id`";

        //conditions
        $conditions = "`subject` LIKE '%" . $this->EscapeString($subject) . "%'";

        //perform query
        $result = $this->SelectSuggestion($fields, $conditions);

        //check if failed to connect
        //or failed to perform
        if($result === false || $result == null){
            return false;
        }

        //retrieve result
        $result = $this->GetResult($result);
        //don't exists
        if(count($result) == 0){
            return false;
        }

        //check if retrieve is only one row
        if(isset($result[0])){
            if(!is_array($result[0])){
                //convert to multi-dimensional
                $result = [[$result]];
            }
        } else {
            //convert to multi-dimensional
            $result = [[$result]];
        }

        //return result
        return $result;
    }

    /**
     * Retrieves all suggestions.
     * 
     * @return bool|array False if failed otherwise multi-dimensional array.
     */
    public function GetAllSuggestions(){
        //fields to select
        $fields = "`email`, `subject`, `content`, `id`";

        //conditions
        $conditions = null;

        //perform query
        $result = $this->SelectSuggestion($fields, $conditions);

        //check if failed to connect
        //or failed to perform
        if($result === false || $result == null){
            return false;
        }

        //retrieve result
        $result = $this->GetResult($result);
        //don't exists
        if(count($result) == 0){
            return false;
        }

        //check if retrieve is only one row
        if(isset($result[0])){
            if(!is_array($result[0])){
                //convert to multi-dimensional
                $result = [[$result]];
            }
        } else {
            //convert to multi-dimensional
            $result = [[$result]];
        }

        //return result
        return $result;
    }


    /**
     * Check if email, is valid.
     * 
     * @param string $email The email to verify.
     * 
     * @return bool True if valid.
     */
    public static function ValidateEmail(string $email) : bool{ 
        return (boolean) filter_var($email, FILTER_VALIDATE_EMAIL); 
    }
}