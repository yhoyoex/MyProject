<?php

class dashboard_controller extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function Index(){ 
        echo "this is Dashboard index";
    }
        
    public function view(){
        //$name = 
        $this->view->render("dashboard/view");
        
    }    
    
    public function save_partner(){
        echo " this is partner save";
    }
        
    public function lock_partner(){
        echo " this is partner lock";
    }    
}