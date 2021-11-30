<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Notification_Message extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblnotification_message";
    }	



}

