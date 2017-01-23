<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mdl_Order extends CI_Model {
    function __construct() {
        parent::__construct();
    }
	# TABLE DEFINITION
    function get_table() {
        $table = "HY_ORDER_CART";
        return $table;
    } 	
    # CHECK CART FOR ITEMS
    function checkCart($partner) {
        $table = $this->get_table();
		$this->db->where('ADDEDBY', $partner);
		$this->db->order_by('ITEM_UNIT_PRICE');
        $query = $this->db->get($table);
        return $query;
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
