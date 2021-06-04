<?php

//Get files
require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

use Suggestion;

//determine output type
header('Content-type: application/json; charset=utf-8', true);
//default response
$response = ['status' => 'error'];

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

//perform action and if success
if($db->InsertSuggestion($data->email, $data->subject, $data->content)){
    //update response
    $response["status"] = "success";
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
    if(isset($data->email) && isset($data->subject) && isset($data->content)){
        //checkif string
        if(is_string($data->email) && is_string($data->subject) && is_string($data->content)){
            return true;
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