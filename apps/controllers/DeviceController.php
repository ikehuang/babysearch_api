<?php

class DeviceController extends \Phalcon\Mvc\Controller {
	
	private $_imei;
	private $_token;
	private $_api_key;
	private $_email;
	private $_sso_id;
	
	//private $_test_imei;
	//private $_test_token;
	//private $_test_key;
	//private $_test_email;
	
	private $_apikey;
	
	public function initialize() {		
		$this->_request = new \Phalcon\Http\Request();
		
		$this->view->disable();
		$this->response->setContentType('application/json', 'UTF-8');
		$this->_imei = $this->_request->getPost('imei');
		$this->_token = $this->_request->getPost('token');
		$this->_email = strtolower($this->_request->getPost('email'));
		$this->_api_key = $this->_request->getPost('api_key');
		$this->_sso_id = $this->_request->getPost('sso_id');
		
		//test code for getting data
		//$this->_test_api_key = $this->_request->get('api_key');
		//$this->_test_email = strtolower($this->_request->get('email'));
		//$this->_test_token = $this->_request->get('token');
		//$this->_test_imei = $this->_request->get('imei');
		
		//to check api-key with config
		$config = new \Phalcon\Config\Adapter\Ini("../apps/config/config.ini");
		$this->_apikey = $config->api->apikey;
	}
	
	public function createAction(){
		//check serial number exist or not 
		//$category = $this->_request->getPost('category');
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		//$status = strtolower($this->_request->getPost('status'));
		$status = '';
		//$type = $this->_request->getPost('type');
		$name = $this->_request->getPost('name');
		$photo = $this->_request->getPost('photo');
		$message = $this->_request->getPost('message');
		$expiry_date = '';
		
		//sample data
		//$this->_api_key = 'qwe123';
		//$this->_apikey = 'qwe123';
		//$this->_email = 'ike@ink.net.tw';
		//$serial_number = 'P1B00000000000K';
		//$type = 'pet';
		//$category = 'cats';
		
		//$imei = '152328054557408';
		//$token = '166942940015970|tcOoRAAQrWcDm_84h3O7NN7Z9DM';
		
		//echo urlencode('14hOkQmZb/J+aegISC0D47BUAKff9TBW72wSXT7JV40=');
		
		//find 'type'-P,M,T,A from first letter of serial number
		$type = null;
		
		if(!empty($serial_number))
			$type = $serial_number[0];
		
		switch($type) {
			case "P":
				$type = "Pets";
				break;
			case "M":
				$type = "Human";
				break;
			case "T":
				$type = "Valuables";
				break;
			case "A":
				$type = "All";
				break;
			default:
				break;
		}
		
		$device_message = array("serial_number" => $serial_number, "status" => $status, "type" => $type, "name" => $name, "photo" => $photo, "message" => $message);
		
		$response_data = array(
				'status' => 'fail',
				'$device_message' => $device_message
		);

		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {

			//make sure all inputs are not empty
			if(!empty($serial_number) && !empty($this->_sso_id)) {

			//if(!empty($serial_number) && !empty($this->_email)) {
				//if user exists, then continue to create device...; otherwise, return fail
				if(User::count("sso_id = '{$this->_sso_id}'") > 0) {

				//if(User::count("email = '{$this->_email}'") > 0) {

					//if serial number exists and 'status'='new', then continue to create device...; otherwise, return fail
					if(Device::count(array("conditions" => "status = 'new' AND serial_number = '{$serial_number}'")) > 0) {
					//if serial number doesn't exist, then continue to create device...; otherwise, return fail
					//if(Device::count("serial_number = '{$serial_number}'") == 0) {
						
						//create Device
						//$device = new Device();
						$device = Device::findFirst("serial_number = '{$serial_number}'");
						
						//register device status as Normal
						$status = 'normal';
						
						
						//if(!empty($device->expiry_date))
							//$expiry_date = '2016-06-31';
						
						$device->serial_number = $serial_number;
						$device->status = $status;
						$device->type = $type;
						$device->name = $name;
						$device->photo = $photo;
						$device->message = $message;
						$device->expiry_date = $expiry_date;
						
						//set default timezone
						date_default_timezone_set( "Asia/Taipei" );
						$device->created = date('Y-m-d H:i:s');
						
						//register expiry date for 1-year for now
						$device->expiry_date = date('Y-m-d H:i:s', strtotime($device->created . " + 365 day"));
						
						//$device->category = $category;
						$device->open = 'N';
						$device->email = $this->_email;
						$device->sso_id = $this->_sso_id;
						
						$device->update();
														
						$device_message = array("serial_number" => $serial_number, "status" => $status, "type" => $type, "name" => $name, "photo" => $photo, "message" => $message);
							
						$response_data = array(
								'status' => 'success',
								'$device_message' => $device_message
						);
						
						//create device info under given type and connect them with device id
						if($type == "Pets") {
							
							$pet_info = new PetInfo();
							$pet_info->did = $device->did;
							$pet_info->create();
							
						}
						else if($type == "Human") {
							
							$human_info = new HumanInfo();
							$human_info->did = $device->did;
							$human_info->create();
							
						}
						else if($type == "Valuables") {
							
							$valuable_info = new ValuableInfo();
							$valuable_info->did = $device->did;
							$valuable_info->create();
							
						}
						else {
							//echo "No category found.";
						}
						
						//create Photos
						$photos = new Photos();
						$photos->did = $device->did;
						$photos->create();
						
					}
					else {
						$return_message = "Failed to create device. Serial number existed\n";
					}
				}
			}
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
	
	public function updateCoordinatesAction() {
		
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		
		$latitude = $this->_request->getPost('latitude');
		$longitude = $this->_request->getPost('longitude');
		$battery_status = $this->_request->getPost('battery_status');
		
		$device_message = array("latitude" => $latitude, 'longitude' => $longitude, 'battery_status' => $battery_status);
		
		$response_data = array(
				'status' => 'fail',
				'device_message' => $device_message
		);
		
		//$this->_api_key = 'qwe123';
		//$this->_apikey = 'qwe123';
		//$this->_email = 'ike@ink.net.tw';
		//$serial_number = 'DB4D46XFKMX';
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			//sample data
			//$serial_number = 'BD1B46XFKMX';
			//$latitude= "221.213";
			//$longitude = "112.123";
			//$battery_status = "";

			if(Device::count("serial_number = '{$serial_number}'") > 0) {
			//if email-serial_number pair exists in the system, then continue...; otherwise, fail.
			//if(Device::count(array("conditions" => "email = '{$this->_email}' AND serial_number = '{$serial_number}'")) > 0) {
				
				$device = Device::findFirst("serial_number = '{$serial_number}'");
				
				if(!empty($latitude))
					$device->latitude = $latitude;
				if(!empty($longitude))
				$device->longitude = $longitude;
				if(!empty($battery_status))
				$device->battery_status = $battery_status;
				
				if($device->update() == false) {
					$message = "Failed to update. Please refer to return message for possible errors.\n";
						
					foreach($device->getmessages() as $msg) {
						$message = $msg . "\n";
					}
				}
				else {
					$message = "Device updated successfully.\n";
				
					$device_message = array("latitude" => $latitude, 'longitude' => $longitude, 'battery_status' => $battery_status);
				
					$response_data = array(
							'status' => 'success',
							'device_message' => $device_message
					);
				}
				
				if($device->status == "lost") {
				
					//push notifications when device status lost
					if(!empty($device->name))
						$msg = '有人發現"' . $device->name . '"';
					else
						$msg = '有人發現 "' . substr($serial_number, 3, 14) . '"';
				
					if((empty($_SESSION))) {
						
						$mobiles = Mobile::find("sso_id = '{$device->sso_id}' and token is not null and token != ''");
													
						if(!empty($mobiles)) {
							$android_send = "N";
							$apple_send = "N";
							foreach($mobiles as $mobile) {
								if($android_send == 'N') {
									$android_send  = $this->_send_android_notification($msg, $serial_number, $mobile->token);
								}
									
								if($apple_send == "N") {
									$apple_send = $this->_send_apple_notification($msg, $serial_number, $mobile->token);
								}
							}
						}
					}
					
					//header("Location: " . "http://{$_SERVER['HTTP_HOST']}/guestbook/create?serial_number=" . $serial_number);
					//header("Location: " . "http://{$_SERVER['HTTP_HOST']}/device?sn=" . $serial_number);
				}
			}
		}
			
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}

	public function updateAction(){
		$category = $this->_request->getPost('category');
		$status = strtolower($this->_request->getPost('status'));
		//$type = $this->_request->getPost('type');
		$name = $this->_request->getPost('name');
		//$photo = $this->_request->getPost('photo');
		$photo = "";
		$message = $this->_request->getPost('message');
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		//$expiry_date = "2015-12-30";
		$open = strtolower($this->_request->getPost('open'));
		
		//for updating pet...
		$pet_name = $this->_request->getPost('pet_name');
		$pet_sex = $this->_request->getPost('pet_sex');
		$pet_birthday = $this->_request->getPost('pet_birthday');
		$pet_height = $this->_request->getPost('pet_height');
		$pet_weight = $this->_request->getPost('pet_weight');
		$pet_temperament = $this->_request->getPost('pet_temperament');
		$pet_talents = $this->_request->getPost('pet_talents');
		$pet_description = $this->_request->getPost('pet_description');
		$pet_chip_number = $this->_request->getPost('pet_chip_number');
		$pet_desex = $this->_request->getPost('pet_desex');
		$pet_vaccine_type = $this->_request->getPost('pet_vaccine_type');
		$pet_bloodtype = $this->_request->getPost('pet_bloodtype');
		$pet_bloodbank = $this->_request->getPost('pet_bloodbank');
		$pet_disability = $this->_request->getPost('pet_disability');
		$pet_insurance = $this->_request->getPost('pet_insurance');
		$pet_hospital_name = $this->_request->getPost('pet_hospital_name');
		$pet_hospital_phone = $this->_request->getPost('pet_hospital_phone');
		$pet_hospital_address = $this->_request->getPost('pet_hospital_address');
		$pet_hospital_city = $this->_request->getPost('pet_hospital_city');
		$pet_hospital_district = $this->_request->getPost('pet_hospital_district');
		$pet_hospital_postal = $this->_request->getPost('pet_hospital_postal');
		$pet_hospital_country = $this->_request->getPost('pet_hospital_country');
		
		//for updating human...
		$human_firstname = $this->_request->getPost('human_firstname');
		$human_lastname = $this->_request->getPost('human_lastname');
		$human_nickname = $this->_request->getPost('human_nickname');
		$human_sex = $this->_request->getPost('human_sex');
		$human_birthday = $this->_request->getPost('human_birthday');
		$human_height = $this->_request->getPost('human_height');
		$human_weight = $this->_request->getPost('human_weight');
		$human_bloodtype = $this->_request->getPost('human_bloodtype');
		$human_disease = $this->_request->getPost('human_disease');
		$human_disability = $this->_request->getPost('human_disability');
		$human_medications = $this->_request->getPost('human_medications');
		$human_hospital_name = $this->_request->getPost('human_hospital_name');
		$human_hospital_phone = $this->_request->getPost('human_hospital_phone');
		$human_hospital_address = $this->_request->getPost('human_hospital_address');
		$human_hospital_city = $this->_request->getPost('human_hospital_city');
		$human_hospital_district = $this->_request->getPost('human_hospital_district');
		$human_hospital_postal = $this->_request->getPost('human_hospital_postal');
		$human_hospital_country = $this->_request->getPost('human_hospital_country');
		
		//for updating valuable...
		$valuable_name = $this->_request->getPost('valuable_name');
		$valuable_description = $this->_request->getPost('valuable_description');
		
		//find 'type'-P,M,T,A from first letter of serial number
		$type = null;
		
		if(!empty($serial_number))
			$type = $serial_number[0];
		
		switch($type) {
			case "P":
				$type = "Pets";
				break;
			case "M":
				$type = "Human";
				break;
			case "T":
				$type = "Valuables";
				break;
			case "A":
				$type = "All";
				break;
			default:
				break;
		}
		
		$device_message = array("serial_number" => $serial_number, "status" => $status, "type" => $type, "name" => $name, "photo" => $photo, "message" => $message, "open" => $open);
			
		$response_data = array(
				'status' => 'fail',
				'device_message' => $device_message
		);
		
		//sample code
		//$this->_api_key = 'qwe123';
		//$this->_apikey = 'qwe123';
		//$this->_email = 'franky@ink.net.tw';
		//$serial_number = 'AB4B46XFKMX';
		//$status = 'lost';
		//$type = 'human';
		//$name = 'little george';
		//$message = 'please contact me if you happen to pick this up at 999132094. Thanks!';
		
		//pet:
		//$pet_name = 'birdie';
		//$pet_sex = 'male';
		//$pet_birthday = '2014-02-14';
		//$pet_height = '80';
		//$pet_weight = '25';
		//$pet_temperament = 'friendly';
		//$pet_talents = 'handshake';
		//$pet_description = 'gold color fur with white collar around his neck';
		//$pet_chip_number = '2134567';
		//$pet_desex = 'yes';
		//$pet_vaccine_type = 'canine vaccine';
		//$pet_bloodtype = 'A';
		//$pet_bloodbank = 'taipei';
		//$pet_disability = '';
		//$pet_insurance = '';
		//$pet_hospital_name = 'taipei vet clinic';
		//$pet_hospital_phone = '01298347';
		//$pet_hospital_address = 'big on road, taipei';
		
		//human:
		//$human_firstname = 'george';
		//$human_lastname = 'washington';
		//$human_nickname = 'gwbaby';
		//$human_sex = 'male';
		//$human_birthday = '2001-10-10';
		//$human_height = '20';
		//$human_weight = '35';
		//$human_bloodtype = 'O';
		//$human_disease = '';
		//$human_disability = '';
		//$human_medications = '';
		//$human_hospital_name = 'US general hospital';
		//$human_hospital_phone = '72017777';
		//$human_hospital_address = 'statue of freedom, america.';
		
		//valuable:
		//$valuable_name = 'iphone 6';
		//$valuable_description = 'moms birthday present';
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
		
			//sample data
			//$serial_number = 'KB2A56XFKMX';
			//$status = "lost";
			//$type = 'nfc';
			//$name = 'apples tag';
			//$photo = 'apple123374.png';
			//$message = 'my name is apple.vPlease contact my owner at 94887766';
			//$category = 'pets';
			
			if(Device::count(array("conditions" => "sso_id = '{$this->_sso_id}' AND serial_number = '{$serial_number}'")) > 0) {
			//if email-serial_number pair exists in the system, then continue...; otherwise, fail.
			//if(Device::count(array("conditions" => "email = '{$this->_email}' AND serial_number = '{$serial_number}'")) > 0) {
				
				$device = Device::findFirst("sso_id = '{$this->_sso_id}' AND serial_number = '{$serial_number}'");
				//$device = Device::findFirst("email = '{$this->_email}' AND serial_number = '{$serial_number}'");
				
				//category missing in device message?
				
				if(!empty($status)){
					//filter status
					switch($status) {
						case "lost":
							$device->status = $status;
							break;
						case "normal":
							$device->status = $status;
							break;
						default:
							break;
					}
				}
				
				//if device is lost, then switch ON the "open" flag to signal
				//if($device->status == 'lost')
					//$device->open = 'Y';
				//else
					//$device->open = 'N';
				
				//assign device name according to different types
				if(!empty($type)){
					switch($type) {
						case "Pets":
							if(!empty($pet_name)){
								$device->name = $pet_name;
							}
							break;
						case "Human":
							if(!empty($human_nickname)){
								$device->name = $human_nickname;
							}
							break;
						case "Valuables":
							if(!empty($valuable_name)){
								$device->name = $valuable_name;
							}
							break;
						default:
							break;
					}
				
					$device->type = $type;
				}

				if(!empty($name)){
					$device->name = $name;
				}	
					
				//$device->photo = $photo;
				if(!empty($message)){
					$device->message = $message;
				}
				
				if(!empty($open)){
					$device->open = $open;
				}
				
				//filter input category with system
				//if(Category::count("name = '{strtolower($category)}'" == 0)) {
					//$category = '';
				//}
				if(!empty($category)){
					$device->category = $category;
				}
				
				//for uploading device photo
				// Check if the user has uploaded files
				if($this->request->hasFiles() == true){
					$uploads = $this->request->getUploadedFiles();
					$isUploaded = false;
					
					foreach($uploads as $upload){
					
						//Move the file into the application
						$path = 'upload/'.md5(uniqid(rand(), true)).'-'.strtolower($upload->getname());
						($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
						
						if($isUploaded) {
							//if(preg_match("/photo/",$upload->getKey())) {
						
								//strip from input key(eg.photos.1) to get id
								//$newkey = preg_replace("/^photos./","",$upload->getKey());
						
								$device->photo = "http://{$_SERVER['HTTP_HOST']}/".$path;
								
								$photo = $device->photo;
							//}
						}
					}
				}
				
				$device->update();
				
				//$_POST['photo']
					
				//update device info under type
				if($type == "Pets") {
						
					//PetInfo: update
					$pet_info = PetInfo::findFirst("did = '{$device->did}'");

					if(!empty($pet_name))
						$pet_info->name = $pet_name;
					if(!empty($pet_sex))
						$pet_info->sex = $pet_sex;
					if(!empty($pet_birthday))
						$pet_info->birthday = $pet_birthday;
					if(!empty($pet_height))
						$pet_info->height = $pet_height;
					if(!empty($pet_weight))
						$pet_info->weight = $pet_weight;
					if(!empty($pet_temperament))
						$pet_info->temperament = $pet_temperament;
					if(!empty($pet_talents))
						$pet_info->talents = $pet_talents;
					if(!empty($pet_description))
						$pet_info->description = $pet_description;
					if(!empty($pet_chip_number))
						$pet_info->chip_number = $pet_chip_number;
					if(!empty($pet_desex))
						$pet_info->desex = $pet_desex;
					if(!empty($pet_vaccine_type))
						$pet_info->vaccine_type = $pet_vaccine_type;
					if(!empty($pet_bloodtype))
						$pet_info->bloodtype = $pet_bloodtype;
					if(!empty($pet_bloodbank))
						$pet_info->bloodbank = $pet_bloodbank;
					if(!empty($pet_disability))
						$pet_info->disability = $pet_disability;
					if(!empty($$pet_insurance))
						$pet_info->insurance = $pet_insurance;
					if(!empty($pet_hospital_name))
						$pet_info->hospital_name = $pet_hospital_name;
					if(!empty($pet_hospital_phone))
						$pet_info->hospital_phone = $pet_hospital_phone;
					if(!empty($pet_hospital_address))
						$pet_info->hospital_address = $pet_hospital_address;
					if(!empty($pet_hospital_city))
						$pet_info->hospital_city = $pet_hospital_city;
					if(!empty($pet_hospital_district))
						$pet_info->hospital_district = $pet_hospital_district;
					if(!empty($pet_hospital_postal))
						$pet_info->hospital_postal = $pet_hospital_postal;
					if(!empty($pet_hospital_country))
						$pet_info->hospital_country = $pet_hospital_country;

					$pet_info->update();						
				}
				else if($type == "Human") {
						
					//HumanInfo: update
					$human_info = HumanInfo::findFirst("did = '{$device->did}'");

					if(!empty($human_firstname))
						$human_info->firstname = $human_firstname;
					if(!empty($human_lastname))
						$human_info->lasname = $human_lastname;
					if(!empty($human_nickname))
						$human_info->nickname = $human_nickname;
					if(!empty($human_sex))
						$human_info->sex = $human_sex;
					if(!empty($human_birthday))
						$human_info->birthday = $human_birthday;
					if(!empty($human_height))
						$human_info->height = $human_height;
					if(!empty($human_weight))
						$human_info->weight = $human_weight;
					if(!empty($human_bloodtype))
						$human_info->bloodtype = $human_bloodtype;
					if(!empty($human_disease))
						$human_info->disease = $human_disease;
					if(!empty($human_disability))
						$human_info->disability = $human_disability;
					if(!empty($human_medications))
						$human_info->medications = $human_medications;
					if(!empty($human_hospital_name))
						$human_info->hospital_name = $human_hospital_name;
					if(!empty($human_hospital_phone))
						$human_info->hospital_phone = $human_hospital_phone;
					if(!empty($human_hospital_address))
						$human_info->hospital_address = $human_hospital_address;
					if(!empty($human_hospital_city))
						$human_info->hospital_city = $human_hospital_city;
					if(!empty($human_hospital_district))
						$human_info->hospital_district = $human_hospital_district;
					if(!empty($human_hospital_postal))
						$human_info->hospital_postal = $human_hospital_postal;
					if(!empty($human_hospital_country))
						$human_info->hospital_country = $human_hospital_country;

					$human_info->update();
				}
				else if($type == "Valuables") {
				
					//ValuableInfo: update
					$valuable_info = ValuableInfo::findFirst("did = '{$device->did}'");
				
					if(!empty($valuable_name))
						$valuable_info->name = $valuable_name;
					if(!empty($valuable_description))
						$valuable_info->description = $valuable_description;
						
					$valuable_info->update();
				}
				else {
					//echo "No category found.";
				}
				
				$device_message = array("serial_number" => $serial_number, "status" => $status, "type" => $type, "name" => $name, "photo" => $photo, "message" => $message);
				
				$response_data = array(
						'status' => 'success',
						'device_message' => $device_message
				);
			}
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();

	}

	public function deleteAction(){
		
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		
		//sample data
		//$this->_api_key = 'qwe123';
		//$this->_apikey = 'qwe123';
		//$serial_number = 'ilHK46XFKMX';
		//$this->_email = 'watson@ink.net.tw';
		
		$response_data = array(
				'status' => 'fail'
		);
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			if(Device::count(array("conditions" => "sso_id = '{$this->_sso_id}' AND serial_number = '{$serial_number}'")) > 0) {
			//if email-serial_number pair exists in the system, then continue...; otherwise, fail.
			//if(Device::count(array("conditions" => "email = '{$this->_email}' AND serial_number = '{$serial_number}'")) > 0) {
				
				$device = Device::findFirst("sso_id = '{$this->_sso_id}' AND serial_number = '{$serial_number}'");
				//$device = Device::findFirst("email = '{$this->_email}' AND serial_number = '{$serial_number}'");
				
				$gid = $device->gid;
				
				//make a copy of did and type for the "will-be" deleted device
				$did = $device->did;
				$type = $device->type;
				
				//set device to be new
				$device->status = "new";
				$device->type = null;
				$device->name = null;
				$device->photo = null;
				$device->message = null;
				$device->expiry_date = null;
				$device->latitude = null;
				$device->longitude = null;
				$device->battery_status = null;
				$device->category = null;
				$device->open = null;
				$device->email = null;
				$device->gid = null;
				$device->created = null;
				$device->lost_message = null;
				$device->lost_date = null;
				$device->lost_time = null;
				$device->lost_location = null;
				$device->lost_spec = null;
				$device->lost_contact_id = null;
				$device->sso_id = null;
				
				$device->update();
				
				
				$message = "Device deleted successfully.\n";
				
				//delete guestbook
				if(Guestbook::count("gid = '{$gid}'") > 0) {
					
					$guestbook = Guestbook::find("gid = '{$gid}'");
					$guestbook->delete();
					
				}
				
				//delete info
				if($type == 'Pets') {
					
					//PetInfo:
					$pet_info = PetInfo::findFirst("did = '{$did}'");
					$pet_info->delete();
				
				}
				else if($type == 'Human') {
					
					//HumanInfo:
					$human_info = HumanInfo::findFirst("did = '{$did}'");
					$human_info->delete();
					
				}
				else if($type == 'Valuables') {
				
					//ValuableInfo:
					$valuable_info = ValuableInfo::findFirst("did = '{$did}'");
					$valuable_info->delete();

				}
				else {
					//echo "No category found.";
				} 
				
				//delete photos:(if any)
				if(Photos::findFirst("did = '{$did}'") > 0) {
					
					$photos = Photos::findFirst("did = '{$did}'");
					$photos->delete();
				}
				
				$response_data = array(
						'status' => 'success'
				);
							
			}
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
	
	public function listAction(){
		
		$tag_status = strtolower($this->_request->getPost('tag_status'));
		
		$device_list = array();
		
		$response_data = array(
				'status' => 'fail',
				'device_list' => $device_list
		);
		
		//test code for listing all devices under given email account
		
		//sample data
		//$this->_api_key = 'qwe123';
		//$this->_apikey = 'qwe123';
		//$this -> sso_id = '3e8a54fd43234d0ba1304aaf499c4c95';
		//$this->_email='ike@ink.net.tw';
		//$tag_status = '';
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			//check email and sso_id
			//if(Mobile::count(array("conditions" => "email = '{$this->_email}' AND sso_id = '{$this->_sso_id}'")) > 0) {
				
				if(User::count("sso_id = '{$this->_sso_id}'") > 0) {
				//if the given email has devices, then continue...; otherwise, return fail
				//if(Device::count("email = '{$this->_email}'") > 0) {
					
					//may add more status later...
					switch ($tag_status) {
						case "lost":
							$conditions = "sso_id = '{$this->_sso_id}' AND status = 'lost'";
							//$conditions = "email = '{$this->_email}' AND status = 'lost'";
							break;
						case "normal":
							$conditions = "sso_id = '{$this->_sso_id}' AND status = 'normal'";
							//$conditions = "email = '{$this->_email}' AND status = 'normal'";
							break;
						default:
							$conditions = "sso_id = '{$this->_sso_id}'";
							//$conditions = "email = '{$this->_email}'";
							break;
					}
					
					//$devices = Device::find("email = '{$this->_email}'");
					$devices = Device::find(array($conditions, "columns" => "serial_number, status, type, name, photo, message, category, latitude, longitude, battery_status, expiry_date, open"));
					
					foreach ($devices as $device) {
						$device_list[] = $device;
					}
					/*
					 for($i=0; $i<count($devices); $i++) {
					var_dump($device_list[$i]->serial_number);
					}
						
					$device_list = array(
							array("serial_number" => $serial_number, "status" => $status, "type" => $type, "name" => $name, "photo" => $photo, "message" => $message, "latitude" => $latitude, 'longitude' => $longitude, 'battery_status' => $battery_status, 'expiry_date' => $expiry_date),
							array("serial_number" => $serial_number, "status" => $status, "type" => $type, "name" => $name, "photo" => $photo, "message" => $message, "latitude" => $latitude, 'longitude' => $longitude, 'battery_status' => $battery_status, 'expiry_date' => $expiry_date),
							array("serial_number" => $serial_number, "status" => $status, "type" => $type, "name" => $name, "photo" => $photo, "message" => $message, "latitude" => $latitude, 'longitude' => $longitude, 'battery_status' => $battery_status, 'expiry_date' => $expiry_date),
					);
					*/
					
					$response_data = array(
							'status' => 'success',
							'device_list' => $device_list
					);
				}
			//}
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
	
	public function transferExpiryDateAction() {
		$from_serial_number = $this->_request->getPost('from_serial_number');
		$to_serial_number = $this->_request->getPost('to_serial_number');
		
		//$category = 'null';
		$type = 'null';
		$name = 'null';
		$photo='null';
		$message='null';
		$latitude = 'null';
		$longitude = 'null';
		$battery_status = 'null';
		$expiry_date = 'null';
		$serial_number = 'null';
		$status = 'null';

		//sample data
		//$from_serial_number = 'KB2A56XFKMX';
		//$to_serial_number = 'CR2A56XFKMX';
		
		$device_message = array("serial_number" => $serial_number, "status" => $status, "type" => $type, "name" => $name, "photo" => $photo, "message" => $message, "latitude" => $latitude, 'longitude' => $longitude, 'battery_status' => $battery_status, 'expiry_date' => $expiry_date);
		
		$response_data = array(
				'status' => 'fail',
				'device_message' => $device_message
		);
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			if((Device::count("sso_id = '{$this->_sso_id}' AND serial_number = '{$from_serial_number}'") > 0)
				&& (Device::count("sso_id = '{$this->_sso_id}' AND serial_number = '{$to_serial_number}'") > 0)) {
			//check if both 'from' and 'to' serial numbers are existed and belonged to the same email account
			//if((Device::count("email = '{$this->_email}' AND serial_number = '{$from_serial_number}'") > 0)
				//&& (Device::count("email = '{$this->_email}' AND serial_number = '{$to_serial_number}'") > 0)) {
				
				$fromDevice = Device::findFirst("sso_id = '{$this->_sso_id}' AND serial_number = '{$from_serial_number}'");
				//$fromDevice = Device::findFirst("email = '{$this->_email}' AND serial_number = '{$from_serial_number}'");
				$toDevice = Device::findFirst("sso_id = '{$this->_sso_id}' AND serial_number = '{$to_serial_number}'");
				//$toDevice = Device::findFirst("email = '{$this->_email}' AND serial_number = '{$to_serial_number}'");
				
				//transfer date calculations...
				$today = strtotime(date('Y-m-d'));
				$fromDate = strtotime($fromDevice->expiry_date);
				$toDate = strtotime($toDevice->expiry_date);
				
				//check to make sure 'fromDevice' has longer expiry date
				//compare between today and from device expire date ,add remain date to to device
				if(strtotime($toDevice->expiry_date) < strtotime($fromDevice->expiry_date)) {
					
					//$toDevice->expiry_date = $fromDevice->expiry_date;
					
					$toDevice->expiry_date = date('Y-m-d', ($toDate + $fromDate - $today));
					
					$toDevice->update();
					$fromDevice->delete();
					
					$device_message = array("serial_number" => $serial_number, "status" => $status, "type" => $type, "name" => $name, "photo" => $photo, "message" => $message, "latitude" => $latitude, 'longitude' => $longitude, 'battery_status' => $battery_status, 'expiry_date' => $expiry_date);
					
					$response_data = array(
							'status' => 'success',
							'device_message' => $device_message
					);
				}
			}
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
		
	}
	
	public function getInfoAction() {
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		
		//sample data
		//$this->_api_key = 'qwe123';
		//$this->_apikey = 'qwe123';
		//$this->_email = 'ike@ink.net.tw';
		//$serial_number = 'INK46XFKMX';
		
		//find 'type'-P,M,T,A from first letter of serial number
		$type = null;
		
		if(!empty($serial_number))
			$type = $serial_number[0];
		
		switch($type) {
			case "P":
				$type = "Pets";
				break;
			case "M":
				$type = "Human";
				break;
			case "T":
				$type = "Valuables";
				break;
			case "A":
				$type = "All";
				break;
			default:
				break;
		}
		
		$device_info = array();
		
		$response_data = array(
				'status' => 'fail',
				'device_info' => $device_info
		);
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			if(Device::count("serial_number = '{$serial_number}'") > 0) {
			//if email-serial_number pair exists in the system, then continue...; otherwise, fail.
			//if(Device::count(array("conditions" => "email = '{$this->_email}' AND serial_number = '{$serial_number}'")) > 0) {
			//if serial number exists, then continue...; otherwise, return fail
			//if(Device::count("serial_number = '{$serial_number}'") > 0) {
		
				//get info under given serial number
				$device = Device::findFirst("serial_number = '{$serial_number}'");
				
				/**
				 from terry 
					
				switch(strtolower($category)) {
					case "dogs":
						$category = "pet";
						$sql = <<<EOT
select devices.did,devices.serial_number,devices.status,
pet_info.name as pet_name,pet_info.sex 
from devices 
left outer join pet_info on pet_info.did = devices.did 
where devices.email = '{$_POST['email']}' 
EOTl
		
					    $query = $this->db->query($sql);
		   				$result = $query->fetch();
		   				
		   				if(!empty($result)) {
		   				//$result = array ("id" => id,serial_number => serial_number....);
		   				
	   						$this->db->query("select photo from photos where did={$result['id']}");
		    				$result['photos'] = $query->fetchAll();
		   				//$result = array ("id" => id,serial_number => serial_number....,"photos" => array());
		   				}
						break;
					case "cats":
						$type = "pet";
						break;
					default:s
						break;
				}
				
				**/
		
				if($type == 'Pets') {
					$pet_info = PetInfo::findFirst(array("did = '{$device->did}'", "columns" => "name, sex, birthday, height,
													weight, temperament, talents, description, chip_number, desex, vaccine_type,
													bloodtype, bloodbank, disability, insurance, hospital_name, hospital_phone,
													hospital_address"));
					
					$device_info[] = $pet_info;
				}
				else if($type == 'Human') {
					$human_info = HumanInfo::findFirst(array("did = '{$device->did}'", "columns" => "firstname, lastname, nickname,
														sex, birthday, height, weight, bloodtype, disease, disability, medications,
														hospital_name, hospital_phone, hospital_address"));
					
					$device_info[] = $human_info;
				}
				else if($type == 'Valuables') {
					$valuable_info = ValuableInfo::findFirst(array("did = '{$device->did}'",  "columns" => "name, description"));
					
					$device_info[] = $valuable_info;
				}
				else {
					//echo "No category found.";
				}
				
				$response_data = array(
						'status' => 'success',
						'device_info' => $device_info
				);
			}
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}

	public function getPhotosAction() {
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		
		$photos = array();
		
		$response_data = array(
				'status' => 'fail',
				'photos' => $photos
		);
		
		//sample data
		//$this->_api_key = 'qwe123';
		//$this->_apikey = 'qwe123';
		//$this->_email = 'brucelee@gmail.com';
		//$serial_number = 'INK46XFKMX';
		//$type = 'valuable';
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			if(Device::count(array("conditions" => "sso_id = '{$this->_sso_id}' AND serial_number = '{$serial_number}'")) > 0) {
			//if email-serial_number pair exists in the system, then continue...; otherwise, fail.
			//if(Device::count(array("conditions" => "email = '{$this->_email}' AND serial_number = '{$serial_number}'")) > 0) {
			//if serial number exists, then continue...; otherwise, return fail
			//if(Device::count("serial_number = '{$serial_number}'") > 0) {
				
				//find all photos under given serial number
				$device = Device::findFirst("serial_number = '{$serial_number}'");
				
				$photos = Photos::find("did = '{$device->did}'");
				
				foreach ($photos as $photo) {
					$photo_list[] = $photo->photo;
				}
					
				$response_data = array(
						'status' => 'success',
						'photos' => $photo_list
				);

			}
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
	
	/**
	 * name = photo[photo_id] when update
	 */
	public function updatePhotosAction() {
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		
		//for uploading photos...
		//$uploads = $this->_request->getPost('photos');
		
		//for photos...
		$photo_list = array();
		
		//$photos = array();
		
		$response_data = array(
				'status' => 'fail',
				'photos' => $photo_list
		);
		
		//sample data
		//$this->_api_key = 'qwe123';
		//$this->_apikey = 'qwe123';
		//$this->_email = 'brucelee@gmail.com';
		//$serial_number = 'INK46XFKMX';
		//$type = 'valuable';
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			if(Device::count(array("conditions" => "sso_id = '{$this->_sso_id}' AND serial_number = '{$serial_number}'")) > 0) {
			//if email-serial_number pair exists in the system, then continue...; otherwise, fail.
			//if(Device::count(array("conditions" => "email = '{$this->_email}' AND serial_number = '{$serial_number}'")) > 0) {
			//if serial number exists, then continue...; otherwise, return fail
			//if(Device::count("serial_number = '{$serial_number}'") > 0) {
						
				//Photos:
				//find did
				$device = Device::findFirst("serial_number = '{$serial_number}'");
				
				$photos = Photos::find("did = '{$device->did}'");
				
				//set counter
				$counter = count($photos);
				
				// Check if the user has uploaded files
				if($this->request->hasFiles() == true){
					$uploads = $this->request->getUploadedFiles();
					$isUploaded = false;
				
					foreach($uploads as $upload){
						
						//Move the file into the application
						$path = 'upload/'.md5(uniqid(rand(), true)).'-'.strtolower($upload->getname());
						($upload->moveTo($path)) ? $isUploaded = true : $isUploaded = false;
						
						if($isUploaded) {							
							if(preg_match("/photo/",$upload->getKey())) {
								
								//strip from input key(eg.photos.1) to get id
								$newkey = preg_replace("/^photos./","",$upload->getKey());
								
								//if device has >= 10 photos, delete from first add to lasts
								if($counter >= 10) {
									$photo = Photos::findFirst("did = '{$device->did}'");
									$photo->delete();
								}
								
								$photo = new Photos();
								$photo->did = $device->did;
								$photo->photo = "http://{$_SERVER['HTTP_HOST']}/".$path;
								$photo->create();
									
								$counter++;
								
								//for listing updated photos
								$photo_list[] = $photo->photo;
								
								//else {
									
									//$photo_list[$newkey]->photo = "http://{$_SERVER['HTTP_HOST']}/".$path;
									//$photo_list[$newkey]->update();
								//}
								//$photo = Photos::findFirst("id = '{$newkey}'");
								
								//$photo->photo = "http://{$_SERVER['HTTP_HOST']}/".$path;
								//$photo->update();
								// do something after upload success
							}
							//else if(preg_match("/document/",$upload->getKey())) { 
								//echo "document";
							//}
							//else {
								// update device photo
								//$device->photo = "http://{$_SERVER['HTTP_HOST']}/".$path;
								//$device->update();
							//}
								
						}
					}
					
					$response_data = array(
							'status' => 'success',
							'photos' => $photo_list
					);
				}
			}
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
	
	public function deleteAllPhotosAction() {
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		
		$response_data = array(
				'status' => 'fail'
		);
		
		//sample data
		//$this->_api_key = 'qwe123';
		//$this->_apikey = 'qwe123';
		//$this->_email = 'brucelee@gmail.com';
		//$serial_number = 'INK46XFKMX';
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			if(Device::count(array("conditions" => "sso_id = '{$this->_sso_id}' AND serial_number = '{$serial_number}'")) > 0) {
			//if email-serial_number pair exists in the system, then continue...; otherwise, fail.
			//if(Device::count(array("conditions" => "email = '{$this->_email}' AND serial_number = '{$serial_number}'")) > 0) {
			//if serial number exists, then continue...; otherwise, return fail
			//if(Device::count("serial_number = '{$serial_number}'") > 0) {
		
				//Photos:
				//find did
				$device = Device::findFirst("serial_number = '{$serial_number}'");
		
				$photos = Photos::find("did = '{$device->did}'");
				
				foreach ($photos as $photo) {
					//$path = preg_replace("http://{$_SERVER['HTTP_HOST']}/","",$photo->photo);
					//var_dump($path);
					//unlink($path);
					//need testing
					$name = $photo->name;
					$newname = preg_replace("/http://{$_SERVER['HTTP_HOST']}/upload//", "", $name);
					$path = getcwd();
					unlink($path . "\\upload\\" . $newname);
					$photo->delete();
				}
				
				$response_data = array(
						'status' => 'success'
				);
			}
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
	
	public function getStatusAction() {
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		
		$tag_status = null;
		
		//sample data
		//$this->_api_key = 'qwe123';
		//$this->_apikey = 'qwe123';
		//$serial_number = 'AB4B46XFKMX';
		
		$response_data = array(
				'status' => 'fail',
				'tag_status' => $tag_status
		);
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			//if serial number exists, then continue...; otherwise, return fail
			if(Device::count("serial_number = '{$serial_number}'") > 0) {
				
				//find status
				$device = Device::findFirst("serial_number = '{$serial_number}'");
				
				$tag_status = $device->status;
			}
			else {
				$tag_status = 'invalid';
			}
			
			$response_data = array(
					'status' => 'success',
					'tag_status' => $tag_status
			);
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
	
	public function importAction() {
	
		$this->view->disable();
		$file = getcwd()."/data/test_serial.csv";
	
		if (($handle = fopen($file, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	
				$device = new Device();
				$device->serial_number = $data[1];
				$device->status = 'new';
				$device->create();
			}
				
			fclose($handle);
		}
	}
	
	private function _send_android_notification($msg, $sn, $token) {
		// API access key from Google API's Console
		define( 'API_ACCESS_KEY', 'AIzaSyDfmE5CeBGdP9eCVMbhykDkZ0jBaMS9mBM' );
		
		
		$registrationIds = array( $token );
		
		// prep the bundle
		$msg = array
		(
				'msg' 	=> $msg,
				'sn'	=> $sn
		);
		
		$fields = array
		(
				'registration_ids' 	=> $registrationIds,
				'data'			=> $msg
		);
		
		$headers = array
		(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
		);
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		
		$new_result = json_decode($result);
		return $new_result->success == 1 ? 'Y' : 'N';
		
	}
	
	private function _send_apple_notification($msg, $serial_number, $token) {
		
			exec("php ".getcwd()."/push.php {$token} {$serial_number} $msg");
			return  'N';
	}
	
	public function indexAction() {
	}
	
	public function pushAction() {
		
		$id = $this->_request->get('id');
		
		$msg = Bulletin::findFirst("id = {$id}");
		
		$device_list = Device::find("status = 'normal' and sso_id is not null GROUP BY sso_id");

		foreach($device_list as $device) {
			
			//$mobiles = Mobile::find("sso_id = '{$device->sso_id}' and token is not null and token != ''");
			$mobiles = Mobile::find(array("conditions" => "sso_id = '{$device->sso_id}' and token is not null and token != ''", "order" => "mid desc"));
		
			if(!empty($mobiles)) {
				
				$android_send = "N";
				$apple_send = "N";
				foreach($mobiles as $mobile) {
					
					if($android_send == 'N') {
						//echo $mobile->token;
						//echo $device->serial_number;
						//echo $msg->message;
						$android_send  = $this->_send_android_notification($msg, $device->$serial_number, $mobile->token);
					}
						
					if($apple_send == "N") {
						$apple_send = $this->_send_apple_notification($msg, $device->$serial_number, $mobile->token);
						//echo $mobile->token;
						//echo $device->serial_number;
						//echo $msg->message;
					}
				}
			}
		}
	}
	
	/*
	public function checkAction() {

		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		
		$response_data = array(
				'status' => 'fail',
				'exist' => 'N'
		);
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			//check if email-serial number pair exists
			if(Device::count("email = '{$this->_email}' AND serial_number = '{$serial_number}'") > 0) {
			
				$response_data = array(
						'status' => 'success',
						'exist' => 'Y'
				);
			}
		}
			
		//$tag_message = array("serial_number","");
		//$response_data = array(
		//		'status' => 'success',
		//		'exist' => count($tag_message) > 0 ? 'Y' : 'N'
		//);
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
	*/
	/**
	public function getMessageAction() {
		
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		
		$message = "";
		
		$response_data = array(
				'status' => 'fail',
				'message' => $message
		);
		
		//sample data
		$serial_number = 'RF1C61XFKMX';		
		
		//if api_key match, continue...; otherwise, return fail
		if($this->_api_key == $this->_apikey) {
			
			$device = Device::findFirst("serial_number = '{$serial_number}'");
			
			$message = $device->message;
			
			$response_data = array(
					'status' => 'success',
					'message' => $message
			);
		}
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
	**/
}

/*
public function transferAction() {
		$serial_number = strtoupper($this->_request->getPost('serial_number'));
		$transfer_email = $this->_request->getPost('transfer_email');

		$response_data = array(
				'status' => 'success',
				'transfer_status' => 'pending'
		);
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
	
	public function confirmTransferAction() {
		$serial_number = strtoupper($this->_request->getPost('serial_number'));

		$response_data = array(
				'status' => 'success',
				'transfer_status' => 'confirmed'
		);
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
		
	}
	
	public function getTransferListAction() {
		$category = 'Category';
		$type = 'Type';
		$name = "Franky Device";
		$latitude = "123213.2131313";
		$longitude = "2132132.12313";
		$battery_status = "low";
		$serial_number = "124124123";
		$status = 'normal';
		
		$device_list = array(
				array("serial_number" => $serial_number, "status" => $status, "type" => $type, "name" => $name, "latitude" => $latitude, 'longitude' => $longitude, 'battery_status' => $battery_status, 'transfer_status' => 'pending')
		);
		
		$response_data = array(
				'status' => 'success',
				'device_list' => $device_list
		);
		
		$this->response->setContent(json_encode($response_data));
		$this->response->send();
	}
 */