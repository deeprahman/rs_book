<?php
namespace classes;

class Register{

    public function validEmail(string &$email):bool{
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }
    public function lengthUser(string &$user_name){
        if(strlen($user_name)>=4){
            return true;
        }
        return false;
    }
    public function lengthPass(string &$pass){
        if(strlen($pass)>=6){
            return true;
        }
        return false;
    }
    //Check if the form is not properly filled
    public function inputEmty(array &$input){
        foreach($input as $key => $value){
            if(!$value){
                $empty_array[]="$key";
            }
        }
            return $empty_array;
    }
}