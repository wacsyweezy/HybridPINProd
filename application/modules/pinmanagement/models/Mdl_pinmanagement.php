<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mdl_Pinmanagement extends CI_Model {
    function __construct() {
        parent::__construct();
    }
	# TABLE DEFINITION
    function get_table() {
        $table = "HY_STOCK_MASTER";
        return $table;
    } 	
	# CUSTOME QUERY 
	function _custom_query($mysql_query) {
        $query = $this->db->query($mysql_query);
        return $query;
    }
	# CUSTOME QUERY FOR ROWNUMS
    function _custom_num_rows_query($mysql_query) {
        $query = $this->db->query($mysql_query);
        $num_rows = $query->num_rows();
        return $num_rows;
    }
}
