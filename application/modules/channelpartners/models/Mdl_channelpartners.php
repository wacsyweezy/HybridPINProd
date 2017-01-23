<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mdl_Channelpartners extends CI_Model {
    function __construct() {
        parent::__construct();
    }
	# TABLE DEFINITION
    function get_table() {
        $table = "HY_PARTY";
        return $table;
    }  
    # CHECK CART FOR ITEMS
    function getPartner() {
        $table = $this->get_table();
        $query = $this->db->get($table);
        return $query;
    }
	
	function _custom_query($mysql_query) {
        $query = $this->db->query($mysql_query);
        return $query;
    }

    function _custom_num_rows_query($mysql_query) {
        $query = $this->db->query($mysql_query);
        $num_rows = $query->num_rows();
        return $num_rows;
    }
}
