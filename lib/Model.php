<?php

class Model {
    function __construct() {
        $this->db = new Database(DB_NAME);
    }

}