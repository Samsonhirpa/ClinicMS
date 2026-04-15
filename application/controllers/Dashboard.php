<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireLogin();
        $this->load->model('User_model');
        $this->load->model('Fee_model');
        $this->load->model('Patient_model');
        $this->load->model('Patient_payment_model');
    }

    public function index()
    {
        $role = (string) $this->session->userdata('role');
        if ($role === 'doctor') {
            redirect('doctor/dashboard');
        }

        if ($role === 'reception') {
            redirect('reception/dashboard');
        }

        if ($role === 'lab') {
            redirect('lab');
        }

        $counts = $this->User_model->getCounts();
        $users = $this->User_model->getAll();

        $roleMap = [];
        foreach ($users as $user) {
            $roleMap[(string) $user->role] = isset($roleMap[(string) $user->role]) ? $roleMap[(string) $user->role] + 1 : 1;
        }

        $feeItems = $this->Fee_model->getAll();
        $revenueProjection = 0;
        foreach ($feeItems as $item) {
            $revenueProjection += (float) $item->amount;
        }

        $this->render('dashboard/index', [
            'counts' => $counts,
            'roleMap' => $roleMap,
            'monthlyAnalytics' => [
                'New Registrations' => min($counts['total_users'] + 4, 100),
                'Active Sessions' => min($counts['active_users'] + 10, 100),
                'Revenue Goal' => min((int) round($revenueProjection / 20), 100),
            ],
            'reports' => [
                'Total configured fee items' => count($feeItems),
                'Projected visit revenue (ETB)' => number_format($revenueProjection, 2),
                'Inactive user accounts' => $counts['inactive_users'],
            ],
        ], [
            'title' => 'Dashboard Analytics',
            'activeMenu' => 'dashboard',
        ]);
    }

    public function reception()
    {
        $this->requireRole(['admin', 'reception']);

        $this->render('dashboard/reception', [
            'totalPatients' => $this->Patient_model->countAll(),
            'pendingRegistrationCount' => $this->Patient_payment_model->countPendingByType('registration'),
            'pendingDiagnoseCount' => $this->Patient_payment_model->countPendingByType('diagnose'),
            'pendingLabCount' => $this->Patient_payment_model->countPendingByType('lab'),
            'opdReadyCount' => count($this->Patient_payment_model->getOpdReadyPatients()),
        ], [
            'title' => 'Reception Dashboard',
            'activeMenu' => 'reception_dashboard',
        ]);
    }
}
