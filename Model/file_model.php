<?php

class File_Model extends Model {
	function __construct() {
        parent::__construct();
    }

	public function get_alldata() {
		$sql = "
				SELECT 
					files.*,
					user_login.id as user_id,
					user_login.name,
					user_login.username
				FROM 
					files
				LEFT JOIN 
					user_login
				ON 
					files.create_user_id = user_login.id
				WHERE
					files.is_archived = 0
			";
		
		$res = $this->db->query($sql);
		return $res;
	}

	public function get_data_result($keyword) {

		$keyword = $this->prepare_search_keyword($keyword);
		if(trim($keyword) == "") return array();

//		echo $keyword;
 
		$sql = "SELECT 
					files.*,
					user_login.id as user_id,
					user_login.name,
					user_login.username
				FROM 
					files
				LEFT JOIN 
					user_login
				ON 
					files.create_user_id = user_login.id
				WHERE 
					MATCH(file_name, description, tag) AGAINST (:keyword IN BOOLEAN MODE)
				AND
					files.is_archived = 0";
		
		$res = $this->db->query($sql, array(":keyword" => $keyword));
		return $res;
	}

	private function prepare_search_keyword($keyword){
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
		$sql = "
				INSERT INTO
					files
					(
						id,
						file_name,
						description,
						tag,
						memo,
						path,
						create_user_id
					)
				VALUES
					(
						:id,
						:file_name,
						:description,
						:tag,
						:memo,
						:path,
						:create_user_id
					)
			";
		
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
		$sql = " INSERT INTO
					files_image (
						img_name,
						img_size,
						files_id,
						upload_by
					)
				VALUES";
					$valuesArr = array();
					foreach($tablesData as $row) {
						$img_name = trim($row['img_name']);
						$img_size = trim($row['img_size']);
						$file_id = $row['files_id'];
						$upload_by = $user['id'];
						$valuesArr[] = "('$img_name', '$img_size', $file_id, $upload_by)";
					}

					$sql .= implode(',', $valuesArr);

					$res = $this->db->query($sql);
	} 

	public function get_by_id($id) {
		$sql = " SELECT 
					files.*,
					user_1.name create_user_name,
					user_2.name archived_user_name
				FROM 
					files
					LEFT JOIN user_login user_1 ON files.create_user_id = user_1.id
					LEFT JOIN user_login user_2 ON files.archived_user_id = user_2.id
				WHERE 
					files.id=:id ";

		$res = $this->db->query($sql, array(":id" => $id));

		$res[0]["images"] = $this->get_images_by_file_id($id);

		return $res[0];
	}

	function get_images_by_file_id($id) {
		$sql = "SELECT * FROM
					files_image
				WHERE
					files_id = :id
				AND 
					status = 0 ";
		
		$res = $this->db->query($sql, array(":id"=>$id));
		return $res;
	}

	public function update_data() {
		$user = Session::get("user");

		$sql = "UPDATE 
					files
				SET
					file_name = :file_name,
					description = :description,
					tag = :tag,
					memo = :memo,
					archived_user_id = :archived_user_id
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
		$sql = "SELECT tags_id,tags_name FROM files_tags ";
		$res = $this->db->query($sql);
	 	return $res;
	}

	function get_AllTagss() {
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
		
		
		//$tagarr = array();
		$sql = "INSERT IGNORE INTO
					files_tags
					(
						tags_name
					)
				VALUES";
					foreach ($tagarr as $value) {
						$dataArr[] = "('$value')";
					}
					$sql .= implode(',', $dataArr);
					$this->db->query($sql);
	}  
}