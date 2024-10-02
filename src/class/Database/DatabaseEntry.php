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

use File\IniFile;

/**
 * Class for ```Database``` to get entry information, from an
 * ```IniFile```.
 * 
 * @author FakyZDev
 */
class DatabaseEntry{

    /**
     * Hold file instance.
     * 
     * @var IniFile
     */
    private IniFile $file;

    /**
     * The section that connect the 
     */
    private string $sectionName;

    /**
     * Constructor
     * 
     * @param string $path Path to file which contains information.
     * @param string $fileName The name of the file that contains the information.
     * @param string $sectionName The section name that contains the information.
     * 
     * Key should be: host, username, password, schema; and they should
     * in section "credential".
     * 
     * Note: Should be an ini file.
     * 
     * @return void
     */
    public function __construct(string $path, string $fileName, string $sectionName){
        //create instance
        $this->file = new IniFile($path, $fileName, true);
        $this->sectionName = $sectionName;
    }

    /**
     * Return database hostname.
     * 
     * @return string Empty string if didn't found.
     */
    public function GetHost() : string{
        //get content
        $content = $this->file->GetFile();

        //get the host
        $host = $content[$sectionName]["host"];

        //Check if not null
        if(isset($host) && is_string($host)){
            return $host;
        }

        //get the fallback host
        $host = $content["host"];

        //Check if not null
        if(isset($host) && is_string($host)){
            return $host;
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
        //get content
        $content = $this->file->GetFile();

        //get the username
        $username = $content[$sectionName]["username"];

        //Check if not null
        if(isset($username) && is_string($username)){
            return $username;
        }

        //get the fallback username
        $username = $content["username"];

        //Check if not null
        if(isset($username) && is_string($username)){
            return $username;
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
        //get content
        $content = $this->file->GetFile();

        //get the username
        $password = $content[$sectionName]["password"];

        //Check if not null
        if(isset($password) && is_string($password)){
            return $password;
        }

        //get the fallback password
        $password = $content["password"];

        //Check if not null
        if(isset($password) && is_string($password)){
            return $password;
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

        //get the username
        $schema = $content[$sectionName]["schema"];

        //Check if not null
        if(isset($schema) && is_string($schema)){
            return $schema;
        }

        //get the fallback password
        $schema = $content["schema"];

        //Check if not null
        if(isset($schema) && is_string($schema)){
            return $schema;
        }

        //Empty string
        return "";
    }
}
?>
