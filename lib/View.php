 <?php

class View {

	function __construct() {
		//echo 'this is the view';
	}

	public function render($name, $noHeader=false, $noInclude = false)
	{
		if ($noInclude == true) {
			require 'View/' . $name . '.php';	
		}
		else {
			if($noHeader == false) require 'View/header.php';
			require 'View/' . $name . '.php';
			if($noHeader == false) require 'View/footer.php';	
		}
	}
}