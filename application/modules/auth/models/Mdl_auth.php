<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mdl_Auth extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_table() {
        $table = "HY_USER_PROFILE";
        return $table;
    }
////----------------PERMISSION----------------

    function get_Permission_table() {
        $table = "HY_PERMISSIONS";
        return $table;
    }

    function getPermissions() {
        $table = $this->get_Permission_table();
        $query = $this->db->get($table);
        return $query;
    }
    
    function get_permissions_where($permid) {

        $table = $this->get_Permission_table();
        $this->db->where('PERMISSION_ID', $permid);
        $query = $this->db->get($table);

        return $query;
    }
    ////-------------------------------------------
    ////---------------- Role  PERMISSION----------------
    function get_RolePermission_table() {
        $table = "HY_ROLE_PERM_XREF";
        return $table;
    }
    function getRolePermissions() {
        $table = $this->get_RolePermission_table();
        $query = $this->db->get($table);
        return $query;
    }
    function get_role_permissions_where($roleid) {
        $table = $this->get_RolePermission_table();
        $this->db->where('ROLE_ID', $roleid);
        $query = $this->db->get($table);
        return $query;
    }
    ////--------------------------------------------------
    function get_MainMenu_table() {
        $table = "HY_MAIN_MENU";
        return $table;
    }
    function getMainMenu() {
        $table = $this->get_MainMenu_table();
        $query = $this->db->get($table);
        return $query;
    }
    function get_MainMenu_where($id) {
        $table = $this->get_MainMenu_table();
        $this->db->where('MAIN_MENU_ID', $id);
        $query = $this->db->get($table);
        return $query;
    }
     function get_MainMenuName_where($id) {
        $table = $this->get_MainMenu_table();
        $this->db->where('MAIN_MENU_ID', $id);
        $query = $this->db->get($table);
        return $query;
    }
    ////--------------------------------------------------
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

    function get_where($username) {

        $table = $this->get_table();
        $this->db->where('USERNAME', $username);
        $query = $this->db->get($table);

        return $query;
    }

    function get_where_custom($col, $value) {
        $table = $this->get_table();
        $this->db->where($col, $value);
        $query = $this->db->get($table);
        return $query;
    }

    function get_evc_custom($col, $value) {
        $table = "HY_PARTY";
        $this->db->where($col, $value);
        $query = $this->db->get($table);
        return $query;
    }

    function get_code_custom($col, $value) {
        $table = "HY_PARTY";
        $this->db->where($col, $value);
        $query = $this->db->get($table);
        return $query;
    }

    function _insert($data) {
        $table = "HY_USER_PROFILE";
        $this->db->insert($table, $data);
    }

    function _update($id, $data) {
        $table = $this->get_table();
        $this->db->where('recipientid', $id);
        $this->db->update($table, $data);
    }

    function _delete($id) {
        $table = $this->get_table();
        $this->db->where('recipientid', $id);
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
        $this->db->select_max('itemId');
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
