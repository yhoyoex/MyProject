<?php

class Partner_Model extends Model{

    function __construct() {
        parent::__construct();
    }    
    
    function calculate_add($i, $j){
        return (int)$i + (int)$j;
    }
    
    function calculate_multiply($i, $j){
        return (int)$i * (int)$j;
    }
    
    function get_partner_list(){
        
        $sql = "select * from partner";
        $res = $this->db->query($sql);
        return $res;
    }
    function get_partner($id){
        
        $sql = "select * from partner where id = :id";
        $res = $this->db->query($sql, array(":id" => $id));
        if(count($res) == 0) {
            printr ('no data'); 
        }
        
        //$res = $res[0];
        return $res;
    }
}
