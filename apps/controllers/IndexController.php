<?php

class IndexController extends \Phalcon\Mvc\Controller {

	public function indexAction(){
		/*
		if(isset($_FILES["fileToUpload"])) {
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
				die();
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
				die();
			}
		}
		*/	
		echo "<h1>Hello-IndexController.php!</h1>";
	}	
}