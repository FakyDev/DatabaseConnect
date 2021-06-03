<?php

namespace File;

/**
 * Interface for the base file class.
 * 
 * @author FakyZDev
 */
interface IFile{

    /**
     * Check if file exists.
     * 
     * @return bool False, if does not exists.
     */
    public function CheckFile();

    /**
     * Return the full file path.
     * 
     * Example: 'dir/subDir/test.php'
     * 
     * @return string
     */
    public function GetFilePath();

    /**
     * Need to implement in derived class. Should return file content.
     * 
     * @return void
     */
    public function GetFile();

    /**
     * Need to implement in derived class. Should update file content.
     * 
     * @return void
     */
    public function UpdateFile($newContent);
}