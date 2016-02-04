<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PhotoEngine
 *
 * @author samamaju
 */
class PhotoEngine {
    //put your code here
    
    function __construct() {
        $this->casp_db = new Database(DB_NAME);
    }
    
    function collect_photo($data, $id){
        $collect_result = "";
        $get_number = 0;
        
        if($data['id'] != 0){
            $sql = "select photo, name from outlet where id = :id";
            $parameters = array(
                ":id" => $data['id']
            );
            
            $result = $this->casp_db->query($sql,$parameters);
            $old_photo_string = $result[0]['photo'];
            $old_name = $result[0]['name'];
            $old_name = str_replace(" ", "_", $old_name);
            $old_name = strtolower($old_name);                        
            
            $new_name = strtolower($data['outlet_name']);
            $new_name = str_replace(" ", "_", $new_name);                        
            
            $photo_string = str_replace($old_name, $new_name, $old_photo_string);                        
            
            if($data['outlet_name'] != $old_name){
                //rename($sql, $collect_result)
                $explode_photo_name = explode("|", $photo_string);
                $explode_old_photo_name = explode("|", $old_photo_string);
                for($i=0; $i < count($explode_photo_name); $i++){
                    //echo $explode_photo_name[$i]. ' - ' . $explode_old_photo_name[$j] . '<br>';
                    for($j=0; $j<count($explode_old_photo_name);$j++){
                        //echo $explode_photo_name[$i]. ' - ' . $explode_old_photo_name[$j] . '<br>';
                        rename('image/'.$explode_old_photo_name[$j],'image/'. str_replace($old_name, $new_name,$explode_old_photo_name[$j]));
                    }
                }
            }

            if($photo_string != ""){
                $explode_photo_string = explode("|", $photo_string);
                $latest_photo = end($explode_photo_string); //get end of photos to set photo count increase 1
                $photo_name = explode(".", $latest_photo)[0];


                for($i = 0; $i < strlen($photo_name); $i++){
                    if(is_numeric(substr($photo_name, $i, 1))){
                        $get_number .= substr($photo_name, $i, 1);
                    }
                }

                $get_number += 1;
                $collect_result .= $photo_string . '|';
            }
        }
        if($get_number == 0){
            $get_number = 1;
        }
        
        for($i = 0; $i < count($_FILES['photo']['name']); $i++){
            if($_FILES['photo']['name'][$i] != ""){
                $photo_name = $_FILES['photo']['name'][$i];
                $valid_extension = array('jpg', 'jpeg', 'png', "JPEG", "JPG");
                $extension = end(explode('.', $photo_name));
                
                $photo_folder = 'image';
                $photo_name = strtolower(str_replace(" ", "_", $data['outlet_name'])) . '_' . ($i + $get_number) . '.' . $extension;                                
                $photo_path = $photo_folder . '/' . $photo_name;                
                //if($_FILES['photo']['size'][$i] < 300000){
                    
                    if(in_array($extension, $valid_extension)){                                                
                        if(move_uploaded_file($_FILES['photo']['tmp_name'][$i], $photo_path)){
                            $collect_result .= $photo_name;
                            
                            if($i != count($_FILES['photo']['name']) -1){
                                $collect_result .= '|';
                            }
                        }else{        
                           $_SESSION['CASP_STATUS'] = "Cannot upload photo";
                           return null; 
                        }
                    }else{ 
                        $_SESSION['CASP_STATUS'] = "Invalid photo type. Only JPEG, JPG, PNG";
                        return  null;
                    }
                    
                    resize_photo($photo_name, $photo_folder.'/', $photo_folder.'/', 1280, 800, $extension);
                //}else{                    
                   // $_SESSION['CASP_STATUS'] = "Photo size must be less than 300 KB";
                    //return null;
                //}            
            }
        }
                
        if(substr($collect_result, strlen($collect_result) -1, 1) == '|'){
            $collect_result = substr($collect_result, 0, strlen($collect_result)-1);
        }
        
        return $collect_result;
    }
}