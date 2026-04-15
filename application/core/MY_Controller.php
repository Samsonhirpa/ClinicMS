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
            'pendingRegistrationCount' => 0,
            'pendingDiagnoseCount' => 0,
            'pendingLabCount' => 0,
            'doctorNewPatientCount' => 0,
            'labNewRequestCount' => 0,
        ], $layoutData);

        if (!empty($layoutData['showSidebar'])) {
            $this->load->model('Patient_payment_model');
            $this->load->model('Doctor_case_model');
            $this->load->model('Lab_request_model');
            $layoutData['pendingRegistrationCount'] = $this->Patient_payment_model->countPendingByType('registration');
            $layoutData['pendingDiagnoseCount'] = $this->Patient_payment_model->countPendingByType('diagnose');
            $layoutData['pendingLabCount'] = $this->Patient_payment_model->countPendingByType('lab');
            $layoutData['doctorNewPatientCount'] = $this->Doctor_case_model->countByStatus('new');
            $layoutData['labNewRequestCount'] = $this->Lab_request_model->countByStatus('paid');
        }

        $this->load->view('layouts/header', $layoutData);
        if (!empty($layoutData['showSidebar'])) {
            $this->load->view('layouts/sidemenu', $layoutData);
        }

        $this->load->view($view, $data);
        $this->load->view('layouts/footer');
    }
}
