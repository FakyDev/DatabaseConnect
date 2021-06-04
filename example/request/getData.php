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

//Get files
require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

use Suggestion;

//determine output type
header('Content-type: application/json; charset=utf-8', true);

//define type
const TYPE_ALL = 0;
const TYPE_ID = 1;
const TYPE_EMAIL = 2;
const TYPE_SUBJECT = 3;

//default response
$response = ["status" => "error", "result" => null];

//check if invalid request
if(!HasValidRequest()){
    //reponse
    Respond($response);
}

//get data
$data = GetPayload();
//check if failed to get data
if($data === false){
    //reponse
    Respond($response);
}

//create instance
$db = new Suggestion();

//get result
switch($data->queryType){
    case TYPE_ALL:
        $value = $db->GetAllSuggestions();
    break;

    case TYPE_ID;
        $value = $db->GetSuggestionById($data->inputData);
    break;

    case TYPE_EMAIL:
        $value = $db->GetSuggestionsByEmail($data->inputData);
    break;

    case TYPE_SUBJECT:
        $value = $db->GetSuggestionsBySubject($data->inputData);
    break;
}

//check if not failed
if($value !== false){
    $response["status"] = "success";
    $response["result"] = $value;
}

//send response
Respond($response);

/**
 * Return a json object from data received.
 * 
 * @return object|bool False if data is invalid.
 */
function GetPayload(){
    //get send data
    $data = file_get_contents("php://input");

    try{
        //try to decode data
        $data = json_decode($data);
    } catch (Exception $e){
        //return false
        return false;
    }

    //return data
    return $data;
}

/**
 * Check if request is valid.
 * 
 * @return bool
 */
function HasValidRequest() : bool{
    //get send data
    $data = file_get_contents("php://input");

    try{
        //try to decode data
        $data = json_decode($data);
    } catch (Exception $e){
        //return false
        return false;
    }

    //check if all data exists
    if(isset($data->queryType)){
        if(is_numeric($data->queryType)){
            switch($data->queryType){
                case TYPE_ALL:
                    return true;
                break;

                case TYPE_ID;
                    if(isset($data->inputData)){
                        if(is_numeric($data->inputData)){
                            return true;
                        }
                    }
                break;

                case TYPE_EMAIL:
                    if(isset($data->inputData)){
                        if(is_string($data->inputData)){
                            return true;
                        }
                    }
                break;

                case TYPE_SUBJECT:
                    if(isset($data->inputData)){
                        if(is_string($data->inputData)){
                            return true;
                        }
                    }
                break;
            }
        }
    }

    //return false
    return false;
}

/**
 * Send the json encoded value and stop scrip execution.
 * 
 * @param array $responseArray Array to encode to json format.
 * 
 * @return void
 */
function Respond(array $responseArray) : void{
    //output value
    echo (json_encode($responseArray));
    //stop script
    exit();
}
?>