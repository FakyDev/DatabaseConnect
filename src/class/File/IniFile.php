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
require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

use File\File;

/**
 * Class which allow to parse and update ini file.
 * 
 * @author FakyZDev
 */
class IniFile extends File{

    /**
     * Determine if parse ini file with section, if it contains.
     * 
     * @var bool
     */
    private bool $sections;

    /**
     * Overriden constructor.
     * 
     * @param string $path The path to file. E.g 'dir/subDir'.
     * @param string $fileName The name of the file. E.g 'test'.
     */
    public function __construct(string $path, string $fileName, bool $sections){
        //call parent constructor
        parent::__construct($path, $fileName, 'ini');
        //update reference
        $this->sections = $sections;
    }

    /**
     * Return file contents.
     * 
     * @return bool|array False, if failed.
     */
    public function GetFile(){
        //File don't exists
        if(!$this->CheckFile()){
            return false;
        }

        //Return content
        return parse_ini_file($this->GetFilePath(), $this->sections);
    }

    /**
     * Update file content. Does not support section.
     * 
     * @param array $newContent Associative array, containing the new data.
     * 
     * Important: If file is not created, will try to create; and if failed will
     * return false.
     * 
     * Note: all data in file will be overriden with ```$newContent```.
     * 
     * @return bool False, if failed.
     */
    public function UpdateFile($newContent) : bool{
        //check if file don't exists
        if(!$this->CheckFile()){
            //check if failed to create
            if(!$this->CreateFile()){
                return false;
            }
        }

        //Convert array to string
        $content = "";
        foreach($newContent as $k => $v){
            $content .= $k . " = " . $v . "\n";
        }

        //Update file
        $value = file_put_contents($this->path, $newContent);

        //Return value
        if($value === false){
            return false;
        } else {
            return true;
        }
    }
}