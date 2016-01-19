<?php

class Crud_controller extends Controller {
	
	function __construct() {
		parent::__construct();
	}

	public function Index() 
	{
		$this->view->title = 'CRUD' ;
		$this->view->crud_list = $this->model->get_crud_list();
		$this->view->render("crud/view");
    }
	public function view()
	{
            echo "this is partner index";
    }    
	
}
