<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Banner extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblbanner";
    }	
	public function get_banners()
	{
		$sql = "SELECT * FROM tblbanner;";
		$q = $this->db->query($sql);
		return $q->result();
	}
	public function get_banner($id)
	{
		$sql = "SELECT * FROM tblbanner WHERE ban_id = ?;";
		$q = $this->db->query($sql, array($id));
		return $q->result();
	}
}

