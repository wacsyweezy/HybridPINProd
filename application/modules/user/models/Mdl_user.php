<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mdl_User extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_table() {
        $table = 'HY_USER_PROFILE';
        return $table;
    }
    
    //--------------------USER ROLE--------------------
    function get_UserRole_table() {
        $table = "HY_USER_ROLES";
        return $table;
    }

    function getUserRoles() {
        $table = $this->get_UserRole_table();
        $query = $this->db->get($table);
        return $query;
    }
    function getRoleValue($id) {
        $table = $this->get_UserRole_table();
        $this->db->where('ROLE_ID', $id);
        $query = $this->db->get($table)->result();
        return $query;
    }
  //----- ----------------------------------------------

    function getAll() {
        $table = $this->get_table();
        $query = $this->db->get($table);
        return $query;
    }

    function get($order_by) {
        $table = $this->get_table();
        $this->db->order_by($order_by);
        $query = $this->db->get($table);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $table = $this->get_table();
        $this->db->limit($limit, $offset);
        $this->db->order_by($order_by);
        $query = $this->db->get($table);
        return $query;
    }

    function get_where($id) {
        $table = $this->get_table();
        $this->db->where('USER_ID', $id);
        $query = $this->db->get($table);
        return $query;
    }

    function get_where_custom($col, $value) {
        $table = $this->get_table();
        $this->db->where($col, $value);
        $query = $this->db->get($table);
        return $query;
    }

    function _insert($data) {
        $table = $this->get_table();
        $this->db->insert($table, $data);
    }

    function _update($id, $data) {
        $table = $this->get_table();
        $this->db->where('USER_ID', $id);
        $this->db->update($table, $data);
    }

    function _delete($id) {
        $table = $this->get_table();
        $this->db->where('USER_ID', $id);
        $this->db->delete($table);
    }

    function count_where($column, $value) {
        $table = $this->get_table();
        $this->db->where($column, $value);
        $query = $this->db->get($table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function count_all() {
        $table = $this->get_table();
        $query = $this->db->get($table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function get_max() {
        $table = $this->get_table();
        $this->db->select_max('id');
        $query = $this->db->get($table);
        $row = $query->row();
        $id = $row->id;
        return $id;
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
