<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mdl_Notification extends My_Model{

    function __construct() {
        parent::__construct();
        $this->tableName = "tblnotification";
    }	

	function get_notifs()	
	{
		$sql = "SELECT P.name, PV.sku, N.notif_id, NM.notif_msg_id, NM.message, N.is_addressed, N.is_viewed,
					TIMESTAMPDIFF(MINUTE, N.datetime_added, NOW()) AS mins_ago,
					TIMESTAMPDIFF(HOUR, N.datetime_added, NOW()) AS hrs_ago,
					TIMESTAMPDIFF(DAY, N.datetime_added, NOW()) AS days_ago,
					(SELECT GROUP_CONCAT(DISTINCT O.opt_name ORDER BY O.opt_grp_id ASC) FROM tblproduct_option PO LEFT JOIN tbloption O ON O.opt_id = PO.option_id WHERE PO.sku = PV.sku ORDER BY O.opt_name) as options 
				FROM tblnotification N
					INNER JOIN tblnotification_message NM ON NM.notif_msg_id = N.notif_msg_id 
					INNER JOIN tblproduct P ON P.prod_id = N.prod_id 
					INNER JOIN tblproduct_variant PV ON PV.prod_id = N.prod_id AND PV.sku = N.sku 
				WHERE N.is_addressed = 0 AND N.is_viewed = 0 ORDER BY N.datetime_added DESC";
		$q = $this->db->query($sql);
		return $q->result();
	}
	function get_count_sku()
	{
		$sql = "SELECT COUNT(sku) m FROM `tblnotification` 
					WHERE is_viewed = 0 AND is_addressed = 0";
		$q = $this->db->query($sql);
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	
	function get_notif_id($prod_id, $sku)
	{
		$sql = "SELECT notif_id m FROM `tblnotification` 
					WHERE is_viewed = 0 AND is_addressed = 0
						 AND prod_id = ? AND sku = ?
					ORDER BY notif_id DESC";
		$q = $this->db->query($sql, array($prod_id, $sku));
		$data = $q->row_array();
        if(empty($data))
            return null;
        else
            return $data['m'];
	}
	
}

