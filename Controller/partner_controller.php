<?php

class Partner_controller extends Controller 
{

    function __construct() {
        parent::__construct();
    }

    public function Index(){ 
        // LIST PARTNER
        echo "this is partner index";
    }
        
    public function view(){
        // VIEW PARTNER
        $id = $_GET['id'];

        $this->view->title = "PARTNER View page";
        
        $this->view->result_1 = $this->model->calculate_add($_GET["a"], $_GET["b"]);
        
        $this->view->result_2 = $this->model->calculate_multiply($_GET["a"], $_GET["b"]);

        $this->view->partner_list = $this->model->get_partner_list();

        $this->view->partner = $this->model->get_partner($id);
        
        $this->view->render("partner/view");

        
    }    
    
    public function save_partner(){
        // SAVE PARTNER
        echo " this is partner save";
    }
        
    public function lock_partner(){
        // LOCK PARTNER
        echo " this is partner lock";
    }    
    
    
    // ---------------
    
    
}