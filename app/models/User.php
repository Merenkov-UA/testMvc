<?php
namespace app\models;
use app\core\base\Model;

class User extends Model{
  

    public $table = 'users';
    public $key = 'id';
    public $balance ;
    

   
       function isAuthorized($login, $pass) {
        if(empty($this->pdo)) return false;
        $answer = $this->findOneUser($login);
        if(!$answer){
            return false;
        }
        else if( hash( 'SHA256', $pass . $answer[0]['pass_salt'] ) 
            !== $answer[0]['pass_hash']
        ) {
            return false;
        }
        
        $this->id = $answer[0]['id'];
        
        return true ;
    }

    

};