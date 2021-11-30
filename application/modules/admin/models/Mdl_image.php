<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Image extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblimage";
    }	
		
}

