<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class My_Model extends CI_Model {
    
    protected $tableName;

	
    function __construct()
    {
        parent::__construct();     
        
    }
	function verify_user_role($username, $user_role)
    {   
		$q = $this->db->query("SELECT * FROM tbluser WHERE lower(role) = lower('$user_role') AND username = '$username'");
        if ($q->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
	}
    function get_col_where($col, $whereCol, $whereValue)
    {
        $this->db->select($col)->from($this->tableName)->where("" . $whereCol . " = '" . $whereValue . "'");
        $q = $this->db->get();
        $data = $q->result_array();
        if(empty($data))
            return null;
        else
            return $data[0][$col];
            
    } 
	function get_col_all_where($col, $whereCol, $whereValue)
    {
		$q = $this->db->select($col)->from($this->tableName)->where("" . $whereCol . " = '" . $whereValue . "'");
	    return $this->db->get();       
    } 
    function exists($whereCol, $whereValue)
    {
        $this->db->select('*');
        $this->db->from($this->get_table());
        $this->db->where($whereCol . '=' .'"'.$whereValue.'"');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1){
           return true;
        }else{ 
           return false;
        }
    }  
    function get_table() 
    {
        return $this->tableName;
    }

    function get($order_by) 
    {
        $table = $this->get_table();
        $this->db->order_by($order_by);
        $query=$this->db->get($table);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) 
    {
        $table = $this->get_table();
        $this->db->limit($limit, $offset);
        $this->db->order_by($order_by);
        $query=$this->db->get($table);
        return $query;
    }

    function get_where($col,$value) 
    {
        $table = $this->get_table();
        $this->db->where($col, $value);
        $query=$this->db->get($table);
        return $query;
    }

    function get_where_custom($col, $value) 
    {
        $table = $this->get_table();
        $this->db->where($col, $value);
        $query=$this->db->get($table);
        return $query->result_array();
        
    }

    function _insert($data) 
    {
        $table = $this->get_table();
        $query = $this->db->insert($table, $data);
		if($query) {
			return $this->db->insert_id(); 
			//return true;
		} else {
			return false;
		}
    }

    function _update($idColName,$id, $data) 
    {
        $table = $this->get_table();
        $this->db->where($idColName, $id);
        $query = $this->db->update($table, $data);
		if($query) {
			return true;
		} else {
			return false;
		}
    }

    function _delete($idColName, $id) 
    {
        $table = $this->get_table();
        $this->db->where($idColName, $id);
        $query = $this->db->delete($table);
		if($query) {
			return true;
		} else {
			return false;
		}
    }

    function count_where($column, $value) 
    {
        $table = $this->get_table();
        $this->db->where($column, $value);
        $query=$this->db->get($table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function count_all() 
    {
        $table = $this->get_table();
        $query=$this->db->get($table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function get_max() 
    {
        $table = $this->get_table();
        $this->db->select_max('id');
        $query = $this->db->get($table);
        $row=$query->row();
        $id=$row->id;
        return $id;
    }

    function _custom_query($mysql_query)
    {
        $query = $this->db->query($mysql_query);
        return $query;
    }
}