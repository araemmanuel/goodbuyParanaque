<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Product_Variant extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblproduct_variant";
    }	
	
}

