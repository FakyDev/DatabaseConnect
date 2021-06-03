<?php
declare(strict_types=1);

namespace Database\Suggestion;

//Get files
include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

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