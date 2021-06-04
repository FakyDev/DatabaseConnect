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

use File\IniFile;

/**
 * Class for ```Database``` to get entry information, from an
 * ```IniFile```.
 * 
 * @author FakyZDev
 */
class DataBaseEntry{

    /**
     * Hold file instance.
     * 
     * @var IniFile
     */
    private $file;

    /**
     * Constructor
     * 
     * @param string $path Path to file which contains information.
     * 
     * Key should be: host, username, password, schema; and they should
     * in section "credential".
     * 
     * Note: Should be an ini file.
     * 
     * @return void
     */
    public function __construct(string $path, string $fileName){
        //create instance
        $this->file = new IniFile($path, $fileName, true);
    }

    /**
     * Return database hostname.
     * 
     * @return string Empty string if didn't found.
     */
    public function GetHost() : string{
        $content = $this->file->GetFile();
        //Check if not null
        if(isset($content["credential"]["host"]) && is_string($content["credential"]["host"])){
            return $content["credential"]["host"];
        }
        //Empty string
        return "";
    }

    /**
     * Return database username.
     * 
     * @return string Empty string if didn't found.
     */
    public function GetUsername() : string{
        $content = $this->file->GetFile();
        //Check if not null
        if(isset($content["credential"]["username"]) && is_string($content["credential"]["username"])){
            return $content["credential"]["username"];
        }
        //Empty string
        return "";
    }

    /**
     * Return database password.
     * 
     * @return string Empty string if didn't found.
     */
    public function GetPassword() : string{
        $content = $this->file->GetFile();
        //Check if not null
        if(isset($content["credential"]["password"]) && is_string($content["credential"]["password"])){
            return $content["credential"]["password"];
        }
        //Empty string
        return "";
    }

    /**
     * Return database schema.
     * 
     * @return string Empty string if didn't found.
     */
    public function GetSchema() : string{
        //Get content
        $content = $this->file->GetFile();
        //Check if not null
        if(isset($content["credential"]["schema"]) && is_string($content["credential"]["schema"])){
            return $content["credential"]["schema"];
        }
        //Empty string
        return "";
    }
}
?>