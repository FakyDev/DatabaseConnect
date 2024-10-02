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

namespace File;

//Get files
//require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

use File\IFile;

/**
 * Base class for ```File``` derived class.
 * 
 * @author FakyZDev
 */
abstract class File implements IFile{

    /**
     * The name of the file.
     * 
     * Example: 'Test'
     * 
     * @var string
     */
    private string $name;

    /**
     * The extension of the file.
     * 
     * Example: 'php' (should not contain the '.')
     * 
     * @var string
     */
    private string $extension;

    /**
     * The path to the file.
     * 
     * Example: 'dir/subDir'
     * 
     * @var string
     */
    private string $path;

    /**
     * Update reference. Does not check if file exists.
     * 
     * @param string $path The path to file. E.g 'dir/subDir'.
     * @param string $fileName The name of the file. E.g 'test'.
     * @param string $fileExtension The extension of the file. E.g 'php'.
     * 
     * Note: ```$fileExtension``` does not contain '.'.
     */
    public function __construct(string $path, string $fileName, string $fileExtension){
        //update reference
        $this->name = $fileName;
        $this->extension = $fileExtension;
        $this->path = $path;
    }

    /**
     * Check if file exists.
     * 
     * @return bool False, if does not exists.
     */
    public function CheckFile() : bool{
        return is_file($this->GetFilePath());
    }

    /**
     * Return the full file path.
     * 
     * Example: 'dir/subDir/test.php'
     * 
     * @return string
     */
    public function GetFilePath(){
        return ($this->path . '/' . $this->name . '.' . $this->extension);
    }

    /**
     * Create the file.
     * 
     * @return bool False, if failed.
     */
    public function CreateFile() : bool{
        //get full path
        $fullPath = $this->GetFilePath();

        //Check if file already exist
        if(is_file($fullPath)){
            return false;
        }

        //Create directory if not exists
        if(!file_exists(pathinfo($fullPath, PATHINFO_DIRNAME))){
            mkdir(pathinfo($fullPath, PATHINFO_DIRNAME), 0777, true);
        }

        //Create file
        $file = fopen($fullPath, 'w');

        //Close file
        fclose($file);

        //return
        return($this->CheckFile());
    }

    /**
     * Need to implement in derived class. Should return file content.
     * 
     * @return void
     */
    public abstract function GetFile();

    /**
     * Need to implement in derived class. Should update file content.
     * 
     * @return void
     */
    public abstract function UpdateFile($newContent);
}