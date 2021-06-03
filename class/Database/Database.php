<?php
declare(strict_types=1);

namespace Database;

//Get files
include_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

use Database\DataBaseEntry;
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
     * 
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
     * No need to use this method on (binding) value for prepared statement.
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