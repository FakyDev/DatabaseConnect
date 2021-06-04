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