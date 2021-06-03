<?php

namespace Database;

/**
 * Interface for the base ```Database``` class.
 * 
 * @author FakyZDev
 */
interface IDatabase{

    /**
     * Return the table name.
     * 
     * @return string
     */
    public function GetTable();

    /**
     * Need to implement in derived class. Should create the database
     * table.
     */
    public function CreateTable();
}