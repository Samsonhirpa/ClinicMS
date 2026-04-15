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

    protected function render($view, array $data = [], array $layoutData = [])
    {
        $layoutData = array_merge([
            'title' => 'ClinicMS',
            'activeMenu' => '',
            'showSidebar' => true,
            'containerClass' => 'app-content p-4',
        ], $layoutData);

        $this->load->view('layouts/header', $layoutData);
        if (!empty($layoutData['showSidebar'])) {
            $this->load->view('layouts/sidemenu', $layoutData);
        }

        $this->load->view($view, $data);
        $this->load->view('layouts/footer');
    }
}
