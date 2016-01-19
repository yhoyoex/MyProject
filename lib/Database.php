<?php

class Database extends PDO {
    function __construct($db_name_in = null) {

        if($db_name_in == null)
            $db_name = DB_NAME;
        else
            $db_name = $db_name_in;
        
        try{
            parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.$db_name, DB_USER, DB_PASS);
        }
        catch (Exception $error_string){
     
            echo " ERROR $error_string";
        }
    }
        
    function query($sql, $parameters = false){

        try{
            $sth = $this->prepare($sql);
            if($parameters){
                $sth->execute($parameters);
            } else {
                $sth->execute();
            }
        }
        catch(PDOException $error_string){
            echo "<pre> SQL ERROR ";
            print_r($error_string);
            echo "</pre>";
        }

        $res = $sth->fetchAll(PDO::FETCH_ASSOC);

        $err = $sth->errorInfo();
        if(ENVIRONMENT == "DEV" && strlen($err[2]) != 0)   {
            echo "<pre> - " . $sql . "<br> </pre>";
            echo $err[2];
            echo "<br>";
        }
        
        return $res;
    }
    
   
}

