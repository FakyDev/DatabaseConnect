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

namespace Database;

//Get files
require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

use Database\DatabaseEntry;
use Database\IDatabase;

use mysqli;

/**
 * Base class which allow you to create a connection to
 * mysql database, with ease.
 * 
 * @author FakyZDev
 */
abstract class Database implements IDatabase{

    /**
     * Hold the instance of DatabaseEntry.
     * 
     * @var DatabaseEntry
     */
    private static ?DatabaseEntry $entry = null;

    /**
     * The name of the table to query for.
     * 
     * @var string
     */
    private string $tableName;

    /**
     * Store the connection to mysql database.
     * 
     * @var mysqli|null
     */
    protected ?mysqli $connection = null;

    /**
     * Try to open connection on construct.
     * 
     * @param string $tableName The name of the table to query for.
     * 
     */
    public function __construct(string $tableName){
        //update reference
        $this->tableName = $tableName;

        //open connection
        $this->OpenConnection();
    }

    /**
     * Close connection, on destruct.
     * 
     * @return void
     */
    public function __destruct(){
        //Close connection
        $this->CloseConnection();
    }


    /**
     * Return the table name.
     * 
     * @return string
     */
    public function GetTable() : string{
        //return
        return $this->tableName;
    }

    /**
     * Need to implement in derived class. Should create the database
     * table.
     */
    public abstract function CreateTable() : bool;

    /**
     * Open connection to a mysql database.
     * 
     * Can retrieve connection with ```Database::$connection```.
     * 
     * If connection is already open, will return true.
     * 
     * @return bool True if success.
     */
    protected function OpenConnection() : bool{
        //Already have a connection
        if($this->connection !== null){
            return true;
        }

        //get instance
        $entryInstance = $this->GetEntryInstance();

        //Create connection
        //use '@' to not show error
        $conn = @new  \mysqli($entryInstance->GetHost(), $entryInstance->GetUsername(), $entryInstance->GetPassword(), $entryInstance->GetSchema());
        
        //Connection error
        if($conn->connect_errno){
            //Update reference
            $this->connection = null;
            
            //Return false
            return false;
        }
        
        //Update reference
        $this->connection = $conn;
        
        //Return true;
        return true;
    }
    
    /**
     * Close database connection.
     * 
     * @return void
     */
    protected function CloseConnection() : void{
        //Return if connection is null
        if($this->connection == null){
            return;
        }
        
        //Close connection
        $this->connection->close();
        
        //Update reference
        $this->connection = null;
    }


    /**
     * Get the last database connect error or query error, then return it in associative array.
     * 
     * @param msqli $conn Connection to database.
     * 
     * @return array Associative array
     */
    public function GetError() : array{
        //Connection problem
        if($this->connection->connect_errno){
            return ["code" => $this->connection->connect_errno, "msg" => $this->connection->connect_error];
        }
        //Query problem
        return ["code" => $this->connection->errno, "msg" => $this->connection->error];
    }

    /**
     * Try to create table, if last database error
     * is because of non-existing table.
     * 
     * @return array Associative array
     */
    protected function TableTroubleshoot() : void{
        //Check if any error occurred and get
        $error = $this->GetError();

        //Table don't exist
        if($error["code"] == 1146){
            //Create table
            $this->CreateTable();
        }
    }

    /**
     * Fetch and store the result in the appropriate way, then return.
     * 
     * @param mysqli_result $result After queried object.
     * 
     * @return array Associative array, if only one row was selected. Multi-demensional array if, more than one row was seleceted. Empty array if no result.
     */
    public function GetResult(\mysqli_result $result) : array{
        //Get result in array
        if($result->num_rows > 0){
            if($result->num_rows == 1){
                //Get result
                $arr = $result->fetch_assoc();
            } else {
                //Declare array
                $arr = [];

                //Update the array
                while($value = $result->fetch_assoc()){
                    $arr[] = $value;
                }
            }
        }

        //Free result
        $result->free_result();
        
        //retrn result
        if(!isset($arr)){
            return [];
        }
        return $arr;
    }


    /**
     * Return the DatabaseEntry instance.
     * 
     * @return DatabaseEntry
     */
    protected function GetEntryInstance() : DataBaseEntry{
        //check if null
        if(self::$entry == null){
            //update instance
            self::$entry = new DataBaseEntry($_SERVER['DOCUMENT_ROOT'] . '/config', 'settings');
        }

        //return instance
        return self::$entry;
    }

    /**
     * Escape string to avoid mysql injection.
     * 
     * @param string $word The word/sentence to fix.
     * 
     * @return string|false False, if failed to connect to database.
     */
    protected function EscapeString(string $word){
        //Try to open connection
        if(!$this->OpenConnection()){
            return false;
        }

        //Escape character and return
        return $this->connection->escape_string($word);
    }
}
