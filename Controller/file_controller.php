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
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_get_data($id) {
        // GET DATA DOCUMENT
        $data = $this->model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_view_file($id) {
        // AJAX VIEW DOCUMENT
        $this->view->data = $this->model->get_by_id($id);
        $this->view->render("file/ajax_view_file", true);
    }

    public function ajax_edit_file($id) {
        // AJAX EDIT DOCUMENT
        $this->view->data = $this->model->get_by_id($id);
        $this->view->render("file/ajax_edit_file", true);
    }
 
    public function get_reserve_id() {
        $data = $this->model->reserve_id();
        echo json_encode($data);
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
        require('UploadHandler.php');
        $upload_handler = new UploadHandler();

/*
        $options = array(
            "upload_urk" => "asdf",
            "..." => "asdf",
            "print_response" => $false;
            );

        $upload_handler = new UploadHandler($options);
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
        $data = $this->model->get_AllTagss();
        echo json_encode($data);
    }
}