<?php

class file_controller extends Controller 
{
    function __construct()  {
        parent::__construct();
    }

    public function Index() {
        // DOCUMENT HOME
        $this->view->project_name = "Project One";
        $this->view->page_header = "Document Keeper";
        $this->view->render("file/view");
    }

    public function view_result() {
        // DOCUMENT RESULT
        $this->view->render("file/result", true);
    }

    public function view_content_result($keyword) {
        // DOCUMENT RESULT CONTENT
        $this->view->list = $this->model->get_data_result($keyword);
        $this->view->render("file/view_content", true);
    }

    public function ajax_add_file() {
        // AJAX ADD DOCUMENT
        $this->save_image();
        $this->model->put_data();
        $this->model->put_tags();
        //echo json_encode(array("status" => TRUE));
    }

    public function ajax_view_file($id) {
        // AJAX VIEW DOCUMENT
        $this->view->client_info = $this->model->get_client_info();
        $this->view->data = $this->model->get_by_id($id);
        $this->view->render("file/ajax_view_file", true);
    }

    public function ajax_get_data_file($id) {
        $this->view->client_info = $this->model->get_client_info();
        $this->view->res = $this->model->get_by_id($id);
        //$this->view->token = $this->render_token();
        $this->view->render("file/ajax_add_edit_file", true);
    }

    public function reserve_id() {
        $this->view->client_info = $this->model->get_client_info();
        $this->view->res = $this->model->reserve_id();
        $this->view->render("file/ajax_add_edit_file", true);
    }
 
    public function ajax_update_file() {
        // AJAX UPDATE DOCUMENT
        $this->save_image();
        $this->model->update_data();
        $this->model->put_tags();
        echo json_encode(array("status" => TRUE));
    }

    public function upload_file() {
        error_reporting(E_ALL | E_STRICT);
        require('lib/UploadHandler.php');

        $path_files = dirname($_SERVER['SCRIPT_FILENAME']).'/public/file/files/';
            if (!file_exists($customer_path_files)) {
                @mkdir($customer_path_files);
        }

        $path_thumbnails = dirname($_SERVER['SCRIPT_FILENAME']).'/public/file/files/thumbnail/';
            if (!file_exists($customer_path_thumbnails)) {
                @mkdir($customer_path_thumbnails);
        }
        
        $options=array(
            'upload_dir' => $path_files,
            'upload_url' => URL.'File/upload_file',
            'thumbnail' => array(
                'upload_dir' => $path_thumbnails,
                'upload_url' => URL.'File/upload_file',
                'max_width' => 80,
                'max_height' => 80
            ),
            'auto_orient' => true,
            //'print_response' => false;
        );

        $upload_handler = new UploadHandler($options);

        /*
        $res = $upload_handler->generate_response(adsf);
        echo $res;
        */
    }

    public function save_image() {
        $tablesData = json_decode($_POST['pTableData'],TRUE);
        $this->model->put_image($tablesData);
    }

    public function ajax_delete_file($id) {
        $this->model->delete_by_id();
        echo json_encode(array("status" => TRUE));
    }

     public function delete_image() {
        $this->model->delete_image_by_name();
        echo json_encode(array("status" => TRUE));
    }

    public function show_AllTags() {
        $data = $this->model->get_AllTags();
        echo json_encode($data);
    }

    public function download($id) {
        $this->view->res = $this->model->get_by_id($id);
        $this->view->render("file/download", true);
    }

    public function download_zip($file_download, $file_download_name) {
        $files_to_zip = array($file_download);
        $result = $this->model->create_zip($files_to_zip, $file_download_name);
    }

    public function get_preview_img($name){
        $url = $this->model->get_url_img($name);
        header("Content-Type: image/jpeg");
        //header('Content-type: application/pdf');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
    }
}