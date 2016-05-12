<?php

class File_Model extends Model {
	function __construct() {
        parent::__construct();
    }

	public function get_data_result($keyword) {

		$keyword = $this->prepare_search_keyword($keyword);
		if(trim($keyword) == "") return array();
 
		$sql = "SELECT files.*, user_login.id AS user_id, user_login.name, user_login.username 
				FROM files 
				LEFT JOIN user_login 
				ON files.create_user_id = user_login.id 
				WHERE MATCH(file_name, description, tag, all_document_keyword) AGAINST (:keyword IN BOOLEAN MODE) 
				AND files.is_archived = 0";
		
		$res = $this->db->query($sql, array(":keyword" => $keyword));
		return $res;
	}

	private function prepare_search_keyword($keyword) {
		$keyword_res = "+" . trim($keyword);
		if($keyword_res == "+") return "";
		$keyword_res = str_replace(" ", " +", $keyword_res);

		$keyword_hacked = "\"".$keyword."\"";
		$keyword_hacked = str_replace("\"\"", "\"", $keyword_hacked);
		$keyword_res = " (" . $keyword_res . ") " . $keyword_hacked;

		return $keyword_res;
	}

	public function reserve_id() {
		$this->db->query("START TRANSACTION");
		$this->db->query("INSERT INTO files () VALUES ()");
		$this->db->query("ROLLBACK");
		$res = $this->db->query("SELECT LAST_INSERT_ID() AS id");
		return $res[0];
	}

	public function put_data() {
		$user = Session::get("user");
		$sql = "INSERT INTO files (id, file_name, description, tag, memo, path, create_user_id)
				VALUES(:id, :file_name, :description, :tag, :memo, :path, :create_user_id)";
		
		$res = $this->db->query (
			$sql, array (
				":id" => $_POST['id'],
				":file_name" => $_POST['file_name'],
				":description" => $_POST['description'],
				":tag" => $_POST['tag'],
				":memo" => $_POST['memo'],
				":path" => $_FILES['files']['name'],
				":create_user_id" => $user['id'] 
			)
		);
	}

	public function put_image($tablesData) {
		$user = Session::get("user");
		$sql = "INSERT INTO files_image (img_name, img_store_name, img_size, files_id, upload_by)
				VALUES";
				$valuesArr = array();
				foreach($tablesData as $row) {
					$img_name = trim($row['img_name']);
					$img_size = trim($row['img_size']);
					$file_id = $row['files_id'];
					$upload_by = $user['id'];
					$image_store_name = trim($row['img_store_name']);
					$valuesArr[] = "('$img_name', '$image_store_name', '$img_size', $file_id, $upload_by)";
					$this->imageserver = new ImageServerEngine();
        			$this->imageserver->set_image_received_to_server($image_store_name);
				}
				$sql .= implode(',', $valuesArr);

				$res = $this->db->query($sql);
	}

	public function get_by_id($id) {
		$sql = "SELECT files.*, user_1.name create_user_name, user_2.name archived_user_name
				FROM files
				LEFT JOIN user_login user_1 ON files.create_user_id = user_1.id
				LEFT JOIN user_login user_2 ON files.archived_user_id = user_2.id
				WHERE files.id=:id ";

		$res = $this->db->query($sql, array(":id" => $id));

		$res[0]["images"] = $this->get_images_by_file_id($id);

		return $res[0];
	}

	function get_images_by_file_id($id) {
		$sql = "SELECT * FROM files_image
				WHERE files_id = :id
				AND status = 0 ";
		
		$res = $this->db->query($sql, array(":id"=>$id));
		return $res;
	}

	public function update_data() {
		$user = Session::get("user");

		$sql = "UPDATE files
				SET file_name = :file_name, description = :description, tag = :tag, memo = :memo, archived_user_id = :archived_user_id
				WHERE id = :id";
		
		$res = $this->db->query (
			$sql, array (
				":id" => $_POST['id'],
				":file_name" => $_POST['file_name'],
				":description" => $_POST['description'],
				":tag" => $_POST['tag'],
				":memo" => $_POST['memo'],
				":archived_user_id" => $user['id'] 
			)
		);
	} 

	public function delete_by_id() {
		$sql = "UPDATE files SET is_archived = 1 WHERE id=:id";
		$res = $this->db->query($sql, array(":id" => $_POST['id']));
		return $res;
	} 

	public function delete_image_by_name() {
		$sql = "UPDATE files_image SET status = 1 WHERE img_name = :name ";
		$res = $this->db->query($sql, array(":name" => $_GET['name']));
		return $res;
	}

	function get_AllTags() {
		$sql = "SELECT tags_name FROM files_tags ";
		$res = $this->db->query($sql);
		foreach ($res as $row) {
            $tags[] = $row['tags_name'];
        }
	 	return $tags;
	}

	public function put_tags() {
		$user = Session::get("user");
		$tag = $_POST['tag'];
		$tagarr = explode( ',', $tag );
		$sql = "INSERT IGNORE INTO files_tags(tags_name)
				VALUES";
					foreach ($tagarr as $value) {
						$dataArr[] = "('$value')";
					}
					
					$sql .= implode(',', $dataArr);
					$this->db->query($sql);
	}
	public function get_client_info() {
		$this->imageserver = new ImageServerEngine();
		return $this->imageserver->get_client_info();
	}

	public function get_url_img($name) {
		$client_info = $this->get_client_info();
		$client_name = $client_info['client_name'];
		
		$server = $client_info['ip_server'] ;
		$url = "http://" . $server . "login/client_login/R";             
		$fields = array(
		    "client_id" => $client_info['client_name'],
		    "client_password" => $client_info['client_password']
		);                        
		foreach ($fields as $key => $v) {
		    $field_string .= $key .'=' . $v . '&';
		}
		$field_string = rtrim($field_string, '&');     
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_POST, count($fields));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $field_string);
		$token = trim(curl_exec($curl));
		curl_close($curl);

		$url = "http://" . $server . "login/client_logout/";
		foreach ($fields as $key => $v) {
		    $field_string .= $key .'=' . $v . '&';
		}

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_exec($curl);
		curl_close($curl);


		$url = "http://".$client_info['ip_server']."image/download/".$name."/large?token=".$token."&client_id=".$client_info['client_name'];
		return $url;
	}

	public function create_zip($files = array(),$destination = '',$overwrite = false) {
        //if the zip file already exists and overwrite is false, return false
        if(file_exists($destination) && !$overwrite) { return false; }
        //vars
        $valid_files = array();
        //if files were passed in...
        if(is_array($files)) {
            //cycle through each file
            foreach($files as $file) {
                //make sure the file exists
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have good files...
        if(count($valid_files)) {
            //create the archive
            $zip = new ZipArchive();
            if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach($valid_files as $file) {
                $zip->addFile($file,$file);
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
            
            //close the zip -- done!
            $zip->close();
            
            //check to make sure the file exists
            return file_exists($destination);
        }
        else
        {
            return false;
        }
    }

}