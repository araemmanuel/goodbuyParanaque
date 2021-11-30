<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Customer extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblcustomer";
    }	

}

