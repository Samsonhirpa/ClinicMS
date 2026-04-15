<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
    }

    protected function requireLogin()
    {
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
    }

    protected function requireRole($roles)
    {
        $this->requireLogin();
        $userRole = $this->session->userdata('role');
        $roles = (array) $roles;

        if (!in_array($userRole, $roles, true)) {
            show_error('Access denied.', 403);
        }
    }
}
