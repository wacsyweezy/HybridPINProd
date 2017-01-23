<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Templ extends MX_Controller {
    public function templContent($data) {
        $this->load->view('myview', $data);
    }
}

?>