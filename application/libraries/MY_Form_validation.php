<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File Uploading Class Extension
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Uploads
 * @author      Harrison Emmanuel (Eharry.me)
 * @link        https://www.eharry.me/blog/post/my-codeigniter-upload-extension/
 */
class MY_Form_Validation extends CI_Form_Validation
{
    public $CI;

    function run($module = '', $group = '')
    {
       (is_object($module)) AND $this->CI = &$module;
        return parent::run($group);
    }
	
	public function is_unique($str, $field)
	{
		sscanf($field, '%[^.].%[^.]', $table, $field);
		return is_object($this->CI->db)
        ? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 0)
        : FALSE;
	}
}

