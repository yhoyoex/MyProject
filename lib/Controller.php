<?php

class Controller {

	function __construct() {
		//echo 'Main controller<br />';
		$this->view = new View();
	}
	
	public function loadModel($name) {
		$path = 'Model/'.$name.'_model.php';

                if (file_exists($path)) {
			require 'Model/'.$name.'_model.php';

                        $modelName = $name . '_Model';
			$this->model = new $modelName();
		}		
	}

}