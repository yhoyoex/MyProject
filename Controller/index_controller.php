<?php

class Index_controller extends Controller 
{
    function __construct() 
    {
        parent::__construct();
    }

    public function Index()
    {    
        $this->view->project_name = "Project One";
        $this->view->page_header = "Home" ;
        $this->view->panel_title = "Dashboard" ;
        $this->view->render("index/index");
    }
  
    public function ajax_index()
    {
        $this->view->render("index/index", true);
    }
    
}